<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisualQuestionAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visual_question_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visual_question_id')->constrained('visual_questions')->onDelete('cascade');

            $table->integer('ordinal')->nullable();
            $table->unique(['visual_question_id', 'ordinal']);

            $table->string('code');
            $table->unique(['visual_question_id', 'code']);

            $table->string('text')->nullable();
            $table->unique(['visual_question_id', 'text']);

            $table->string('credit_url')->nullable();
            $table->string('credit_label')->nullable();
            $table->string('link_text', 1024)->nullable();
            $table->string('link_url', 1024)->nullable();
            $table->boolean('has_image')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visual_question_answers');
    }
}
