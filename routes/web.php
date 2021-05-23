<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/{menu}', function(){
    return view('main');
})->where('menu', '.*');

Route::get('/warehouse/{menu?}', function () {
    return view('warehouse')->with('token', 'TOKEN');
});


Route::get('/laboratorian/{menu?}', function(){
    return view('laboratorian');
});

Route::get('/login', function(){
    return view('login');
});

Route::get('/manager/{menu?}', function(){
    return view('warehousemanager');
});
Route::get('/director', function(){
    return view('director');
});

Route::get('request', function(){
    return view('request');
});
Route::get('requestor', function(){
    return view('requestor');
});


