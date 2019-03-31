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

Route::get('/', function() {return redirect(app()->getLocale());
});

Route::group(['prefix' => '{locale}', 'where' => ['locale' => '[a-zA-Z]{2}'],
'middleware' => 'setlocale'], function(){

  Route::get('/', [
      'middleware' => ['auth'],
      'uses' => "HomeController@index"])->name('index');


  Route::post('/', "HomeController@filter")->name('filter');

  Route::get('/bankAccountsOverview', 'BankAccountController@index')->name('bankAccountsOverview');

  Route::post('/bankAccountsOverview', 'BankAccountController@create')->name('bankAccountsOverview');

  Route::delete('/bankAccountsOverview', 'BankAccountController@delete')->name('bankaccount.delete');

  //Route::delete('/bankAccountsOverview{bank_account}', ["uses" => 'BankAccountController@delete', "as" => 'delete']);

  Route::post('/users', "HomeController@getUsers")->name('users');

  Route::post('/deletedollie', "HomeController@deleteDollie")->name('dollie.delete');

  Route::post('/payment', 'PaymentsController@prepare')->name('prepare');

  Route::get('/payment', [
      'middleware' => ['auth'],
      'uses' => 'PaymentsController@link'])->name('payment.link');

  Auth::routes();

  Route::get('/newdollie', 'DolliesController@index')->name('newdollie');

  Route::post('/newdollie', 'DolliesController@verifyDollie')->name('newdollie.verify');

  Route::get('/dollie/{dollie_id}', "DolliesController@showDollie")->name('dollie.show');

  Route::post('/getgroups', 'GroupController@getGroups')->name('getgroups');

  Route::get('/groups', 'GroupController@index')->name('groups');

  Route::post('/groups', 'GroupController@add')->name('addgroup');

  Route::post('/deletegroup', 'GroupController@delete')->name('group.delete');

  Route::post('/addmember', 'GroupController@addMember')->name('group.addmember');

  Route::resource('bankAccounts', 'BankAccountController');

  Route::post('/savedollie', 'DolliesController@saveDollie')->name('newdollie.save');

  Route::get('/accounts', 'AccountsController@index')->name('accounts');

  Route::get('/events', 'EventsController@index')->name('events');
});

Route::post('/webhook', 'PaymentsController@webhook')->name('payment.webhook');

Route::get('/dollieimage/{filename}', ["uses" => "DolliesController@dollieImage", "as" => "dollie.image"]);