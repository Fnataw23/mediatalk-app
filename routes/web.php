<?php

use App\Http\Controllers\ProfileController;
use App\Models\Post;
use App\Models\Debate;
use Illuminate\Support\Facades\Route;

// 1. Welcome / Home landing page
Route::get('/', function () {
    $latestPosts = Post::with('category')->withCount(['comments', 'reactions'])->latest()->take(3)->get();
    $popularPosts = Post::with('category')->withCount(['comments', 'reactions'])->orderBy('reactions_count', 'desc')->take(3)->get();
    $activeDebate = Debate::where('active', true)->first() ?? Debate::latest()->first();

    return view('welcome', [
        'latestPosts' => $latestPosts,
        'popularPosts' => $popularPosts,
        'activeDebate' => $activeDebate,
    ]);
})->name('home');

// 2. Lessons Feed list page
Route::get('/feed', function () {
    return view('posts.feed');
})->name('posts.feed');

// 3. Lesson Single Content details page
Route::get('/posts/{slug}', function ($slug) {
    $post = Post::where('slug', $slug)
        ->with(['category', 'vocabularies', 'questions', 'tasks.answers'])
        ->firstOrFail();

    return view('posts.show', [
        'post' => $post,
    ]);
})->name('posts.show');

// 4. Weekly Debate page
Route::get('/debate', function () {
    return view('posts.debate');
})->name('posts.debate');

// 5. Dashboard (Command Center)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 6. Profile & Avatar upload
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
});

Route::get('/deploy-migrate-seed', function () {
    try {
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        $tables = \Illuminate\Support\Facades\DB::select('SHOW TABLES');
        foreach ($tables as $table) {
            $tableArray = (array) $table;
            $tableName = reset($tableArray);
            \Illuminate\Support\Facades\DB::statement("DROP TABLE IF EXISTS `{$tableName}`");
        }
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        \Illuminate\Support\Facades\Artisan::call('migrate', [
            '--force' => true,
            '--seed' => true
        ]);
        return "Database migrated and seeded successfully!";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

require __DIR__.'/auth.php';
