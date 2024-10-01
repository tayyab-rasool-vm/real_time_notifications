<?php

use App\Events\Example;
use App\Http\Controllers\ProfileController;
use App\Jobs\TestJob;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redis;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/broadcast/{id}', function ($id) {
    // Redis::set('name', 'Taylor');
    // Redis::set('cast', 'kamboh');

    // TestJob::dispatch();
    broadcast(new Example(User::find($id)));
    echo "Notification sent to user id $id";
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
