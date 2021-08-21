<?php

namespace App\Models\Filter;

use App\Components\FallbackCache\FallbackCache;
use Exception;
use Facades\App\Services\Psykhe\PsykheCatalogService;
use Illuminate\Support\Facades\Facade;
use function iterator_to_array;
use Str;

class Category extends Facade
{
    protected static function flatten($data, $parents = '')
    {
        foreach ($data as $slug => $child) {
            $path = ($parents ? $parents.'/' : '').$slug;

            if (! isset($child['children'])) {
                yield $path => array_merge($child, ['slug' => $slug]);

                continue;
            }

            foreach (static::flatten($child['children'], $path) as $rpath => $rdata) {
                yield $rpath => $rdata;
            }
        }
    }

    public static function Flat($filter = null)
    {
        $flat = [];
        foreach (static::flatten(static::Tree()) as $slug => $category) {
            if ($filter === null) {
                $flat[$slug] = $category;

                continue;
            }

            if (is_callable($filter)) {
                if (call_user_func($filter, $slug, $category)) {
                    $flat[$slug] = $category;
                }

                continue;
            }

            if (
                $slug === $filter || substr($slug, 0, strlen($filter) + 1) === $filter.'/'
            ) {
                $flat[$slug] = $category;
            }
        }

        return $flat;
    }

    protected static function slugToHuman($slug)
    {
        return str_replace('/ And /', ' and ', ucwords(strtolower(str_replace('-', ' ', $slug))));
    }

    public static function Tree()
    {
        $t = [];
        foreach (static::slugs() as $slug => $category) {
            $in_parent = $category['in_parent'];
            $parts     = explode('/', $slug);
            $a         = $parts[0];
            $b         = $parts[1] ?? null;
            $c         = $parts[2] ?? null;

            if (! isset($t[$a])) {
                $t[$a] = [
                    'label'                  => $category['label'],
                    'description_no_login'   => $category['description_no_login'],
                    'description_with_login' => $category['description_with_login'],
                    'heading_no_login'       => $category['heading_no_login'] ?: $category['label'],
                    'heading_with_login'     => $category['heading_with_login'] ?: $category['label'],
                ];
            }

            if (! isset($b)) {
                $t[$a]['in_parent'] = $in_parent;
            }

            if (isset($b)) {
                if (! isset($t[$a]['children'])) {
                    $t[$a]['children'] = [];
                }

                if (! isset($t[$a]['children'][$b])) {
                    $t[$a]['children'][$b] = [
                        'label'                  => $category['label'],
                        'description_no_login'   => $category['description_no_login'],
                        'description_with_login' => $category['description_with_login'],
                        'heading_no_login'       => $category['heading_no_login'] ?: $category['label'],
                        'heading_with_login'     => $category['heading_with_login'] ?: $category['label'],
                    ];
                }

                if (! isset($c)) {
                    $t[$a]['children'][$b]['in_parent'] = $in_parent;
                }
            }

            if (isset($c)) {
                if (! isset($t[$a]['children'][$b]['children'])) {
                    $t[$a]['children'][$b]['children'] = [];
                }

                $t[$a]['children'][$b]['children'][$c] = [
                    'label'                  => $category['label'],
                    'description_no_login'   => $category['description_no_login'],
                    'description_with_login' => $category['description_with_login'],
                    'heading_no_login'       => $category['heading_no_login'] ?: $category['label'],
                    'heading_with_login'     => $category['heading_with_login'] ?: $category['label'],
                    'in_parent'              => $in_parent,
                ];
            }
        }

        return $t;
    }

    public static function single($path, $subtree = null)
    {
        if ($subtree === null) {
            $subtree = static::Tree();
        }

        if (! is_array($path)) {
            $path = explode('/', $path);
        }

        if (isset($subtree[$path[0]])) {
            if (count($path) > 1) {
                if (! isset($subtree[$path[0]]['children'])) {
                    return null;
                }

                return static::single(array_slice($path, 1), $subtree[$path[0]]['children']);
            }

            return $subtree[$path[0]];
        }

        return null;
    }

    public static function cascaded($path = null)
    {
        $cascaded = [];
        $subtree  = null;

        if ($path) {
            $cascaded[] = $path;
            $subtree    = static::single($path);

            if ($subtree === null) {
                return $cascaded;
            }

            if (isset($subtree['children'])) {
                $subtree = $subtree['children'];
            } else {
                $subtree = [];
            }
        } else {
            $subtree = static::Tree();
        }

        foreach ($subtree as $slug => $child) {
            if ($child['in_parent']) {
                $child_path = $path ? ($path.'/'.$slug) : $slug;
                $cascaded   = array_merge($cascaded, static::cascaded($child_path));
            }
        }

        return $cascaded;
    }

    public static function slugs(): iterable
    {
        $categories = FallbackCache::remember(__METHOD__.'::categories', 60 * 60, function () {
            return iterator_to_array(PsykheCatalogService::Categories(), false);
        });
        $menu = FallbackCache::remember(__METHOD__.'::menu', 60 * 60, function () {
            return iterator_to_array(PsykheCatalogService::Menu(), false);
        });

        if (! $categories) {
            throw new Exception('Failed to retrieve categories');
        }

        if (! $menu) {
            throw new Exception('Failed to retrieve menu');
        }

        $slugs = [];
        foreach ($categories as $category) {
            $slugs[implode('/', $category->path)] = $category;
        }

        foreach ($menu as $entry) {
            $category = (
            $entry->category ?
                $entry->category :
                Str::slug($entry->name, '-')
            );

            if (! isset($slugs[$category])) {
                // virtual category (should be just "everything")
                continue;
            }
            $slugs[$category] = array_merge(
                (array) $slugs[$category],
                [
                    'label'      => $entry->name,
                    'sort_order' => $entry->sort_order,
                    'in_menu'    => true,
                ]
            );
        }

        foreach ($slugs as $slug => $category) {
            if (! is_array($category)) {
                $tail         = explode('/', $slug);
                $tail         = array_pop($tail);
                $slugs[$slug] = array_merge(
                    (array) $category,
                    [
                        'label'   => static::slugToHuman($tail),
                        'in_menu' => false,
                    ]
                );
            }
        }

        uasort($slugs, function ($a, $b) {
            if (count($a['path']) != count($b['path'])) {
                return count($a['path']) - count($b['path']);
            }

            if (isset($a['sort_order']) && ! isset($b['sort_order'])) {
                return 1;
            }

            if (! isset($a['sort_order']) && isset($b['sort_order'])) {
                return -1;
            }

            if (! isset($a['sort_order']) && ! isset($b['sort_order'])) {
                return 0;
            }

            return $a['sort_order'] - $b['sort_order'];
        });

        yield from $slugs;
    }
}
