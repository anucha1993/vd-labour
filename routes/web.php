<?php

use App\Http\Controllers\jobgroup\jobGoupController;
use App\Http\Controllers\labours\labourController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('labour/create',[labourController::class,'create'])->name('labour.create');
Route::get('labours',[labourController::class,'index'])->name('labour.index');
Route::post('labour/store',[labourController::class,'store'])->name('labour.store');
Route::get('labour/edit/{labourModel}',[labourController::class,'edit'])->name('labour.edit');
Route::put('labour/update/{labourModel}',[labourController::class,'update'])->name('labour.update');
Route::get('labour/createFolder',[labourController::class,'createFolder'])->name('labour.createFolder');





Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// job Group 
Route::get('jobgroup/ajax/position',[jobGoupController::class,'ajaxSelectPosition'])->name('jobgroup.ajaxSelectPosition');