<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::redirect('/', '/login');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [RouteController::class, 'dashboard'])->name('dashboard');


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Task routes
    Route::post('/tasks/store', [TaskController::class, 'store'])->name('tasks.store');

    Route::get('/tasks/user/logged/{status}', [TaskController::class, 'listTaskUserLoggedStatus'])->name('tasks.user.logged.status');
    Route::get('/tasks/all/{status}', [TaskController::class, 'listTaskAllStatus'])->name('tasks.all.status');
    Route::get('/tasks/{hash}/users', [TaskController::class, 'listTaskUserHash'])->name('tasks.user.logged');

    Route::post('/tasks//users/update', [TaskController::class, 'updateTaskUserHash'])->name('tasks.user.update');
    Route::post('/tasks/update/status', [TaskController::class, 'updateTaskStatusCompleted'])->name('tasks.update.status');
    Route::post('/tasks/update', [TaskController::class, 'update'])->name('tasks.update');
    Route::post('/tasks/delete', [TaskController::class, 'destroy'])->name('tasks.delete');
    Route::post('/tasks/filter', [TaskController::class, 'filterTasks'])->name('tasks.filter');

    //User routes
    Route::get('/users', [UserController::class, 'listAllUser'])->name('users.list');

});


require __DIR__.'/auth.php';
