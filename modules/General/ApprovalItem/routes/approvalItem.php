<?php

use ApprovalItem\Http\Controllers\ApprovalItemController;

Route::get('/showRequests', [ApprovalItemController::class, 'showRequests']);
Route::get('/filterRequests', [ApprovalItemController::class, 'filterRequests']);
Route::middleware(['access.control:6'])->group(function () {
    Route::get('/changeassignOrganization/{id}/{pid}', [ApprovalItemController::class, 'change_assign_organization']);

    Route::post('/changeassignOrganization', [ApprovalItemController::class, 'assign_unregistered_organization']);

    Route::get('/assignorganization/{id}', [ApprovalItemController::class, 'choose_assign_organization']);
});

Route::middleware(['access.control:7'])->group(function () {

    Route::get('/assignstaff/{id}', [ApprovalItemController::class, 'choose_assign_staff']);

    Route::get('/confirmassign/{id}/{pid}', [ApprovalItemController::class, 'confirm_assign_staff']);
});

Route::middleware(['access.control:8'])->group(function () {

    Route::get('/investigate/{id}', [ApprovalItemController::class, 'investigate']);

    Route::post('/createprerequisite', [ApprovalItemController::class, 'create_prerequisite']);

    Route::post('/progresssave', [ApprovalItemController::class, 'progress_update']);

    Route::post('/finalapproval', [ApprovalItemController::class, 'final_approval']);

    Route::get('/cancelprerequisite/{id}/{uid}', [ApprovalItemController::class, 'cancel_prerequisite']);
});