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
    'uses' => "HomeController@index"])->name('index');

Route::post('/', "HomeController@filter")->name('filter');

Route::get('/bankAccountsOverview', 'BankAccountController@index')->name('bankAccountsOverview');

Route::post('/bankAccountsOverview', 'BankAccountController@create')->name('bankAccountsOverview');

Route::delete('/bankAccountsOverview', 'BankAccountController@delete');

// Route::delete('/bankAccountsOverview{bank_account}', ["uses" => 'BankAccountController@delete', "as" => 'delete']);

Route::post('/users', "HomeController@getUsers")->name('users');

Route::post('/deletedollie', "HomeController@deleteDollie")->name('dollie.delete');

Route::post('/payment', 'PaymentsController@prepare')->name('prepare');

Route::get('/payment', [
    'middleware' => ['auth'],
    'uses' => 'PaymentsController@link'])->name('payment.link');

Route::post('/webhook', 'PaymentsController@webhook')->name('payment.webhook');

Auth::routes();

Route::get('/newdollie', 'DolliesController@index')->name('newdollie');

Route::post('/newdollie', 'DolliesController@verifyDollie')->name('newdollie.verify');

// Route::post('/newdollie', 'DolliesController@saveDollie')->name('newdollie.save');

Route::resource('bankAccounts', 'BankAccountController');

Route::post('/savedollie', 'DolliesController@saveDollie')->name('newdollie.save');

Route::get('/accounts', 'AccountsController@index')->name('accounts');
