<?php

use Organization\Http\Controllers\AdminController;
use Organization\Http\Controllers\OrganizationController;
use Organization\Http\Controllers\UserController;
use Organization\Http\Controllers\TypeController;

Route::get('/index', [OrganizationController::class, 'index'])->name('orgIndex'); 

// Open create view.
Route::get('/create1', [OrganizationController::class, 'create']);      

// Store data in the database. 
Route::post('/store', [OrganizationController::class, 'store']);       

// Open edit view.
Route::get('/edit/{id}', [OrganizationController::class, 'edit']);         

// Store changes in the db.
Route::patch('/update/{id}', [OrganizationController::class, 'update']);   

//Contact delete
Route::get('/contactremove/{id}', [OrganizationController::class, 'contactremove']);
Route::post('/contactupdate/{id}', [OrganizationController::class, 'contactupdate']);
// Delete a organization.
Route::delete('/delete/{id}', [OrganizationController::class, 'destroy']);     

//More details.
Route::get('/more/{id}', [OrganizationController::class, 'more']);              

//Fetch Contact Types.
Route::any('/create','Organization\Http\Controllers\TypeController@getTypesList'); 

Route::get('/actIndex', [OrganizationController::class, 'activities'])->name('orgActIndex'); 

Route::get('/newActivity', [OrganizationController::class, 'new_activity']); 

Route::post('/activitycreate', [OrganizationController::class, 'activity_create']); 

Route::get('/activityremove/{id}', [OrganizationController::class, 'activity_remove']); 