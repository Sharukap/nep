<?php

use Admin\Http\Controllers\AdminController;
use Admin\Http\Controllers\UserController;

/////PASSWORD RESET
Route::get('/passwordReset', function() {       // Open view to reset password.
    return view('admin::passwordReset');
});
Route::patch('/alterPassword', [UserController::class, 'alterPassword']);       // Save data to the db.

Route::middleware(['auth'])->group(function () {
// user/index route will route to the UserController to route based on the user's role  
Route::get('/index', [UserController::class, 'index'])->name('userIndex'); 


///////ADMIN ACTIONS      
Route::get('/create', [UserController::class, 'create']);      // Open create view.
Route::post('/store', [UserController::class, 'store']);       // Store data in the database. 
Route::get('/edit/{id}', [UserController::class, 'edit']);         // Open edit view
Route::patch('/update/{id}', [UserController::class, 'update']);   // Store changes in the db.
Route::delete('/delete/{id}', [AdminController::class, 'destroy']);     // Delete a user.
Route::get('/changePrivilege/{id}', [AdminController::class, 'changePrivilege']);   // Open the view to change privileges.
Route::patch('/savePrivilege/{id}', [AdminController::class, 'savePrivilege']);     // Save those changes to the db.

//////SELF REGISTERED SECTION
Route::get('/showSelfRegistered', [AdminController::class, 'showSelfRegistered']);      // Open the view to show all self registered users
Route::get('/showActivate/{id}', [AdminController::class, 'showActivate']);     // Open the view to activate a particular user.
Route::patch('/activate/{id}', [AdminController::class, 'activate']);           // Save that user to the database.


///////More details button for all users - Admin, HoO and Manager - One route because same functionality
Route::get('/more/{id}', [UserController::class, 'more']);

//search active users
Route::get('/searchUsers', [UserController::class, 'searchUsers']);
//search - activate users
Route::get('/searchSelfRegistered', [UserController::class, 'searchSelfRegistered']);

//Role Based Access control management
Route::get('/roleindex',[AdminController::class, 'index'])->name('roleIndex');
Route::get('/roleedit/{id}',[AdminController::class, 'roleedit'])->name('roleedit');
Route::post('/rolePriviledge/{id}',[AdminController::class, 'roleupdate']);
Route::get('/removeAccess/{id}',[AdminController::class, 'accessremove']);

});








