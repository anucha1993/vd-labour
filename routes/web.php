<?php

use App\Exports\LabourAlertsExport;
use App\Models\labours\labourModel;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\files\labourFileModel;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\labours\labourController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\combine\PdfMergeController;
use App\Http\Controllers\jobgroup\jobGoupController;
use App\Http\Controllers\customers\customerController;
use App\Http\Controllers\labours\labourFileController;
use App\Http\Controllers\dashboards\dashboardController;
use App\Http\Controllers\categorys\ExaminationRounController;
use App\Http\Controllers\formExport\labourFormExportController;

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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/',[labourController::class,'index'])->name('labour.index');
Route::get('labour/create',[labourController::class,'create'])->name('labour.create');
Route::get('labours',[labourController::class,'index'])->name('labour.index');
Route::post('labour/store',[labourController::class,'store'])->name('labour.store');
Route::get('labour/edit/{labourModel}',[labourController::class,'edit'])->name('labour.edit');
Route::put('labour/update/{labourModel}',[labourController::class,'update'])->name('labour.update');
Route::get('labour/createFolder',[labourController::class,'createFolder'])->name('labour.createFolder');
Route::get('labour/CombinePDF/{labourModel}',[labourController::class,'CombinePDF'])->name('labour.CombinePDF');
Route::get('labour/view/doc/{labourModel}',[labourController::class,'viewDocs'])->name('labour.viewDocs');





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
Route::post('/export/export-labour', [labourFormExportController::class, 'export'])->name('labour.export');

//รอบสอบ 
Route::get('category/examination-roun',[ExaminationRounController::class, 'index'])->name('category.examination');
Route::post('category/examination-roun/store',[ExaminationRounController::class, 'store'])->name('category.examination.store');
Route::get('category/examination-roun/cancel/{examinationRoundModel}',[ExaminationRounController::class, 'index'])->name('category.examination.cancel');

//customer
Route::get('customers',[customerController::class,'index'])->name('customer.index');
Route::post('customers/store',[customerController::class,'store'])->name('customer.store');
Route::get('customer/edit/{customerModel}',[customerController::class,'edit'])->name('customer.edit');
Route::PUT('customer/update/{customerModel}',[customerController::class,'update'])->name('customer.update');

// Route::middleware(['auth', 'role:admin'])->group(function () {
//     Route::get('/permissions', [RolePermissionController::class, 'index'])->name('permissions.index');
//     Route::post('/permissions/update', [RolePermissionController::class, 'update'])->name('permissions.update');
// });


Route::middleware(['auth'])->group(function () {
    Route::get('/roles', [RolePermissionController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RolePermissionController::class, 'create'])->name('roles.create'); // เพิ่ม Route นี้
    Route::post('/roles', [RolePermissionController::class, 'store'])->name('roles.store');
    Route::get('/roles/{role}/edit', [RolePermissionController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{role}', [RolePermissionController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{role}', [RolePermissionController::class, 'destroy'])->name('roles.destroy');
});
    
    // Routes สำหรับจัดการ Users และการเพิ่ม Users เข้า Roles
    Route::get('/users', [UserRoleController::class, 'index'])->name('users.index');
    Route::post('/users/assign-role', [UserRoleController::class, 'assignRole'])->name('users.assignRole');


    Route::resource('dashboards', dashboardController::class);

    Route::get('/export/labour-alerts', function () {
        return Excel::download(new LabourAlertsExport, 'แจ้งเตือนเอกสารแรงงาน.xlsx');
    })->name('labours.export.alerts');

    Route::get('/labours/alert/{type}', [App\Http\Controllers\LabourAlertController::class, 'show'])
    ->name('labours.alert.list');

    Route::post('/download-zip', [\App\Http\Controllers\Combine\PdfMergeController::class, 'downloadZip'])
    ->name('download.zip');