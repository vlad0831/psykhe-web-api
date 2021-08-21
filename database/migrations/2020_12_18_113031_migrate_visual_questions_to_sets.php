<?php

use App\Models\PersonalityTest\VisualQuestion;
use App\Models\PersonalityTest\VisualQuestionAnswer;
use App\Models\PersonalityTest\VisualQuestionSet;
use App\Models\User\PersonalityTest\VisualResponse;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MigrateVisualQuestionsToSets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('visual_questions', function (Blueprint $table) {
            $table->string('link_url')->nullable();
            $table->string('link_text')->nullable();
            $table->boolean('has_image')->default(false);
            $table->unsignedBigInteger('visual_question_set_id')->nullable();

            $table->foreign('visual_question_set_id')
                ->references('id')
                ->on('visual_question_sets')
                ->onDelete('cascade')
            ;
        });

        // Remove foreign key constraints from visual responses while we transition to the new format
        Schema::table('user_visual_responses', function (Blueprint $table) {
            $table->dropForeign('user_visual_responses_visual_question_id_foreign');
            $table->dropForeign('user_visual_responses_visual_question_answer_id_foreign');

            $table->unsignedBigInteger('visual_question_answer_id')->nullable()->change();
        });

        // For every current question:
        //   - Creates a new question set with the same text
        //   - Creates a new question for each answer, and associates it with the set
        //   - Deletes the original question
        VisualQuestion::all()->each(function (VisualQuestion $question) {
            $set = VisualQuestionSet::forceCreate([
                'id'      => $question->id,
                'mutable' => false,
                'code'    => $question->code,
                'text'    => $question->text,
                'maximum' => $question->maximum,
            ]);

            // Create questions for all answers to this question
            VisualQuestionAnswer::query()
                ->where('visual_question_id', $question->id)
                ->each(function (VisualQuestionAnswer $answer) use ($set) {
                    $question = VisualQuestion::create([
                        'visual_question_set_id' => $set->id,
                        'code'                   => $answer->code,
                        'text'                   => $answer->text,
                        'link_url'               => $answer->link_url,
                        'link_text'              => $answer->link_text,
                        'has_image'              => $answer->has_image,
                    ]);

                    // Move the answer id to question id in responses
                    VisualResponse::query()
                        ->where('visual_question_answer_id', $answer->id)
                        ->update([
                            'visual_question_id'        => $question->id,
                            'visual_question_answer_id' => null,
                        ])
                    ;

                    // Delete the original answer
                    VisualQuestionAnswer::query()
                        ->where('id', $answer->id)
                        ->delete()
                    ;
                })
            ;

            // Delete the original question
            $question->delete();
        });

        // Clear the maximum for all questions, since this value is used for multiple choice questions now
        VisualQuestion::query()->update(['maximum' => null]);

        // Remove unused fields from visual_question_answers and create new set relationship
        Schema::table('visual_question_answers', function (Blueprint $table) {
            $table->dropForeign('visual_question_answers_visual_question_id_foreign');

            $table->dropColumn('visual_question_id');
            $table->dropColumn('ordinal');
            $table->dropColumn('credit_url');
            $table->dropColumn('credit_label');
            $table->dropColumn('has_image');
            $table->dropColumn('link_url');
            $table->dropColumn('link_text');

            $table->unsignedBigInteger('visual_question_set_id')->nullable();

            $table->foreign('visual_question_set_id')
                ->references('id')
                ->on('visual_question_sets')
                ->onDelete('cascade')
            ;
        });

        // Add foreign key constraints for the migrated format
        Schema::table('user_visual_responses', function (Blueprint $table) {
            $table->foreign('visual_question_id')
                ->references('id')
                ->on('visual_questions')
                ->onDelete('cascade')
            ;

            $table->foreign('visual_question_answer_id')
                ->references('id')
                ->on('visual_question_answers')
                ->onDelete('cascade')
            ;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Past the point of no return
    }
}
