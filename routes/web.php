<?php

use App\Http\Controllers\FormController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RespondentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return redirect()->route('projects.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Project Routes
    Route::resource('projects', ProjectController::class);
    Route::get('/projects/{project}/dashboard', [ProjectController::class, 'show'])->name('projects.show');

    // Form Master Routes
    Route::resource('forms', FormController::class)->except(['index', 'show']);

    // Respondent Routes
    Route::delete('/respondents/bulk-destroy', [RespondentController::class, 'bulkDestroy'])->name('respondents.bulk-destroy');
    Route::resource('respondents', RespondentController::class)->except(['index']);
    Route::get('/respondents/{respondent}/album', [RespondentController::class, 'album'])->name('respondents.album');
});

require __DIR__.'/auth.php';
