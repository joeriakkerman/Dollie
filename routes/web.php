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

Route::get('/', [
    'middleware' => ['auth'],
    'uses' => "HomeController@index"]);

Auth::routes();

Route::get('/bankAccountsOverview', 'BankAccountController@index')->name('bankAccountsOverview');

Route::get('/newdollie', 'DolliesController@index')->name('newdollie');

Route::post('/newdollie', 'DolliesController@verifyDollie')->name('newdollie.verify');

Route::post('/newdollie', 'DolliesController@saveDollie')->name('newdollie.save');

Route::resource('bankAccounts', 'BankAccountController');