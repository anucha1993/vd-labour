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
use App\Http\Controllers\labours\labourPrintController;
use App\Http\Controllers\dashboards\dashboardController;
use App\Http\Controllers\categorys\ExaminationRounController;
use App\Http\Controllers\formExport\labourFormExportController;
use App\Http\Controllers\files\FileManageController;
use App\Http\Controllers\PermissionController;

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
    // Roles Management
    Route::get('/roles', [RolePermissionController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RolePermissionController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RolePermissionController::class, 'store'])->name('roles.store');
    Route::get('/roles/{role}/edit', [RolePermissionController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{role}', [RolePermissionController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{role}', [RolePermissionController::class, 'destroy'])->name('roles.destroy');

    // Permissions Management
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
    Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
    Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');
});
    
    // Routes สำหรับจัดการ Users
    Route::resource('users', \App\Http\Controllers\UserController::class);
    Route::post('/users/assign-role', [\App\Http\Controllers\UserController::class, 'assignRole'])->name('users.assignRole');
    Route::put('/users/{user}/reset-password', [\App\Http\Controllers\UserController::class, 'resetPassword'])->name('users.resetPassword');
    Route::put('/users/{user}/update-status', [\App\Http\Controllers\UserController::class, 'updateStatus'])->name('users.updateStatus');


    Route::resource('dashboards', dashboardController::class);

    Route::get('/export/labour-alerts', function () {
        return Excel::download(new LabourAlertsExport, 'แจ้งเตือนเอกสารแรงงาน.xlsx');
    })->name('labours.export.alerts');

    Route::get('/labours/alert/{type}', [App\Http\Controllers\LabourAlertController::class, 'show'])
    ->name('labours.alert.list');

    Route::post('/download-zip', [\App\Http\Controllers\Combine\PdfMergeController::class, 'downloadZip'])
    ->name('download.zip');

    Route::get('labour/{labour}/cidfile-delete', [LabourController::class, 'deleteCidFile'])->name('labour.cidfile.delete');
    Route::get('labour/{labour}/visafile-delete', [LabourController::class, 'deleteVisaFile'])->name('labour.visafile.delete');
    Route::get('/labour/{id}/print', [labourPrintController::class, 'print'])->name('labour.print');

    // File Management Routes
    Route::resource('file-manage', FileManageController::class);
    Route::post('/file-manage/{id}/list-file', [FileManageController::class, 'addListFile'])->name('file-manage.add-list-file');
    Route::put('/file-manage/{id}/list-file/{listFileId}', [FileManageController::class, 'updateListFile'])->name('file-manage.update-list-file');
    Route::delete('/file-manage/{id}/list-file/{listFileId}', [FileManageController::class, 'destroyListFile'])->name('file-manage.destroy-list-file');

    // Position Management Routes
    Route::resource('positions', \App\Http\Controllers\positions\PositionController::class);

    // Job Group Management Routes
    Route::resource('jobgroups', \App\Http\Controllers\jobgroup\JobGroupController::class);

    // Lead Management Routes
    Route::resource('leads', \App\Http\Controllers\leads\LeadController::class);
    Route::get('/leads/{lead}/convert', [\App\Http\Controllers\leads\LeadController::class, 'convertForm'])->name('leads.convertForm');
    Route::post('/leads/{lead}/convert', [\App\Http\Controllers\leads\LeadController::class, 'convert'])->name('leads.convert');

    // Demand Management Routes
    Route::resource('demands', \App\Http\Controllers\demands\DemandController::class);
    Route::get('/demands/{demand}/pdf', [\App\Http\Controllers\demands\DemandController::class, 'generatePdf'])->name('demands.pdf');
    Route::get('/demands/{demand}/print', [\App\Http\Controllers\demands\DemandController::class, 'print'])->name('demands.print');

    // Staff Sub Management Routes
    Route::resource('staff-sub', \App\Http\Controllers\staff\StaffSubController::class);

    // Staff Management Routes
    Route::resource('staff', \App\Http\Controllers\staff\StaffController::class);

    // Job Application Additional Routes (must come BEFORE resource routes)
    Route::prefix('jobs')->name('jobs.')->group(function () {
        Route::patch('{job}/toggle-status', [\App\Http\Controllers\JobController::class, 'toggleStatus'])->name('toggle-status');
        Route::get('statistics', [\App\Http\Controllers\JobController::class, 'statistics'])->name('statistics');
        Route::get('dashboard', [\App\Http\Controllers\JobDashboardController::class, 'index'])->name('dashboard');
        Route::get('dashboard/data', [\App\Http\Controllers\JobDashboardController::class, 'getData'])->name('dashboard.data');
        Route::post('dashboard/export', [\App\Http\Controllers\JobDashboardController::class, 'export'])->name('dashboard.export');
    });
    
    Route::prefix('job-leads')->name('job-leads.')->group(function () {
        Route::get('{job}/applicants', [\App\Http\Controllers\JobLeadController::class, 'jobApplicants'])->name('job-applicants');
        Route::get('search-available', [\App\Http\Controllers\JobLeadController::class, 'searchAvailableLeads'])->name('search-available');
        Route::delete('{jobLead}/cancel', [\App\Http\Controllers\JobLeadController::class, 'cancel'])->name('cancel');
        Route::patch('{jobLead}/force-unlock', [\App\Http\Controllers\JobLeadController::class, 'forceUnlock'])->name('force-unlock');
        Route::patch('bulk-update', [\App\Http\Controllers\JobLeadController::class, 'bulkUpdate'])->name('bulk-update');
    });

    // Job Application Management Routes
    Route::resource('jobs', \App\Http\Controllers\JobController::class);
    Route::resource('job-leads', \App\Http\Controllers\JobLeadController::class);
    
    // Job Dashboard
    Route::get('job-dashboard', [\App\Http\Controllers\JobDashboardController::class, 'index'])->name('job-dashboard');
    Route::get('job-dashboard/data', [\App\Http\Controllers\JobDashboardController::class, 'getData'])->name('job-dashboard.data');

    // PDF Export Routes
    Route::prefix('pdf')->name('pdf.')->group(function () {
        Route::get('/lead/{lead}', [\App\Http\Controllers\PdfController::class, 'generateLeadPdf'])->name('lead');
        Route::get('/leads-list', [\App\Http\Controllers\PdfController::class, 'generateLeadsListPdf'])->name('leads.list');
        Route::get('/cv-form/{lead?}', [\App\Http\Controllers\PdfController::class, 'generateCvForm'])->name('cv.form');
        Route::get('/test', [\App\Http\Controllers\PdfController::class, 'testPdf'])->name('test');
        
        // PDF Download Routes
        Route::get('/download/lead/{lead}', [\App\Http\Controllers\PdfDownloadController::class, 'downloadLeadPdf'])->name('download.lead');
        Route::get('/download/leads-list', [\App\Http\Controllers\PdfDownloadController::class, 'downloadLeadsListPdf'])->name('download.leads.list');
        Route::get('/download/cv-form/{lead?}', [\App\Http\Controllers\PdfDownloadController::class, 'downloadCvForm'])->name('download.cv.form');
    });
