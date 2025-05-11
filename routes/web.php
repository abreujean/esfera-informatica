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

    Route::get('/users', [UserController::class, 'listAllUser'])->name('users.list');

});

//nome de middleware para VerificaSeUsuarioAdministrador
Route::middleware(['auth', 'verified', 'isAdmin'])->group(function () {

    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/index', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{hash}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::post('/users/update', [UserController::class, 'update'])->name('users.update');
    Route::post('/users/delete', [UserController::class, 'destroy'])->name('users.delete');
});


require __DIR__.'/auth.php';
