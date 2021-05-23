<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/rawparents', 'RawFilterController@getRawParents');
Route::get('/raw', 'RawFilterController@getRaws');
Route::post('/raw', 'RawController@store');
Route::put('/raw/{id}', 'RawController@update');
Route::delete('/raw/{id}', 'RawController@delete');
Route::get('/color', 'RawFilterController@getColors');
Route::get('/test', 'RawFilterController@getAllRawParentsByFirm');
Route::get('/test2/{parent_id}', function($parent_id){
    return \App\RawParent::find($parent_id)->leaves()->get()->toJson();
});
Route::get('/test3/{id}', 'RawController@updateAsParent');
Route::get('import/excel','ExcelController@imports');
Route::get('export/excel','ExcelController@requests');

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('login', 'Auth\LoginController@login');

Route::group(['middleware' => ['auth:api', 'auto-logout']], function() {
    Route::post('logout', 'Auth\LoginController@logout');

    Route::group(['middleware' => ['role:clerk|superadmin']], function(){
        Route::post('/import', 'Sklad\ImportController@store');//clerk||
        Route::put('/import/{id}','Sklad\ImportController@update');//clerk||
        Route::delete('/import/{id}', 'Sklad\ImportController@destroy');//clerk||
        Route::get('/import/check-seria', 'Sklad\ImportController@checkSeria');//clerk
        Route::put('/export/{id}', 'Sklad\ExportController@update');//clerk
        Route::get('/request', 'Sklad\ExportController@getRequestedExports');//clerk
        Route::put('/request/{id}/confirm', 'Sklad\ExportController@confirm');//clerk
        Route::get('/firms/{id}/balance', 'RawFilterController@getFirmBalance');//clerk
        Route::get('/firms/{id}/raws', 'RawFilterController@getFirmRaws');//clerk
        Route::post('/export', 'Sklad\ExportController@store');//clerk||
    });

    Route::group(['middleware' => ['role:requestor|superadmin']], function() {
        Route::post('/export/request', 'Sklad\ExportController@storeAsRequest');//requestor||
        Route::put('/export/request/{id}', 'Sklad\ExportController@update');//reqeustor||
    });

    Route::group(['middleware' => ['role:manager|superadmin|director']], function() {
        Route::get('/import/export/history', 'Sklad\ImportController@importExportByRawFirm');//manager||
        Route::get('/balance/filtered', 'RawFilterController@getAllRawParentsByFirm');//manager
        Route::get('/balance', 'RawFilterController@getRawsByFirm');//manager
    });

    Route::group(['middleware' => ['role:clerk|requestor']], function(){
        Route::delete('/export/{id}', 'Sklad\ExportController@delete');//requestor||clerk||
        Route::get('/export/quantity', 'Sklad\ExportController@getQuantity');//clerk||requestor
    });

    Route::get('/import', 'Sklad\ImportController@importHistory')->middleware('role:clerk|manager|director');//clerk||manager


    Route::get('/export', 'Sklad\ExportController@index')->middleware('role:clerk|manager|requestor|director');;//reqeustor||clerk||manager
    Route::get('/firms', 'RawFilterController@getFirms');//all

    Route::prefix('laboratorian')->middleware(['role:laboratorian|superadmin'])
            ->group(function () {
        Route::get('import', 'Laboratorian\ImportController@getNewImports'); //laboratorian
        Route::put('import/{id}','Laboratorian\ImportController@changeStatus'); // laboratorian
    });

});

Route::get('notice/word', 'WordController@notice');



