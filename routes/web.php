<?php

use App\Models\labours\labourModel;
use App\Models\files\labourFileModel;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\labours\labourController;
use App\Http\Controllers\combine\PdfMergeController;
use App\Http\Controllers\formExport\labourFormExportController;
use App\Http\Controllers\jobgroup\jobGoupController;
use App\Http\Controllers\labours\labourFileController;

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
Route::get('labour/CombinePDF/{labourModel}',[labourController::class,'CombinePDF'])->name('labour.CombinePDF');





Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// job Group 
Route::get('jobgroup/ajax/position',[jobGoupController::class,'ajaxSelectPosition'])->name('jobgroup.ajaxSelectPosition');

Route::post('/merge-pdfs', [PdfMergeController::class, 'mergePdfs'])->name('merge-pdfs');

// labour file
Route::get('labour/delete/file/',[labourFileController::class,'delete'])->name('labourfile.delete');

//export Labour
Route::get('export/form/labour',[labourFormExportController::class,'index'])->name('export.form.labour');

//export
Route::get('/export/export-labour', [labourFormExportController::class, 'export'])->name('labour.export');


