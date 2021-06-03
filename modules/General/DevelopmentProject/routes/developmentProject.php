<?php

use DevelopmentProject\Http\Controllers\DevelopmentProjectController;

//can be accessed by anyone who can create a request
Route::get('/applicationForm', [DevelopmentProjectController::class, 'form'])->name("devproject");
//route to save the form to the db
Route::post('/saveForm', [DevelopmentProjectController::class, 'save']);
//route to show the created request
Route::get('/show/{id}',  [DevelopmentProjectController::class, 'show']); 
//route to delete a created request
Route::delete('/delete/{processid}/{devid}/{landid}', [DevelopmentProjectController::class, 'destroy']); 
//AJAX autocomplete for gazette
Route::get('/autocompleteGazette', [DevelopmentProjectController::class, 'gazetteAutocomplete'])->name('gazette');