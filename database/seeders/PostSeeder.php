<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Vocabulary;
use App\Models\Question;
use App\Models\Task;
use App\Models\TaskAnswer;
use App\Models\Debate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Categories
        $movieCat = Category::updateOrCreate(['slug' => 'movie-discussions'], ['title' => 'Movie Discussions']);
        $musicCat = Category::updateOrCreate(['slug' => 'music-talks'], ['title' => 'Music Talks']);
        $memeCat = Category::updateOrCreate(['slug' => 'meme-english'], ['title' => 'Meme English']);

        // 2. Weekly Debates
        Debate::updateOrCreate(
            ['title' => 'Will AI completely replace human English teachers by 2030?'],
            [
                'description' => "With the rise of highly sophisticated conversational LLMs like Gemini and specialized tutoring agents, some believe human tutors are becoming completely obsolete. Others argue that human empathy, authentic cultural feedback, and emotional encouragement are irreplaceable in language acquisition. What do you think?",
                'active' => true,
                'starts_at' => now()->subDays(2),
                'ends_at' => now()->addDays(5),
            ]
        );

        Debate::updateOrCreate(
            ['title' => 'Is internet slang ruining the English language?'],
            [
                'description' => "A fierce debate on whether internet memes, TikTok slangs, and abbreviations enrich the English vocabulary with creativity, or slowly degrade critical grammar, formal writing skills, and structured conversational ability among youth.",
                'active' => false,
                'starts_at' => now()->subDays(15),
                'ends_at' => now()->subDays(8),
            ]
        );

        // 3. Posts
        // --- POST 1: The Matrix ---
        $post1 = Post::updateOrCreate(
            ['slug' => 'the-matrix-choice-scene'],
            [
                'category_id' => $movieCat->id,
                'title' => 'The Matrix: The Red or Blue Pill',
                'description' => "Neo meets Morpheus for the first time and is offered a life-altering choice between the Blue Pill (illusory comfort) and the Red Pill (harsh truth). In this classic scene, analyze how characters use the vocabulary of destiny, freedom, and neural prisons.",
                'media_type' => 'youtube',
                'media_url' => 'https://www.youtube.com/embed/KjeTCL4nS5Y',
                'level' => 'B1',
            ]
        );

        Vocabulary::updateOrCreate(
            ['post_id' => $post1->id, 'word' => 'fate'],
            [
                'transcription' => '/feɪt/',
                'translation' => 'судьба',
                'explanation' => 'The development of events outside a person\'s control, regarded as determined by a supernatural power.',
                'example' => 'Morpheus: "Do you believe in fate, Neo?" Neo: "No. Because I don\'t like the idea that I\'m not in control of my life."',
            ]
        );

        Vocabulary::updateOrCreate(
            ['post_id' => $post1->id, 'word' => 'splinter'],
            [
                'transcription' => '/ˈsplɪn.tər/',
                'translation' => 'заноза / осколок',
                'explanation' => 'A small, thin, sharp piece of wood, glass, or other material, broken off from a larger piece.',
                'example' => 'Morpheus: "Like a splinter in your mind, driving you mad."',
            ]
        );

        Vocabulary::updateOrCreate(
            ['post_id' => $post1->id, 'word' => 'bondage'],
            [
                'transcription' => '/ˈbɒn.dɪdʒ/',
                'translation' => 'рабство / оковы',
                'explanation' => 'The state of being another person\'s slave, or being bound by external forces without freedom.',
                'example' => 'Morpheus: "A prison for your mind. You were born into bondage, Neo."',
            ]
        );

        Question::updateOrCreate(['post_id' => $post1->id, 'text' => 'Why does Morpheus offer Neo a choice instead of just telling him the truth directly?']);
        Question::updateOrCreate(['post_id' => $post1->id, 'text' => 'If you were in Neo\'s place, would you choose the Red Pill or the Blue Pill? Why?']);

        // Tasks for Post 1
        $t1 = Task::updateOrCreate(
            ['post_id' => $post1->id, 'type' => 'multiple_choice'],
            ['question_text' => 'What does Morpheus compare the Matrix to in this scene?']
        );
        TaskAnswer::updateOrCreate(['task_id' => $t1->id, 'answer' => 'A prison for your mind', 'is_correct' => true]);
        TaskAnswer::updateOrCreate(['task_id' => $t1->id, 'answer' => 'A software program', 'is_correct' => false]);
        TaskAnswer::updateOrCreate(['task_id' => $t1->id, 'answer' => 'A digital dream', 'is_correct' => false]);

        $t2 = Task::updateOrCreate(
            ['post_id' => $post1->id, 'type' => 'fill_gap'],
            ['question_text' => 'Morpheus says: "You were born into [bondage], Neo, a prison that you cannot smell or taste or touch."']
        );
        TaskAnswer::updateOrCreate(['task_id' => $t2->id, 'answer' => 'bondage', 'is_correct' => true]);

        $t3 = Task::updateOrCreate(
            ['post_id' => $post1->id, 'type' => 'match_words'],
            ['question_text' => 'Match these Matrix vocabulary words to their Russian translations.']
        );
        TaskAnswer::updateOrCreate(['task_id' => $t3->id, 'answer' => 'fate', 'matching_translation' => 'судьба', 'is_correct' => true]);
        TaskAnswer::updateOrCreate(['task_id' => $t3->id, 'answer' => 'splinter', 'matching_translation' => 'заноза', 'is_correct' => true]);
        TaskAnswer::updateOrCreate(['task_id' => $t3->id, 'answer' => 'bondage', 'matching_translation' => 'оковы', 'is_correct' => true]);


        // --- POST 2: Pulp Fiction Royale with Cheese ---
        $post2 = Post::updateOrCreate(
            ['slug' => 'pulp-fiction-royale-with-cheese'],
            [
                'category_id' => $movieCat->id,
                'title' => 'Pulp Fiction: The Royale with Cheese',
                'description' => "Jules and Vincent discuss the hilarious cultural quirks of Europe, like how a McDonald's Quarter Pounder is named a 'Royale with Cheese' in Paris because of the metric system. Practice casual slang and natural conversational flow.",
                'media_type' => 'youtube',
                'media_url' => 'https://www.youtube.com/embed/6Pkq_eBHXJ4',
                'level' => 'B2',
            ]
        );

        Vocabulary::updateOrCreate(
            ['post_id' => $post2->id, 'word' => 'custom'],
            [
                'transcription' => '/ˈkʌs.təm/',
                'translation' => 'обычай / традиция',
                'explanation' => 'A traditional and widely accepted way of behaving or doing something that is specific to a particular society.',
                'example' => 'Vincent: "They got different customs there, it\'s the little differences that make them special."',
            ]
        );

        Vocabulary::updateOrCreate(
            ['post_id' => $post2->id, 'word' => 'quarter'],
            [
                'transcription' => '/ˈkwɔː.tər/',
                'translation' => 'четверть',
                'explanation' => 'One of four equal parts into which something is or can be divided.',
                'example' => 'Jules: "A Quarter Pounder with cheese. Because of the metric system they wouldn\'t know what that is."',
            ]
        );

        Question::updateOrCreate(['post_id' => $post2->id, 'text' => 'Why do small cultural quirks (like food names or metric units) shock Vincent more than major political differences?']);

        $t2_1 = Task::updateOrCreate(
            ['post_id' => $post2->id, 'type' => 'multiple_choice'],
            ['question_text' => 'What is a Quarter Pounder called in Paris, France?']
        );
        TaskAnswer::updateOrCreate(['task_id' => $t2_1->id, 'answer' => 'Royale with Cheese', 'is_correct' => true]);
        TaskAnswer::updateOrCreate(['task_id' => $t2_1->id, 'answer' => 'Le Double Cheese', 'is_correct' => false]);
        TaskAnswer::updateOrCreate(['task_id' => $t2_1->id, 'answer' => 'Metric Big Mac', 'is_correct' => false]);

        $t2_2 = Task::updateOrCreate(
            ['post_id' => $post2->id, 'type' => 'fill_gap'],
            ['question_text' => 'Vincent talks about cultural differences: "They got different [customs] there, man."']
        );
        TaskAnswer::updateOrCreate(['task_id' => $t2_2->id, 'answer' => 'customs', 'is_correct' => true]);


        // --- POST 3: Coldplay - Fix You ---
        $post3 = Post::updateOrCreate(
            ['slug' => 'coldplay-fix-you'],
            [
                'category_id' => $musicCat->id,
                'title' => 'Coldplay: Empathy and Hope in "Fix You"',
                'description' => "Break down the lyrical structure and emotional vocabulary of Coldplay's iconic ballad 'Fix You'. Explore how we express comfort, regret, and validation of sadness in contemporary English.",
                'media_type' => 'youtube',
                'media_url' => 'https://www.youtube.com/embed/k4V3Mo61fJM',
                'level' => 'A2',
            ]
        );

        Vocabulary::updateOrCreate(
            ['post_id' => $post3->id, 'word' => 'ignite'],
            [
                'transcription' => '/ɪɡˈnaɪt/',
                'translation' => 'зажечь / воспламенить',
                'explanation' => 'To catch fire or cause to catch fire; metaphorically, to spark energy or hope.',
                'example' => 'Lyrics: "Lights will guide you home, and ignite your bones, and I will try to fix you."',
            ]
        );

        Vocabulary::updateOrCreate(
            ['post_id' => $post3->id, 'word' => 'succeed'],
            [
                'transcription' => '/səkˈsiːd/',
                'translation' => 'преуспеть',
                'explanation' => 'To achieve the desired aim or result.',
                'example' => 'Lyrics: "When you try your best, but you don\'t succeed..."',
            ]
        );

        Question::updateOrCreate(['post_id' => $post3->id, 'text' => 'What does it mean to "fix someone" emotionally? Is it actually possible, or is the metaphor flawed?']);

        $t3_1 = Task::updateOrCreate(
            ['post_id' => $post3->id, 'type' => 'multiple_choice'],
            ['question_text' => 'Complete the lyrics: "When you try your best, but you don\'t..."']
        );
        TaskAnswer::updateOrCreate(['task_id' => $t3_1->id, 'answer' => 'succeed', 'is_correct' => true]);
        TaskAnswer::updateOrCreate(['task_id' => $t3_1->id, 'answer' => 'succeeds', 'is_correct' => false]);
        TaskAnswer::updateOrCreate(['task_id' => $t3_1->id, 'answer' => 'success', 'is_correct' => false]);

        $t3_2 = Task::updateOrCreate(
            ['post_id' => $post3->id, 'type' => 'fill_gap'],
            ['question_text' => 'Complete the lyrics: "Lights will guide you home, and [ignite] your bones..."']
        );
        TaskAnswer::updateOrCreate(['task_id' => $t3_2->id, 'answer' => 'ignite', 'is_correct' => true]);


        // --- POST 4: Meme English - Stonks ---
        $post4 = Post::updateOrCreate(
            ['slug' => 'meme-english-stonks'],
            [
                'category_id' => $memeCat->id,
                'title' => 'The Meme English: Anatomy of "Stonks"',
                'description' => "Investigate how a deliberate misspelling of 'stocks' paired with a blank-faced 3D head became a worldwide internet symbol for sarcastic business decisions and financial failures. Learn internet slang and vocabulary.",
                'media_type' => 'image',
                'media_url' => 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3',
                'level' => 'A2',
            ]
        );

        Vocabulary::updateOrCreate(
            ['post_id' => $post4->id, 'word' => 'stocks'],
            [
                'transcription' => '/stɒks/',
                'translation' => 'акции',
                'explanation' => 'Shares in the ownership of a company, representing a claim on part of the corporation\'s assets and earnings.',
                'example' => 'Regular: "He invested $500 in tech stocks." Meme: "I bought 10 boxes of matches. Stonks!"',
            ]
        );

        Vocabulary::updateOrCreate(
            ['post_id' => $post4->id, 'word' => 'deliberate'],
            [
                'transcription' => '/dɪˈlɪb.ər.ət/',
                'translation' => 'намеренный / умышленный',
                'explanation' => 'Done consciously and intentionally.',
                'example' => 'The misspelling in "Stonks" is a deliberate grammatical error to mock naive investors.',
            ]
        );

        Question::updateOrCreate(['post_id' => $post4->id, 'text' => 'Why do you think deliberate misspellings (like stonks, doge, smol) are so popular in internet humor?']);

        $t4_1 = Task::updateOrCreate(
            ['post_id' => $post4->id, 'type' => 'multiple_choice'],
            ['question_text' => 'What is "Stonks" a deliberate misspelling of?']
        );
        TaskAnswer::updateOrCreate(['task_id' => $t4_1->id, 'answer' => 'stocks', 'is_correct' => true]);
        TaskAnswer::updateOrCreate(['task_id' => $t4_1->id, 'answer' => 'stone', 'is_correct' => false]);
        TaskAnswer::updateOrCreate(['task_id' => $t4_1->id, 'answer' => 'stinks', 'is_correct' => false]);

        $t4_2 = Task::updateOrCreate(
            ['post_id' => $post4->id, 'type' => 'fill_gap'],
            ['question_text' => 'The meme uses a [deliberate] misspelling to indicate a sarcastic investment.']
        );
        TaskAnswer::updateOrCreate(['task_id' => $t4_2->id, 'answer' => 'deliberate', 'is_correct' => true]);

        // --- POST 5: Sintel - A Quest for Friendship ---
        $post5 = Post::updateOrCreate(
            ['slug' => 'sintel-quest-for-friendship'],
            [
                'category_id' => $movieCat->id,
                'title' => 'Sintel: A Quest for Friendship',
                'description' => "Follow Sintel as she rescues a baby dragon and starts an epic, emotional journey to find him when he is taken. In this segment, we will study the vocabulary of quests, hope, and attachment.",
                'media_type' => 'video',
                'media_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/Sintel.mp4',
                'level' => 'B1',
            ]
        );

        Vocabulary::updateOrCreate(
            ['post_id' => $post5->id, 'word' => 'quest'],
            [
                'transcription' => '/kwest/',
                'translation' => 'поиски / квест',
                'explanation' => 'A long or arduous search for something.',
                'example' => 'Sintel sets out on a perilous quest to find her lost companion, Scales.',
            ]
        );

        Vocabulary::updateOrCreate(
            ['post_id' => $post5->id, 'word' => 'attachment'],
            [
                'transcription' => '/əˈtætʃ.mənt/',
                'translation' => 'привязанность',
                'explanation' => 'An extra feeling of affection or fondness for a person, animal, or object.',
                'example' => 'Sintel developed a deep emotional attachment to the baby dragon.',
            ]
        );

        Question::updateOrCreate(['post_id' => $post5->id, 'text' => 'Why do you think Sintel was willing to risk her life to find Scales?']);
        Question::updateOrCreate(['post_id' => $post5->id, 'text' => 'How does the theme of attachment affect our decisions in real life?']);

        $t5_1 = Task::updateOrCreate(
            ['post_id' => $post5->id, 'type' => 'multiple_choice'],
            ['question_text' => 'What is the name of Sintel\'s companion dragon?']
        );
        TaskAnswer::updateOrCreate(['task_id' => $t5_1->id, 'answer' => 'Scales', 'is_correct' => true]);
        TaskAnswer::updateOrCreate(['task_id' => $t5_1->id, 'answer' => 'Sintel', 'is_correct' => false]);
        TaskAnswer::updateOrCreate(['task_id' => $t5_1->id, 'answer' => 'Morpheus', 'is_correct' => false]);

        $t5_2 = Task::updateOrCreate(
            ['post_id' => $post5->id, 'type' => 'fill_gap'],
            ['question_text' => 'A long and difficult search for something is called a [quest].']
        );
        TaskAnswer::updateOrCreate(['task_id' => $t5_2->id, 'answer' => 'quest', 'is_correct' => true]);

        $t5_3 = Task::updateOrCreate(
            ['post_id' => $post5->id, 'type' => 'match_words'],
            ['question_text' => 'Match these vocabulary words to their Russian translations.']
        );
        TaskAnswer::updateOrCreate(['task_id' => $t5_3->id, 'answer' => 'quest', 'matching_translation' => 'поиски', 'is_correct' => true]);
        TaskAnswer::updateOrCreate(['task_id' => $t5_3->id, 'answer' => 'attachment', 'matching_translation' => 'привязанность', 'is_correct' => true]);

        // --- POST 6: Tears of Steel - Sci-Fi Dialogue ---
        $post6 = Post::updateOrCreate(
            ['slug' => 'tears-of-steel-sci-fi-confrontation'],
            [
                'category_id' => $movieCat->id,
                'title' => 'Tears of Steel: A Sci-Fi Confrontation',
                'description' => "Explore the advanced sci-fi vocabulary and intense conversational dynamics in the Blender Foundation's short film 'Tears of Steel'. We will analyze the grammar of regret, technology, and futuristic military operations.",
                'media_type' => 'video',
                'media_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/TearsOfSteel.mp4',
                'level' => 'B2',
            ]
        );

        Vocabulary::updateOrCreate(
            ['post_id' => $post6->id, 'word' => 'salvage'],
            [
                'transcription' => '/ˈsæl.vɪdʒ/',
                'translation' => 'спасти / уберечь',
                'explanation' => 'To rescue or save from loss, destruction, or ruin.',
                'example' => 'Celia: "We\'re here to salvage the situation, not to destroy it."',
            ]
        );

        Vocabulary::updateOrCreate(
            ['post_id' => $post6->id, 'word' => 'confrontation'],
            [
                'transcription' => '/ˌkɒn.frʌn.ˈteɪ.ʃən/',
                'translation' => 'противостояние / конфронтация',
                'explanation' => 'A hostile or argumentative meeting or situation between opposing parties.',
                'example' => 'The tense confrontation between the human soldiers and the giant robot escalates quickly.',
            ]
        );

        Question::updateOrCreate(['post_id' => $post6->id, 'text' => 'How does the setting of a futuristic laboratory influence the tone of the conversation?']);
        Question::updateOrCreate(['post_id' => $post6->id, 'text' => 'What do you think is the main conflict between the two main characters in this scene?']);

        $t6_1 = Task::updateOrCreate(
            ['post_id' => $post6->id, 'type' => 'multiple_choice'],
            ['question_text' => 'What does the word "salvage" mean in a conversation?']
        );
        TaskAnswer::updateOrCreate(['task_id' => $t6_1->id, 'answer' => 'To rescue something from ruin', 'is_correct' => true]);
        TaskAnswer::updateOrCreate(['task_id' => $t6_1->id, 'answer' => 'To destroy completely', 'is_correct' => false]);
        TaskAnswer::updateOrCreate(['task_id' => $t6_1->id, 'answer' => 'To ignore a problem', 'is_correct' => false]);

        $t6_2 = Task::updateOrCreate(
            ['post_id' => $post6->id, 'type' => 'fill_gap'],
            ['question_text' => 'We need to [salvage] what is left of our relationship before it\'s too late.']
        );
        TaskAnswer::updateOrCreate(['task_id' => $t6_2->id, 'answer' => 'salvage', 'is_correct' => true]);

        $t6_3 = Task::updateOrCreate(
            ['post_id' => $post6->id, 'type' => 'match_words'],
            ['question_text' => 'Match these sci-fi vocabulary words to their Russian translations.']
        );
        TaskAnswer::updateOrCreate(['task_id' => $t6_3->id, 'answer' => 'salvage', 'matching_translation' => 'спасти', 'is_correct' => true]);
        TaskAnswer::updateOrCreate(['task_id' => $t6_3->id, 'answer' => 'confrontation', 'matching_translation' => 'противостояние', 'is_correct' => true]);
    }
}
