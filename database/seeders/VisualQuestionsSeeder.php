<?php

namespace Database\Seeders;

use App\Models\PersonalityTest\VisualQuestion;
use App\Models\PersonalityTest\VisualQuestionAnswer;
use App\Models\PersonalityTest\VisualQuestionSet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class VisualQuestionsSeeder extends Seeder
{
    public function run()
    {
        $setArray = [
            [
                'code'      => 'style-general',
                'heading'   => 'Style',
                'text'      => 'Which of these is closest to your personal style? Pick two maximum.',
                'maximum'   => 2,
                'order'     => 5,
                'mutable'   => false,
                'questions' => [
                    [
                        'order'     => 1,
                        'code'      => 'a',
                        'text'      => 'City-chic and casual: I love good staples.',
                        'has_image' => true,
                    ],
                    [
                        'order'     => 4,
                        'code'      => 'b',
                        'text'      => 'Streetwear - give me a big sneaker any day.',
                        'has_image' => true,
                    ],
                    [
                        'order'     => 3,
                        'code'      => 'c',
                        'text'      => 'Polished but statement: I love miniskirts and tall boots.',
                        'has_image' => true,
                    ],
                    [
                        'order'     => 6,
                        'code'      => 'd',
                        'text'      => 'Artful and avant-garde, I hate looking basic.',
                        'has_image' => true,
                    ],
                    [
                        'order'     => 7,
                        'code'      => 'e',
                        'text'      => 'Powerful and professional, because of my job.',
                        'has_image' => true,
                    ],
                    [
                        'order'     => 2,
                        'code'      => 'f',
                        'text'      => 'All-black-everything, usually with a tough boot.',
                        'has_image' => true,
                    ],
                    [
                        'order'     => 9,
                        'code'      => 'g',
                        'text'      => 'Feminine - I love a floral print.',
                        'has_image' => true,
                    ],
                    [
                        'order'     => 8,
                        'code'      => 'h',
                        'text'      => 'Free-spirited and festival-ready.',
                        'has_image' => true,
                    ],
                    [
                        'order'     => 5,
                        'code'      => 'i',
                        'text'      => 'Sexy and streamlined: fitted dresses and stilettos.',
                        'has_image' => true,
                    ],
                ],
                'answers' => [],
            ],
            [
                'code'      => 'print',
                'heading'   => 'Prints',
                'text'      => 'How often do you wear solids and prints?',
                'maximum'   => null,
                'order'     => 2,
                'mutable'   => true,
                'questions' => [
                    [
                        'code'      => 'solid',
                        'text'      => 'Solid',
                        'has_image' => true,
                        'maximum'   => 1,
                    ],
                    [
                        'code'      => 'floral',
                        'text'      => 'Floral',
                        'has_image' => true,
                        'maximum'   => 1,
                    ],
                    [
                        'code'      => 'animal',
                        'text'      => 'Animal',
                        'has_image' => true,
                        'maximum'   => 1,
                    ],
                    [
                        'code'      => 'other',
                        'text'      => 'Other',
                        'has_image' => true,
                        'maximum'   => 1,
                    ],
                ],
                'answers' => [
                    [
                        'order' => 1,
                        'code'  => 'often',
                        'text'  => 'Often',
                    ],
                    [
                        'order' => 2,
                        'code'  => 'sometimes',
                        'text'  => 'Sometimes',
                    ],
                    [
                        'order' => 3,
                        'code'  => 'never',
                        'text'  => 'Never',
                    ],
                ],
            ],
            [
                'code'      => 'color',
                'heading'   => 'Colors',
                'text'      => 'Which colors do you like to wear the most? Click all that apply.',
                'maximum'   => null,
                'order'     => 1,
                'mutable'   => true,
                'questions' => [
                    [
                        'code'      => 'black',
                        'text'      => 'All black',
                        'has_image' => true,
                    ],
                    [
                        'code'      => 'neutral',
                        'text'      => 'Neutrals',
                        'has_image' => true,
                    ],
                    [
                        'code'      => 'pale',
                        'text'      => 'White',
                        'has_image' => true,
                    ],
                    [
                        'code'      => 'pastel',
                        'text'      => 'Pastels',
                        'has_image' => true,
                    ],
                    [
                        'code'      => 'warm',
                        'text'      => 'Warm colors',
                        'has_image' => true,
                    ],
                    [
                        'code'      => 'cool',
                        'text'      => 'Cool colors',
                        'has_image' => true,
                    ],
                    [
                        'code'      => 'bright',
                        'text'      => 'Bright colors',
                        'has_image' => true,
                    ],
                    [
                        'code'      => 'sparkle',
                        'text'      => 'Sequin and sparkle',
                        'has_image' => true,
                    ],
                ],
                'answers' => [],
            ],
            [
                'code'      => 'silhouette',
                'heading'   => 'Silhouettes',
                'text'      => 'Which silhouettes do you feel best in? Click all that apply.',
                'maximum'   => null,
                'order'     => 3,
                'mutable'   => true,
                'questions' => [
                    [
                        'code'      => 'pencil',
                        'text'      => 'Straight and pencil',
                        'link_url'  => 'https://click.linksynergy.com/deeplink?id=79BFZqEA/Ao&mid=35663&murl=https%3A%2F%2Fwww.mytheresa.com%2Fen-gb%2Falex-perry-kye-crepe-midi-dress-1631306.html',
                        'link_text' => 'Alex Perry at MyTheresa',
                        'has_image' => true,
                    ],
                    [
                        'code'      => 'relaxed',
                        'text'      => 'Relaxed and boxy',
                        'link_url'  => 'https://click.linksynergy.com/deeplink?id=79BFZqEA/Ao&mid=35663&murl=https%3A%2F%2Fwww.mytheresa.com%2Fen-gb%2Fkhaite-sueanne-cotton-twill-minidress-1637424.html%3Fcatref%3Dcategory',
                        'link_text' => 'Khaite at MyTheresa',
                        'has_image' => true,
                    ],
                    [
                        'code'      => 'flirty',
                        'text'      => 'A-line and flirty',
                        'link_url'  => 'https://click.linksynergy.com/deeplink?id=79BFZqEA/Ao&mid=35663&murl=https%3A%2F%2Fwww.mytheresa.com%2Fen-gb%2Frasario-silk-organza-mindress-1580824.html%3Fcatref%3Dcategory',
                        'link_text' => 'Rasario at MyTheresa',
                        'has_image' => true,
                    ],
                    [
                        'code'      => 'classic',
                        'text'      => 'Classic sheath and column',
                        'link_url'  => 'https://click.linksynergy.com/deeplink?id=79BFZqEA/Ao&mid=35725&murl=https%3A%2F%2Fwww.matchesfashion.com%2Fproducts%2FNorma-Kamali-V-neck-velvet-slip-dress-1413137',
                        'link_text' => 'Norma Kamali at MATCHESFASHION',
                        'has_image' => true,
                    ],
                    [
                        'code'      => 'bold',
                        'text'      => 'Bold and oversized',
                        'link_url'  => 'https://www.tkqlhce.com/click-9283411-14435057?url=https%3A%2F%2Fwww.modaoperandi.com%2Fwomen%2Fp%2Fcecilie-bahnsen%2Flisbeth-dress-mist-matelasse-bandeau-dress-with-gathered-pockets%2F454583',
                        'link_text' => 'Cecilie Bahnsen at Moda Operandi',
                        'has_image' => true,
                    ],
                ],
                'answers' => [],
            ],
            [
                'code'      => 'brand',
                'heading'   => 'Brands',
                'text'      => 'Which brands appeal to you most? Choose three maximum. (Don’t worry, we won’t purposely push you these brands, we just want to get you.)',
                'maximum'   => 3,
                'order'     => 4,
                'mutable'   => false,
                'questions' => [
                    [
                        'code' => 'alexander-mcqueen',
                        'text' => 'Alexander McQueen, Simone Rocha',
                    ],
                    [
                        'code' => 'dolce-gabbana',
                        'text' => 'Dolce & Gabbana, Versace',
                    ],
                    [
                        'code' => 'giambattista-valli',
                        'text' => 'Giambattista Valli, Zimmermann',
                    ],
                    [
                        'code' => 'ralph-lauren',
                        'text' => 'Ralph Lauren, Max Mara',
                    ],
                    [
                        'code' => 'saint-laurent',
                        'text' => 'Saint Laurent, Alexandre Vauthier',
                    ],
                    [
                        'code' => 'tom-ford',
                        'text' => 'Balenciaga, Burberry',
                    ],
                ],
                'answers' => [],
            ],
        ];

        // Convert the set data into a nested collection for easier processing
        $sets = collect($setArray)->map(function (array $setData) {
            return tap(collect($setData), function (Collection $set) {
                foreach (['questions', 'answers'] as $nestedKey) {
                    $set->put($nestedKey, collect($set->get($nestedKey))->map(function (array $data) {
                        return collect($data);
                    }));
                }
            });
        });

        // Remove existing question sets that are not in the seed data
        VisualQuestionSet::query()
            ->whereNotIn('code', $sets->pluck('code'))
            ->delete()
        ;

        $sets->each(function (Collection $setData) {
            // Create / update questions according to seed data
            $set = VisualQuestionSet::updateOrCreate(
                ['code' => $setData->get('code')],
                $setData->except(['code', 'questions', 'answers'])->toArray()
            );

            foreach ([
                'questions' => VisualQuestion::query(),
                'answers' => VisualQuestionAnswer::query(),
            ] as $dataKey => $query) {
                // Remove existing questions and answers that are not in the seed data
                (clone $query)->where('visual_question_set_id', $set->id)
                    ->whereNotIn('code', $setData->get($dataKey)->pluck('code'))
                    ->delete()
            ;

                // Create / update questions and answers from seed data
                $setData->get($dataKey)->each(function (Collection $data) use ($set, $query) {
                    $model = (clone $query)->updateOrCreate(
                        ['code' => $data->get('code')],
                        $data->except('code')->toArray()
                    );

                    $model->set()->associate($set);
                    $model->save();
                });
            }
        });
    }
}
