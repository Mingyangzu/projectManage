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
Route::get('welcome','Admin\AdminController@welcome')->name('welcome');

Route::get('/','Admin\AdminController@login')->name('/');
Route::get('sign_out','Admin\AdminController@sign_out')->name('sign_out');
Route::post('modify_password','Admin\AdminController@modify_password')->name('modify_password');
Route::post('login_check','Admin\AdminController@login_check')->name('login_check');

Route::get('project_index','Admin\AdminController@project')->name('project_index');
Route::post('get_project','Admin\AdminController@get_project_detail')->name('get_project');
Route::post('update_project','Admin\AdminController@update_project')->name('update_project');
Route::post('add_project','Admin\AdminController@add_project')->name('add_project');

Route::get('finance_index','Admin\FinanceController@finance_index')->name('finance_index');
Route::post('get_collection_records','Admin\FinanceController@get_collection_records')->name('get_collection_records');
Route::post('update_collection','Admin\FinanceController@update_collection')->name('add_update_collection');
Route::post('add_collection','Admin\FinanceController@add_collection')->name('add_collection');

Route::get('expenditure_index','Admin\FinanceController@expenditure_index')->name('expenditure_index');
Route::post('get_expenditure','Admin\FinanceController@get_expenditure')->name('get_expenditure');
Route::post('update_expenditure','Admin\FinanceController@update_expenditure')->name('update_expenditure');
Route::post('add_expenditure','Admin\FinanceController@add_expenditure')->name('add_expenditure');
Route::post('expenditure_url','Admin\FinanceController@expenditure_url')->name('expenditure_url');

Route::get('contract_index','Admin\ContractController@contract_index')->name('contract_index');
Route::post('get_contract','Admin\ContractController@get_contract')->name('get_contract');
Route::post('update_contract','Admin\ContractController@update_contract')->name('update_contract');
Route::post('add_contract','Admin\ContractController@add_contract')->name('add_contract');
Route::post('contract_url','Admin\ContractController@contract_url')->name('contract_url');

Route::get('admin_index','Admin\OperaterController@admin_index')->name('admin_index');
Route::post('get_admin','Admin\OperaterController@get_admin')->name('get_admin');
Route::post('update_admin','Admin\OperaterController@update_admin')->name('update_admin');
Route::post('add_admin','Admin\OperaterController@add_admin')->name('add_admin');

Route::get('customer_index','Admin\CustomerController@customer_index')->name('customer_index');
Route::post('get_customer','Admin\CustomerController@get_customer')->name('get_customer');
Route::post('update_customer','Admin\CustomerController@update_customer')->name('update_customer');
Route::post('add_customer','Admin\CustomerController@add_customer')->name('add_customer');
Route::post('get_customer_admin','Admin\CustomerController@get_customer_admin')->name('get_customer_admin');
Route::post('appoint_customer_admin','Admin\CustomerController@appoint_customer_admin')->name('appoint_customer_admin');

Route::get('menu_list','Admin\MenuController@menu_list')->name('menu_list');
Route::post('get_menu','Admin\MenuController@get_menu')->name('get_menu');
Route::post('update_menu','Admin\MenuController@update_menu')->name('update_menu');
Route::post('add_menu','Admin\MenuController@add_menu')->name('add_menu');

Route::get('menu_cate_list','Admin\MenuController@menu_cate_list')->name('menu_cate_list');
Route::post('get_menu_cate','Admin\MenuController@get_menu_cate')->name('get_menu_cate');
Route::post('update_menu_cate','Admin\MenuController@update_menu_cate')->name('update_menu_cate');
Route::post('add_menu_cate','Admin\MenuController@add_menu_cate')->name('add_menu_cate');

Route::get('role_index','Admin\RoleController@role_index')->name('role_index');
Route::post('get_role','Admin\RoleController@get_role')->name('get_role');
Route::post('update_role','Admin\RoleController@update_role')->name('update_role');
Route::post('add_role','Admin\RoleController@add_role')->name('add_role');
Route::post('get_role_power_list','Admin\RoleController@get_role_power_list')->name('get_role_power_list');
Route::post('submit_role_power','Admin\RoleController@submit_role_power')->name('submit_role_power');

Route::get('power_index','Admin\PowerController@power_index')->name('power_index');
Route::post('get_power','Admin\PowerController@get_power')->name('get_power');
Route::post('update_power','Admin\PowerController@update_power')->name('update_power');
Route::post('add_power','Admin\PowerController@add_power')->name('add_power');

Route::get('step_index','Admin\StepController@step_index')->name('step_index');
Route::post('get_step','Admin\StepController@get_step')->name('get_step');
Route::post('update_step','Admin\StepController@update_step')->name('update_step');
Route::post('add_step','Admin\StepController@add_step')->name('add_step');

Route::get('type_index','Admin\TypeController@type_index')->name('type_index');
Route::post('get_type','Admin\TypeController@get_type')->name('get_type');
Route::post('update_type','Admin\TypeController@update_type')->name('update_type');
Route::post('add_type','Admin\TypeController@add_type')->name('add_type');

Route::get('wages_index','Admin\WagesController@wages_index')->name('wages_index');
Route::post('get_wages','Admin\WagesController@get_wages')->name('get_wages');
Route::post('update_wages','Admin\WagesController@update_wages')->name('update_wages');
Route::post('add_wages','Admin\WagesController@add_wages')->name('add_wages');

//Route::get('manages', 'Manage\ManagesController@index')->name('manages'); 

Route::group(['namespace' => 'Manage'], function(){
   Route::get('welcome','ManagesController@welcome')->name('welcome');
//   Route::get('manages', 'ManagesController@index')->name('manages'); 
   
   Route::get('contract','ContractController@index')->name('contract');
   Route::get('customer_index','CustomerController@index')->name('customer');
   
   Route::get('customerlist', 'CustomerController@customerlist')->name('customerlist');
   Route::post('addcustomer', 'CustomerController@addcustomer')->name('addcustomer');
   Route::get('getcustomer', 'CustomerController@getcustomer')->name('getcustomer');
   Route::get('getprojects', 'CustomerController@getprojects')->name('getprojects');
   Route::delete('delcustomer', 'CustomerController@delcustomer')->name('delcustomer');
   
   Route::get('project_index','ProjectController@index')->name('project');
   Route::get('projectlist','ProjectController@projectlist')->name('projectlist');
   Route::post('addproject', 'ProjectController@addproject')->name('addproject');
   Route::get('getproject', 'ProjectController@getproject')->name('getproject');
   Route::delete('delproject', 'ProjectController@delproject')->name('delproject');
   
   Route::get('record', 'RecordController@index')->name('record');
   Route::post('addrecord', 'RecordController@addrecord')->name('addrecord');
   Route::get('getrecord', 'RecordController@getrecord')->name('getrecord');
   Route::get('getrecordlist', 'RecordController@getrecordlist')->name('getrecordlist');
   Route::delete('delrecord', 'RecordController@delrecord')->name('delrecord');
   
   Route::get('contract', 'ContractController@index')->name('contract');
   Route::get('contractlist', 'ContractController@contractlist')->name('contractlist');
   Route::post('addcontract', 'ContractController@addcontract')->name('addcontract');
   Route::get('getcontract', 'ContractController@getcontract')->name('getcontract');
   Route::delete('delcontract', 'ContractController@delcontract')->name('delcontract');
   Route::post('upcontractfiles', 'ContractController@upcontractfiles')->name('upcontractfiles');
   
   Route::get('package', 'PackageController@index')->name('package');
   Route::get('packagelist', 'PackageController@packagelist')->name('packagelist');
   Route::post('addpackage', 'PackageController@addpackage')->name('addpackage');
   Route::get('getpackage', 'PackageController@getpackage')->name('getpackage');
   Route::delete('delpackage', 'PackageController@delpackage')->name('delpackage');
   Route::post('uppackagefiles', 'PackageController@uppackagefiles')->name('uppackagefiles');
   
   Route::group(['prefix'=>'process'], function(){
        Route::get('lists', 'ProcessController@lists')->name('process.lists');
        Route::get('getlists', 'ProcessController@getlists')->name('process.getlists');
        Route::get('todolist', 'ProcessController@todolist')->name('process.todolist');
        Route::get('gettodolist', 'ProcessController@gettodolist')->name('process.gettodolist');
        Route::get('handled', 'ProcessController@handled')->name('process.handled');
        Route::get('handledlist', 'ProcessController@handledlist')->name('process.handledlist');
        Route::get('getproject', 'ProcessController@getproject')->name('process.getproject');
        Route::post('addprocess', 'ProcessController@addprocess')->name('process.addprocess');
        Route::delete('del', 'ProcessController@del')->name('del');
        Route::get('detail', 'ProcessController@detail')->name('process.detail');
        Route::get('edit', 'ProcessController@edit')->name('process.edit');
        Route::post('addnote', 'ProcessController@addnote')->name('process.addnote');
        Route::post('overnote', 'ProcessController@overnote')->name('process.overnote');
        Route::post('subfinance', 'ProcessController@subfinance')->name('process.subfinance');
        
   });
   
});

