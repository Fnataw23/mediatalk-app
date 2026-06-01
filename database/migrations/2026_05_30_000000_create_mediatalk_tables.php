<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // 2. Posts (Content Items)
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('media_type'); // youtube, vimeo, image
            $table->string('media_url')->nullable();
            $table->string('level'); // A2, B1, B2
            $table->timestamps();
        });

        // 3. Vocabularies
        Schema::create('vocabularies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->string('word');
            $table->string('transcription')->nullable();
            $table->string('translation');
            $table->text('explanation')->nullable();
            $table->text('example')->nullable();
            $table->timestamps();
        });

        // 4. Discussion Questions
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->text('text');
            $table->timestamps();
        });

        // 5. Interactive Tasks
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // multiple_choice, fill_gap, match_words
            $table->text('question_text');
            $table->timestamps();
        });

        // 6. Task Answers
        Schema::create('task_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->cascadeOnDelete();
            $table->string('answer'); // For match_words, this is the English term
            $table->boolean('is_correct')->default(false);
            $table->string('matching_translation')->nullable(); // For match_words, this is the Russian translation
            $table->timestamps();
        });

        // 7. Comments
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('comments')->cascadeOnDelete();
            $table->text('content');
            $table->timestamps();
        });

        // 8. Comment Likes
        Schema::create('comment_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('comment_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'comment_id']);
        });

        // 9. Post Reactions
        Schema::create('reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->string('reaction_type'); // useful, funny, interesting
            $table->timestamps();
            $table->unique(['user_id', 'post_id', 'reaction_type']);
        });

        // 10. Achievements
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('code')->unique(); // e.g. first_comment, movie_fan
            $table->timestamps();
        });

        // 11. User Achievements (Pivot)
        Schema::create('user_achievements', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('achievement_id')->constrained()->cascadeOnDelete();
            $table->timestamp('created_at')->useCurrent();
            $table->primary(['user_id', 'achievement_id']);
        });

        // 12. Weekly Debates
        Schema::create('debates', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->boolean('active')->default(false);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
        });

        // 13. Debate Votes
        Schema::create('debate_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('debate_id')->constrained()->cascadeOnDelete();
            $table->string('vote'); // yes, no
            $table->timestamps();
            $table->unique(['user_id', 'debate_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('debate_votes');
        Schema::dropIfExists('debates');
        Schema::dropIfExists('user_achievements');
        Schema::dropIfExists('achievements');
        Schema::dropIfExists('reactions');
        Schema::dropIfExists('comment_likes');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('task_answers');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('vocabularies');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('categories');
    }
};
