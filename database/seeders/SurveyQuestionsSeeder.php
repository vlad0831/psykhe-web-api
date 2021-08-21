<?php

namespace Database\Seeders;

use App\Models\PersonalityTest\SurveyQuestion;
use App\Models\PersonalityTest\SurveyQuestionAnswer;
use Illuminate\Database\Seeder;

class SurveyQuestionsSeeder extends Seeder
{
    public function run()
    {
        foreach (
            [
                [
                    'quality' => 'O',
                    'text' => 'I am original and creative, often coming up with new ideas.',
                    'code' => 'creative',
                    'answers' => [
                        [
                            'score' => 5,
                            'text' => 'Strongly agree',
                        ],
                        [
                            'score' => 4,
                            'text' => 'Agree',
                        ],
                        [
                            'score' => 3,
                            'text' => 'Somewhat - neutral',
                        ],
                        [
                            'score' => 2,
                            'text' => 'Disagree',
                        ],
                        [
                            'score' => 1,
                            'text' => 'Strongly disagree',
                        ],
                    ],
                ],
                [
                    'quality' => 'O',
                    'text' => 'I avoid philosophical or theoretical discussions.',
                    'code' => 'philosophical',
                    'answers' => [
                        [
                            'score' => 1,
                            'text' => 'Strongly agree',
                        ],
                        [
                            'score' => 2,
                            'text' => 'Agree',
                        ],
                        [
                            'score' => 3,
                            'text' => 'Somewhat - neutral',
                        ],
                        [
                            'score' => 4,
                            'text' => 'Disagree',
                        ],
                        [
                            'score' => 5,
                            'text' => 'Strongly disagree',
                        ],
                    ],
                ],
                [
                    'quality' => 'O',
                    'text' => 'I am unconventional, and unsure if I believe in marriage.',
                    'code' => 'unconventional',
                    'answers' => [
                        [
                            'score' => 5,
                            'text' => 'Strongly agree',
                        ],
                        [
                            'score' => 4,
                            'text' => 'Agree',
                        ],
                        [
                            'score' => 3,
                            'text' => 'Somewhat - neutral',
                        ],
                        [
                            'score' => 2,
                            'text' => 'Disagree',
                        ],
                        [
                            'score' => 1,
                            'text' => 'Strongly disagree',
                        ],
                    ],
                ],
                [
                    'quality' => 'O',
                    'text' => 'I tend to vote for conservative political candidates.',
                    'code' => 'conservative',
                    'answers' => [
                        [
                            'score' => 1,
                            'text' => 'Strongly agree',
                        ],
                        [
                            'score' => 2,
                            'text' => 'Agree',
                        ],
                        [
                            'score' => 3,
                            'text' => 'Somewhat - neutral',
                        ],
                        [
                            'score' => 4,
                            'text' => 'Disagree',
                        ],
                        [
                            'score' => 5,
                            'text' => 'Strongly disagree',
                        ],
                    ],
                ],
                [
                    'quality' => 'O',
                    'text' => 'I have many artistic interests.',
                    'code' => 'artistic',
                    'answers' => [
                        [
                            'score' => 5,
                            'text' => 'Strongly agree',
                        ],
                        [
                            'score' => 4,
                            'text' => 'Agree',
                        ],
                        [
                            'score' => 3,
                            'text' => 'Somewhat - neutral',
                        ],
                        [
                            'score' => 2,
                            'text' => 'Disagree',
                        ],
                        [
                            'score' => 1,
                            'text' => 'Strongly disagree',
                        ],
                    ],
                ],
                [
                    'quality' => 'C',
                    'text' => 'I tend to be disorganized and often forget to put things back in their proper place.',
                    'code' => 'disorganized',
                    'answers' => [
                        [
                            'score' => 1,
                            'text' => 'Strongly agree',
                        ],
                        [
                            'score' => 2,
                            'text' => 'Agree',
                        ],
                        [
                            'score' => 3,
                            'text' => 'Somewhat - neutral',
                        ],
                        [
                            'score' => 4,
                            'text' => 'Disagree',
                        ],
                        [
                            'score' => 5,
                            'text' => 'Strongly disagree',
                        ],
                    ],
                ],
                [
                    'quality' => 'C',
                    'text' => 'I work hard.',
                    'code' => 'work-hard',
                    'answers' => [
                        [
                            'score' => 5,
                            'text' => 'Strongly agree',
                        ],
                        [
                            'score' => 4,
                            'text' => 'Agree',
                        ],
                        [
                            'score' => 3,
                            'text' => 'Somewhat - neutral',
                        ],
                        [
                            'score' => 2,
                            'text' => 'Disagree',
                        ],
                        [
                            'score' => 1,
                            'text' => 'Strongly disagree',
                        ],
                    ],
                ],
                [
                    'quality' => 'C',
                    'text' => 'I sometimes behave irresponsibly.',
                    'code' => 'irresponsible',
                    'answers' => [
                        [
                            'score' => 1,
                            'text' => 'Strongly agree',
                        ],
                        [
                            'score' => 2,
                            'text' => 'Agree',
                        ],
                        [
                            'score' => 3,
                            'text' => 'Somewhat - neutral',
                        ],
                        [
                            'score' => 4,
                            'text' => 'Disagree',
                        ],
                        [
                            'score' => 5,
                            'text' => 'Strongly disagree',
                        ],
                    ],
                ],
                [
                    'quality' => 'C',
                    'text' => 'I have difficulty starting or finishing tasks.',
                    'code' => 'difficulty-starting-finishing',
                    'answers' => [
                        [
                            'score' => 1,
                            'text' => 'Strongly agree',
                        ],
                        [
                            'score' => 2,
                            'text' => 'Agree',
                        ],
                        [
                            'score' => 3,
                            'text' => 'Somewhat - neutral',
                        ],
                        [
                            'score' => 4,
                            'text' => 'Disagree',
                        ],
                        [
                            'score' => 5,
                            'text' => 'Strongly disagree',
                        ],
                    ],
                ],
                [
                    'quality' => 'C',
                    'text' => 'I strive for high achievement in life.',
                    'code' => 'strive-for-achievement',
                    'answers' => [
                        [
                            'score' => 5,
                            'text' => 'Strongly agree',
                        ],
                        [
                            'score' => 4,
                            'text' => 'Agree',
                        ],
                        [
                            'score' => 3,
                            'text' => 'Somewhat - neutral',
                        ],
                        [
                            'score' => 2,
                            'text' => 'Disagree',
                        ],
                        [
                            'score' => 1,
                            'text' => 'Strongly disagree',
                        ],
                    ],
                ],
                [
                    'quality' => 'E',
                    'text' => 'I am talkative.',
                    'code' => 'talkative',
                    'answers' => [
                        [
                            'score' => 5,
                            'text' => 'Strongly agree',
                        ],
                        [
                            'score' => 4,
                            'text' => 'Agree',
                        ],
                        [
                            'score' => 3,
                            'text' => 'Somewhat - neutral',
                        ],
                        [
                            'score' => 2,
                            'text' => 'Disagree',
                        ],
                        [
                            'score' => 1,
                            'text' => 'Strongly disagree',
                        ],
                    ],
                ],
                [
                    'quality' => 'E',
                    'text' => 'I prefer to have others lead and take charge.',
                    'code' => 'submissive',
                    'answers' => [
                        [
                            'score' => 1,
                            'text' => 'Strongly agree',
                        ],
                        [
                            'score' => 2,
                            'text' => 'Agree',
                        ],
                        [
                            'score' => 3,
                            'text' => 'Somewhat - neutral',
                        ],
                        [
                            'score' => 4,
                            'text' => 'Disagree',
                        ],
                        [
                            'score' => 5,
                            'text' => 'Strongly disagree',
                        ],
                    ],
                ],
                [
                    'quality' => 'E',
                    'text' => 'I am sometimes shy, and introverted.',
                    'code' => 'shy',
                    'answers' => [
                        [
                            'score' => 1,
                            'text' => 'Strongly agree',
                        ],
                        [
                            'score' => 2,
                            'text' => 'Agree',
                        ],
                        [
                            'score' => 3,
                            'text' => 'Somewhat - neutral',
                        ],
                        [
                            'score' => 4,
                            'text' => 'Disagree',
                        ],
                        [
                            'score' => 5,
                            'text' => 'Strongly disagree',
                        ],
                    ],
                ],
                [
                    'quality' => 'E',
                    'text' => 'I often stay out way later than planned if the conversation is good and drinks are flowing.',
                    'code' => 'stay-out-late',
                    'answers' => [
                        [
                            'score' => 5,
                            'text' => 'Every time',
                        ],
                        [
                            'score' => 4,
                            'text' => 'Often',
                        ],
                        [
                            'score' => 3,
                            'text' => 'Sometimes',
                        ],
                        [
                            'score' => 2,
                            'text' => 'Not too often',
                        ],
                        [
                            'score' => 1,
                            'text' => 'Hardly ever',
                        ],
                    ],
                ],
                [
                    'quality' => 'E',
                    'text' => 'I get very excited about life and am eager to make plans.',
                    'code' => 'excited-about-life',
                    'answers' => [
                        [
                            'score' => 5,
                            'text' => 'Strongly agree',
                        ],
                        [
                            'score' => 4,
                            'text' => 'Agree',
                        ],
                        [
                            'score' => 3,
                            'text' => 'Somewhat - neutral',
                        ],
                        [
                            'score' => 2,
                            'text' => 'Disagree',
                        ],
                        [
                            'score' => 1,
                            'text' => 'Strongly disagree',
                        ],
                    ],
                ],
                [
                    'quality' => 'A',
                    'text' => 'I have an assertive personality, I am not afraid to ask for what I want.',
                    'code' => 'assertive',
                    'answers' => [
                        [
                            'score' => 1,
                            'text' => 'Strongly agree',
                        ],
                        [
                            'score' => 2,
                            'text' => 'Agree',
                        ],
                        [
                            'score' => 3,
                            'text' => 'Somewhat - neutral',
                        ],
                        [
                            'score' => 4,
                            'text' => 'Disagree',
                        ],
                        [
                            'score' => 5,
                            'text' => 'Strongly disagree',
                        ],
                    ],
                ],
                [
                    'quality' => 'A',
                    'text' => 'I always assume the best about people.',
                    'code' => 'assume-the-best',
                    'answers' => [
                        [
                            'score' => 5,
                            'text' => 'Strongly agree',
                        ],
                        [
                            'score' => 4,
                            'text' => 'Agree',
                        ],
                        [
                            'score' => 3,
                            'text' => 'Somewhat - neutral',
                        ],
                        [
                            'score' => 2,
                            'text' => 'Disagree',
                        ],
                        [
                            'score' => 1,
                            'text' => 'Strongly disagree',
                        ],
                    ],
                ],
                [
                    'quality' => 'A',
                    'text' => 'I tend to find fault with others.',
                    'code' => 'fault-with-others',
                    'answers' => [
                        [
                            'score' => 1,
                            'text' => 'Strongly agree',
                        ],
                        [
                            'score' => 2,
                            'text' => 'Agree',
                        ],
                        [
                            'score' => 3,
                            'text' => 'Somewhat - neutral',
                        ],
                        [
                            'score' => 4,
                            'text' => 'Disagree',
                        ],
                        [
                            'score' => 5,
                            'text' => 'Strongly disagree',
                        ],
                    ],
                ],
                [
                    'quality' => 'A',
                    'text' => 'I am nice, I treat all people with respect.',
                    'code' => 'nice',
                    'answers' => [
                        [
                            'score' => 5,
                            'text' => 'Strongly agree',
                        ],
                        [
                            'score' => 4,
                            'text' => 'Agree',
                        ],
                        [
                            'score' => 3,
                            'text' => 'Somewhat - neutral',
                        ],
                        [
                            'score' => 2,
                            'text' => 'Disagree',
                        ],
                        [
                            'score' => 1,
                            'text' => 'Strongly disagree',
                        ],
                    ],
                ],
                [
                    'quality' => 'A',
                    'text' => 'I can be rude sometimes.',
                    'code' => 'rude',
                    'answers' => [
                        [
                            'score' => 1,
                            'text' => 'Strongly agree',
                        ],
                        [
                            'score' => 2,
                            'text' => 'Agree',
                        ],
                        [
                            'score' => 3,
                            'text' => 'Somewhat - neutral',
                        ],
                        [
                            'score' => 4,
                            'text' => 'Disagree',
                        ],
                        [
                            'score' => 5,
                            'text' => 'Strongly disagree',
                        ],
                    ],
                ],
                [
                    'quality' => 'N',
                    'text' => 'I have a soft heart, and am compassionate.',
                    'code' => 'compassionate',
                    'answers' => [
                        [
                            'score' => 5,
                            'text' => 'Strongly agree',
                        ],
                        [
                            'score' => 4,
                            'text' => 'Agree',
                        ],
                        [
                            'score' => 3,
                            'text' => 'Somewhat - neutral',
                        ],
                        [
                            'score' => 2,
                            'text' => 'Disagree',
                        ],
                        [
                            'score' => 1,
                            'text' => 'Strongly disagree',
                        ],
                    ],
                ],
                [
                    'quality' => 'N',
                    'text' => 'I tend to worry a lot.',
                    'code' => 'worry',
                    'answers' => [
                        [
                            'score' => 5,
                            'text' => 'Very often',
                        ],
                        [
                            'score' => 4,
                            'text' => 'Often',
                        ],
                        [
                            'score' => 3,
                            'text' => 'Sometimes',
                        ],
                        [
                            'score' => 2,
                            'text' => 'Not too much',
                        ],
                        [
                            'score' => 1,
                            'text' => 'Not at all',
                        ],
                    ],
                ],
                [
                    'quality' => 'N',
                    'text' => 'I can be tense.',
                    'code' => 'tense',
                    'answers' => [
                        [
                            'score' => 5,
                            'text' => 'Strongly agree',
                        ],
                        [
                            'score' => 4,
                            'text' => 'Agree',
                        ],
                        [
                            'score' => 3,
                            'text' => 'Somewhat - neutral',
                        ],
                        [
                            'score' => 2,
                            'text' => 'Disagree',
                        ],
                        [
                            'score' => 1,
                            'text' => 'Strongly disagree',
                        ],
                    ],
                ],
                [
                    'quality' => 'N',
                    'text' => 'I have an extreme personality, I do little in moderation.',
                    'code' => 'extreme',
                    'answers' => [
                        [
                            'score' => 5,
                            'text' => 'Strongly agree',
                        ],
                        [
                            'score' => 4,
                            'text' => 'Agree',
                        ],
                        [
                            'score' => 3,
                            'text' => 'Somewhat - neutral',
                        ],
                        [
                            'score' => 2,
                            'text' => 'Disagree',
                        ],
                        [
                            'score' => 1,
                            'text' => 'Strongly disagree',
                        ],
                    ],
                ],
                [
                    'quality' => 'N',
                    'text' => 'I am relaxed, I handle stress well.',
                    'code' => 'relaxed',
                    'answers' => [
                        [
                            'score' => 1,
                            'text' => 'Strongly agree',
                        ],
                        [
                            'score' => 2,
                            'text' => 'Agree',
                        ],
                        [
                            'score' => 3,
                            'text' => 'Somewhat - neutral',
                        ],
                        [
                            'score' => 4,
                            'text' => 'Disagree',
                        ],
                        [
                            'score' => 5,
                            'text' => 'Strongly disagree',
                        ],
                    ],
                ],
            ] as $i => $question
        ) {
            $q = SurveyQuestion::firstOrNew([
                'quality' => $question['quality'],
                'code'    => $question['code'],
            ]);
            $q->ordinal = ($i + 1) * 100;
            $q->text    = $question['text'];
            $q->save();

            foreach ($question['answers'] as $j => $answer) {
                $a = SurveyQuestionAnswer::firstOrNew([
                    'survey_question_id' => $q->id,
                    'score'              => $answer['score'],
                ]);
                $a->ordinal = ($j + 1) * 100;
                $a->text    = $answer['text'];
                $a->save();
            }
        }
    }
}
