<?php
// Categories
Route::get('/categories', 'CategoryController@index')->name('categories');
Route::get('/categories/get', 'CategoryController@getAll');
Route::post('/categories/create/{title}/{description}', 'CategoryController@create');
Route::post('/categories/destroy/{category}', 'CategoryController@destroy');
Route::post('/categories/update/{category}/{title}/{description}', 'CategoryController@update');