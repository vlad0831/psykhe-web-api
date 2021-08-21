<?php

namespace App\Http\Controllers\Query;

use App\Components\FallbackCache\FallbackCache;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Query\StoreQueryRequest;
use App\Models\Filter\Brand;
use App\Models\Filter\Category;
use App\Models\Filter\Color;
use App\Services\Psykhe\Contracts\PsykheCatalogService;
use App\Services\Psykhe\Contracts\PsykheQueryService;
use App\Services\Psykhe\Models\Ocean;
use App\Services\Psykhe\Models\Query\Query;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Nadar\Stemming\Stemm;

class QueryController extends ApiController
{
    /**
     * @var PsykheQueryService
     */
    public $queryService;

    /**
     * @var PsykheCatalogService
     */
    public $catalogService;

    /**
     * @param PsykheQueryService   $queryService
     * @param PsykheCatalogService $catalogService
     *
     * @return void
     */
    public function __construct(PsykheQueryService $queryService, PsykheCatalogService $catalogService)
    {
        $this->queryService   = $queryService;
        $this->catalogService = $catalogService;
    }

    /**
     * @param string $identifier
     * @param string $checkpoint
     *
     * @return JsonResponse
     */
    public function resolve(string $identifier, string $checkpoint = '')
    {
        try {
            $response = $this->queryService->ResolveQuery($identifier, $checkpoint);

            return $this->responseOk($response);
        } catch (Exception $controllerException) {
            report($controllerException);
            $errorMessage = config('app.debug') ? $controllerException->getMessage() : 'There has been an error while getting your recommendations';

            return $this->responseInternalError($errorMessage);
        }
    }

    /**
     * @param StoreQueryRequest $request
     *
     * @return JsonResponse
     */
    public function create(StoreQueryRequest $request)
    {
        try {
            $query = new Query();

            if (isset($request->search)) {
                $searchTerms = str_replace(['  '], [' '], trim($request->search));

                if ($searchTerms !== '') {
                    $hijackedQuery = $this->buildHijackedQuery($searchTerms);

                    if ($hijackedQuery) {
                        return $this->responseOk(
                            [
                                'status' => 'redirect',
                                'query'  => $hijackedQuery,
                            ]
                        );
                    }
                    $query->search = $searchTerms;
                }
            }

            $user = auth('sanctum')->user();

            if ($user) {
                $query->user = $user->identifier;
            } else {
                $query->ocean    = new Ocean();
                $query->ocean->o = 4;
                $query->ocean->c = 3;
                $query->ocean->e = 4;
                $query->ocean->a = 2;
                $query->ocean->n = 4;
            }

            $query->mood           = $request->mood;
            $query->recommendation = $request->recommendation;

            $price = [];

            if (count($request->price)) {
                $price = [
                    'min'       => $request->price[0],
                    'max'       => $request->price[1],
                    'priceMode' => '',
                ];
            }

            if (isset($request->modes) && count($request->modes)) {
                if (count($price)) {
                    $price['priceMode'] = $request->modes[0];
                } else {
                    $price = [
                        'priceMode' => $request->modes[0],
                    ];
                }
            }

            $query->brands     = $request->brands;
            $query->categories = $request->categories;
            $query->colors     = $request->colors;
            $query->occasions  = $request->occasions;
            $query->options    = $request->options;
            $query->partners   = $request->partners;
            $query->price      = (object) $price;
            $query->savelists  = $request->savelists;
            $query->products   = $request->products;

            if ($request->limit) {
                $query->limit = $request->limit;
            }

            $response = $this->queryService->CreateQuery($query);

            return $this->responseOk($response);
        } catch (Exception $controllerException) {
            report($controllerException);
            $errorMessage = config('app.debug') ? $controllerException->getMessage() : 'There has been an error while creating your recommendations';

            return $this->responseInternalError($errorMessage);
        }
    }

    /**
     * @param string $searchTerm
     *
     * @return array
     */
    private function buildHijackedQuery(string $searchTerm): array
    {
        if (! $searchTerm) {
            return [];
        }

        $filters = [
            'categories' => [
                'matches' => [],
                'map'     => FallbackCache::remember(__METHOD__.'::categories', 60 * 60, function () {
                    return collect(Category::slugs())->map(function ($category) {
                        return $this->slugifyAndStemify($category['label']);
                    })->flip();
                }),
            ],

            'brands' => [
                'matches' => [],
                'map'     => FallbackCache::remember(__METHOD__.'::brands', 60 * 60, function () {
                    return collect(Brand::List())->flatMap(function ($brand) {
                        return [
                            $this->slugifyAndStemify($brand->name) => $brand->identifier,
                        ];
                    });
                }),
            ],

            'colors' => [
                'matches' => [],
                'map'     => FallbackCache::remember(__METHOD__.'::colors', 60 * 60, function () {
                    return collect(Color::List())->flatMap(function ($color) {
                        return [
                            $this->slugifyAndStemify($color['name']) => $color['identifier'],
                        ];
                    });
                }),
            ],
        ];

        $consumedTermPosition = 0;
        $searchTerms          = explode('-', $this->slugifyAndStemify($searchTerm));

        foreach ($searchTerms as $index => $searchTerm) {
            // If this search term has already been used in a previous match we should
            // not check it again to avoid matching the same word twice
            if ($consumedTermPosition > $index) {
                continue;
            }

            $matchedTerm    = false;
            $remainingTerms = array_slice($searchTerms, $index + 1);

            // Checks if the current word in the search string, or any number of words after it
            // match with a term in a filter. If so, skip checking all the words that made up the match.
            foreach ($filters as $name => $data) {
                $result = $this->resolveMatches([$searchTerm], $remainingTerms, $data['map']);

                if ($result) {
                    $filters[$name]['matches'][] = $filters[$name]['map']->get(
                        implode('-', $result)
                    );

                    $consumedTermPosition = $index + count($result);
                    $matchedTerm          = true;
                    break;
                }
            }

            // If no matches were found the the search term is ambiguous
            if (! $matchedTerm) {
                return [];
            }
        }

        // If more than one brand or color was matched the search is ambiguous
        if (count($filters['brands']['matches']) > 1) {
            return [];
        }

        if (count($filters['colors']['matches']) > 1) {
            return [];
        }

        // If more than one category was matched, sort the category names by ascending length.
        // To be unambiguous, each category must then be a child of the one before it.
        $categories = $filters['categories']['matches'];

        usort($categories, function ($a, $b) {
            return strlen($a) <=> strlen($b);
        });

        for ($i = 0; $i < (count($categories) - 1); $i++) {
            $category     = $categories[$i];
            $nextCategory = $categories[$i + 1];

            $filters['categories']['matches'] = [$nextCategory];

            if (substr($nextCategory, 0, strlen($category)) !== $category) {
                return [];
            }
        }

        return [
            'brands'     => $filters['brands']['matches'],
            'colors'     => $filters['colors']['matches'],
            'categories' => $filters['categories']['matches'],
        ];
    }

    /**
     * Given an array of current words, check to see if when concatenated, these words are in the
     * provided map. If not, take the next word from the remaining terms in the search query and
     * recurse until either a match is found, or there are no remaining terms left.
     *
     * @param array      $currentTerms
     * @param array      $remainingTerms
     * @param Collection $map
     *
     * @return array an array of words that have been found in the provided map
     */
    public function resolveMatches(array $currentTerms, array $remainingTerms, Collection $map): array
    {
        // Return the current search terms if they exist in the map
        if ($map->has(strtolower(implode('-', $currentTerms)))) {
            return $currentTerms;
        }

        // If there are no terms left to try, the term is not in the map
        if (! $remainingTerms) {
            return [];
        }

        // Otherwise append the next term to the current term and recurse
        $currentTerms[] = $remainingTerms[0];
        $remainingTerms = array_slice($remainingTerms, 1);

        return $this->resolveMatches($currentTerms, $remainingTerms, $map);
    }

    /**
     * @param string $terms
     *
     * @return string
     */
    private function slugifyAndStemify(string $terms): string
    {
        $find    = ['&', 'and', 'the', 'in', ','];
        $cleanup = str_replace('  ', ' ', str_replace($find, '', strtolower($terms)));
        $stemmed = Stemm::stemPhrase($cleanup, 'en');

        return str_replace([' '], ['-'], $stemmed);
    }
}
