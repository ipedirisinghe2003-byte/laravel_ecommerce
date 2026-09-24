<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\Authadmin;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;

//Route::get('/', function () {

   // return view('welcome');
//});
route::get('/', [HomeController::class, 'index'])->name('home.index');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    route::get('/user/dashboard', [UserController::class, 'index'])->name('user.dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

route::middleware([Authadmin::class])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/brands', [AdminController::class, 'brands'])->name('admin.brands');
    Route::get('/admin/categories', [CategoryController::class, 'index'])->name('admin.categories');
    Route::get('/admin/brand-add', [AdminController::class, 'brandAdd'])->name('admin.brand-add');
    Route::get('/admin/brand-add.php', [AdminController::class, 'brandAdd'])->name('admin.brand-add.php');
    Route::post('/admin/brand-store', [AdminController::class, 'brandStore'])->name('admin.brand-store');
    Route::post('/admin/brand-store.php', [AdminController::class, 'brandStore'])->name('admin.brand-store.php');
    Route::get('/admin/brands-edit/{brand}', [AdminController::class, 'brandEdit'])->name('admin.brand-edit');
    Route::patch('/admin/brands-update/{brand}', [AdminController::class, 'brandUpdate'])->name('admin.brand-update');
    Route::delete('/admin/brands-delete/{brand}', [AdminController::class, 'brandDestroy'])->name('admin.brand-destroy');


});

require __DIR__.'/auth.php';


