<?php

use App\Http\Controllers\LookupController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [LookupController::class, 'index']);
Route::post('/', [LookupController::class, 'lookup']);
Route::get('/order-form/{lookup_id}/{photo_id}', [LookupController::class, 'orderForm']);

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::prefix('admin')->middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/projects', [ProjectController::class, 'list'])->name('Projects');
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('CreateProject');
    Route::post('/projects/create', [ProjectController::class, 'createProject']);
    Route::post('/projects/duplicate', [ProjectController::class, 'duplicateProject']);
    Route::post('/projects/delete', [ProjectController::class, 'deleteProject']);
    Route::get('/projects/{id}', [ProjectController::class, 'editProject']);
    Route::post('/projects/{id}', [ProjectController::class, 'saveProject']);
});
