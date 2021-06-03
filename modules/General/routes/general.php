<?php

use General\Http\Controllers\GeneralController;
use Organization\Http\Controllers\OrganizationController;

Route::middleware(['access.control:1'])->group(function () {


Route::get('/showRequests', [ApprovalItemController::class, 'showRequests']);

//Route::get('/general', [GeneralController::class, 'general_module_access_control']);

Route::get('/pending', [GeneralController::class, 'pending'])->name("pending");

Route::get('/systemSetting', [GeneralController::class, 'system_data']);

Route::get('/autoadminOn', [OrganizationController::class, 'activity_admin_on']);

Route::get('/autoadminOff', [OrganizationController::class, 'activity_admin_off']);

Route::get('/filterItems', [GeneralController::class, 'filter_process_items']);

});