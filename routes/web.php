<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/show-transaction/{id}', function($id) {

    $transaction = \App\Transaction::find($id);

    echo "<pre>";
    echo "Before <br>";
    print_r($transaction->toArray());

});

Route::get('/show-transaction-id/{transid}', function($transid) {

    // dd($transid);

    $transaction = \App\Transaction::where('transid',$transid);

    echo "<pre>";
    echo "Before <br>";
    print_r($transaction->get()->toArray());

});

Route::get('/update-transaction/{id}/{column_name}/{value}', function($id, $column_name,$value) {    

    $sequence = \App\Transaction::find($id);

    echo "<pre>";
    echo "Before <br>";
    print_r($sequence->toArray());

    $sequence->$column_name = $value;
    $sequence->save();

    echo "<pre>";
    echo "After <br>";
    print_r($sequence->toArray());
  

});


Route::get('/show-approval/{transaction_id}', function($transaction_id) {

    $approval = \App\ApprovalStatus::where('transaction_id',$transaction_id);

    // echo $approval->get();

    echo "<pre>";
    echo "Before <br>";
    print_r($approval->get()->toArray());

});

Route::get('/update-approval/{id}/{column_name}/{value}', function($id, $column_name,$value) {    

    $sequence = \App\ApprovalStatus::find($id);

    echo "<pre>";
    echo "Before <br>";
    print_r($sequence->toArray());

    $sequence->$column_name = $value;
    $sequence->save();

    echo "<pre>";
    echo "After <br>";
    print_r($sequence->toArray());
  

});

Route::get('/user-lists', function() {

    return \App\User::all();

});


Route::get('/show-data/{tablename}/{id}', function($tablename,$id) {

    $table = "\\App\\".$tablename;    

    $sequence = $table::find($id);

    // $sequence = \App\Toheader::find($id);

    echo "<pre>";
    echo "Before <br>";
    print_r($sequence->toArray());
  

});


Route::get('/update-data/{tablename}/{id}/{column_name}/{value}', function($tablename,$id, $column_name,$value) {

    $table = "\\App\\".$tablename;    

    $sequence = $table::find($id);

    // $sequence = \App\Toheader::find($id);

    echo "<pre>";
    echo "Before <br>";
    print_r($sequence->toArray());

    $sequence->$column_name = $value;
    $sequence->save();

    echo "<pre>";
    echo "After <br>";
    print_r($sequence->toArray());
  

});


Route::view('/login','auth.login')->name('login');
Route::post('/checklogin', 'Auth\LoginController@checklogin')->name('login-attempt');
Route::get('/logout','Auth\LoginController@logout')->name('logout');


Route::get('/approval-overview/{id}/{type}','TransactionsController@overview')->name('approval-overview');

Route::group(['middleware' => ['auth']], function () {
	   Route::post('/search-hris-employee', 'SearchController@search_hris_employee')->name('search.hris.employee');
// User/Approver Maintenance
	Route::resource('/approvers','UserController');
	Route::get('/delete-approver','UserController@destroy')->name('approver.delete');
//

// Maintenance
	Route::get('/allowed-transactions','MaintenanceController@transaction_index')->name('allowed-transactions.index');
	Route::get('/allowed-application/create','MaintenanceController@transaction_create')->name('transaction.create');
	Route::post('/allowed-application/store','MaintenanceController@transaction_store')->name('transaction.store');
	Route::get('/allowed-application/edit/{id}','MaintenanceController@transaction_edit')->name('transaction.edit');
	Route::post('/allowed-application/update','MaintenanceController@transaction_update')->name('transaction.update');
	Route::get('/remove-application','MaintenanceController@app_remove')->name('app.remove');
    Route::get('/change-password', function() {

            $id = \Auth::user()->id;

            return view('auth.passwords.change', compact('id'));

        })->name('change.password');
    Route::patch('/update-password', 'UserController@updatePassword')->name('update.password');

// Template
	Route::resource('/templates','TemplateController');
	Route::get('/delete-template','TemplateController@delete')->name('template.delete');
//

// Transactions
	Route::get('/transactions','TransactionsController@index')->name('transactions.index');
	Route::get('/history','TransactionsController@history')->name('transactions.history');
	//Route::get('/requests/pending','TransactionsController@pendings')->name('pending.requests');
	//Route::get('/get-request-details/{id}','TransactionsController@get_request_details');

	Route::get('/transaction/details/{id}','TransactionsController@details')->name('transaction.details');

	Route::post('/transactions/details/batch/','TransactionsController@batchsubmit')->name('transaction.batchsubmit');
//

// Approval Status
	Route::post('updateStatus', 'ApprovalStatusController@update_request_status');
//

// Email Routes
	Route::view('/emails','email.index')->name('emails.index');
	Route::view('/email/show','email.show')->name('email.show');
	Route::view('/email/compose','email.compose')->name('email.compose');

// Sample using mail trap next approver
    Route::get('emailtrap', function(){
        \Mail::to('ariel.gayorgor@gmail.com')->send(new \App\Mail\NextApproverNotification('tabang'));
    });

// Sample using PMC next approver
    Route::get('pmcemail', function(){
        \Mail::to('aagayorgor@philsaga.com')->send(new \App\Mail\NextApproverNotification('help'));
    });

// Sample using mail trap canceled
    Route::get('emailtrapcanceled', function(){
        \Mail::to('ariel.gayorgor@gmail.com')->send(new \App\Mail\CanceledNotification('tabang'));
    });

// Sample using PMC canceled
    Route::get('pmcemailcanceled', function(){
        \Mail::to('aagayorgor@philsaga.com')->send(new \App\Mail\CanceledNotification('help'));
    });

// Sample using mail trap on hold
    Route::get('emailtraponhold', function(){
        \Mail::to('ariel.gayorgor@gmail.com')->send(new \App\Mail\OnholdNotification('tabang'));
    });

// Sample using PMC on hold
    Route::get('pmcemailonhold', function(){
        \Mail::to('aagayorgor@philsaga.com')->send(new \App\Mail\OnholdNotification('help'));
    });

});
