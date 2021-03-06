<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\LookupController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


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