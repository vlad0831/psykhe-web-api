<?php

namespace Database\Seeders;

use App\Models\PersonalityTest\SurveyQuestion;
use App\Models\PersonalityTest\VisualQuestionSet;
use App\Models\User;
use App\Models\User\PersonalityTest\SurveyResponse;
use App\Models\User\PersonalityTest\VisualResponse;
use App\Models\User\Profile;
use App\Models\User\Traits;
use Illuminate\Database\Seeder;
use stdClass;

class UserSeeder extends Seeder
{
    protected function populatePT($user)
    {
        foreach (SurveyQuestion::all() as $question) {
            $answer   = $question->answers()->orderBy('ordinal')->first();
            $response = SurveyResponse::firstOrNew([
                'user_id'            => $user->id,
                'survey_question_id' => $question->id,
            ]);
            $response->user_id                   = $user->id;
            $response->survey_question_id        = $question->id;
            $response->survey_question_answer_id = $answer->id;
            $response->save();
        }

        $visualQuestionSets = VisualQuestionSet::query()
            ->with('questions', 'answers')
            ->get()
        ;

        foreach ($visualQuestionSets as $set) {
            $questions = $set->questions()->get();
            $i         = 0;
            $maximum   = $set->maximum;

            foreach ($questions as $question) {
                if ($maximum && $i++ >= $maximum) {
                    break;
                }

                VisualResponse::updateOrCreate(
                    [
                        'user_id'            => $user->id,
                        'visual_question_id' => $question->id,
                    ],
                    [
                        'visual_question_answer_id' => $set->answers->first()->id ?? null,
                    ]
                );
            }
        }
    }

    public function run()
    {
        $users = [
            'example@example.com' => [
                'name_first' => 'Firstegname',
                'name_last'  => 'Lastegname',
            ],
            'anabel@psykhefashion.com' => [
                'name_first' => 'Anabel',
                'name_last'  => 'Maldonado',
                'ocean'      => '43424',
            ],
            'jennifer.lopez@example.com' => [
                'name_first' => 'Jennifer',
                'name_last'  => 'Lopez',
                'ocean'      => '43524',
            ],
            'ivanka.trump@example.com' => [
                'name_first' => 'Ivanka',
                'name_last'  => 'Trump',
                'ocean'      => '24432',
            ],
            'laylasevilla@example.com' => [
                'name_first' => 'Layla',
                'name_last'  => 'Sevilla',
                'ocean'      => '43424',
            ],

            'camille@example.com' => [
                'name_first' => 'Camille',
                'name_last'  => 'Example',
                'ocean'      => '24232',
            ],
            'natalie@example.com' => [
                'name_first' => 'Natalie',
                'name_last'  => 'Example',
                'ocean'      => '52341',
            ],

            'ocean11111@example.com' => [
                'name_first' => 'Ocean11111',
                'name_last'  => 'Example',
                'ocean'      => '11111',
            ],
            'ocean22222@example.com' => [
                'name_first' => 'Ocean22222',
                'name_last'  => 'Example',
                'ocean'      => '22222',
            ],
            'ocean33333@example.com' => [
                'name_first' => 'Ocean33333',
                'name_last'  => 'Example',
                'ocean'      => '33333',
            ],
            'ocean44444@example.com' => [
                'name_first' => 'Ocean44444',
                'name_last'  => 'Example',
                'ocean'      => '44444',
            ],
            'ocean55555@example.com' => [
                'name_first' => 'Ocean55555',
                'name_last'  => 'Example',
                'ocean'      => '55555',
            ],
        ];

        foreach ($users as $email => $spec) {
            $user                    = User::firstOrNew(['email' => $email]);
            $user->email             = $email;
            $user->email_verified_at = strtotime('2020-01-01 00:00:00');
            $user->password          = 'password1';
            $user->save();

            if (isset($spec['ocean'])) {
                $ocean           = str_split($spec['ocean'], 1);
                $traits          = Traits::firstOrNew(['user_id' => $user->id]);
                $traits->user_id = $user->id;
                $traits->ocean   = json_encode([
                    'O' => $ocean[0],
                    'C' => $ocean[1],
                    'E' => $ocean[2],
                    'A' => $ocean[3],
                    'N' => $ocean[4],
                ]);
                $traits->save();
            }

            $profile             = Profile::firstOrNew(['user_id' => $user->id]);
            $profile->user_id    = $user->id;
            $profile->avatar     = true;
            $profile->name_first = $spec['name_first'];
            $profile->name_last  = $spec['name_last'];
            $profile->options    = new stdClass();
            $profile->save();

            $this->populatePT($user);
        }
    }
}
