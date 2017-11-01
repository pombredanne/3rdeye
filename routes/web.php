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


Route::get('/index', function () {
    return view('default/landing');
})->name('index');

Route::group(['middleware'=>'auth'], function(){
    
    /*
    Route::get('/', function () {
        return view('pages/search');
    });
    */

    Route::get('/', 'HomeController@home');
    
    Route::post('/account/newuser', 'UsersController@save');  
    Route::get('/account', 'UsersController@index');
    
    Route::post('/add-reference', 'ReferenceController@save');   
    Route::get('/references', 'ReferenceController@index');    
    Route::get('/reference/delete/{id}', 'ReferenceController@destroy');    
    Route::get('/reference/edit/{id}', 'ReferenceController@showDetails');    
    Route::put('/reference/edit/{id}', 'ReferenceController@update'); 
    Route::get('/add-reference', 'ReferenceController@insert');


    Route::get('/reports', 'ReportController@index');
    Route::post('/report/text', 'ReportController@textreport');
    Route::post('/report/pdf', 'ReportController@pdfreport');
    Route::get('/report/view/{id}', 'ReportController@view');
    Route::get('/report/delete/{id}','ReportController@destroy');

});



Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');
Route::post('/demo/result', 'ReportController@demoIndex');
