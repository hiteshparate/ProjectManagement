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

Route::get('/', function () {
    return view('login');
});


//Route::get('/admin_home','loginController@get_admin_home');
Route::post('/login', 'loginController@login');
Route::get('/get_dashboard','loginController@get_dashboard');
Route::get('/logout', 'loginController@logout');
Route::get('/dashboard','loginController@get_dashboard_coordinator');
//Route::post('/get_off_campus_data','facultyController@get_off_campus_data');
//Route::get('consent_form','studentController@get_form');
//Route::post('/register','studentController@register_student');
//Route::post('/off_campus_details','studentController@off_campus_data');
Route::get('/option','loginController@get_option');
Route::get('/faculty_option','loginController@get_faculty_option');
//Route::get('/view_project','studentController@get_view_project');
//Route::get('/submission','studentController@get_submission');
//Route::get('/view_report/{id}','studentController@get_report');
//Route::post('/store_report/{e_id}', 'studentController@store_report');
Route::get('/forgotPassword','passwordResetController@getPasswordView');
Route::post('/reset_password','passwordResetController@resetPassword');
Route::get('/reset_password/reset','passwordResetController@get_reset_password');
Route::post('/new_password','passwordResetController@reset');
Route::post('/accept_request','facultyController@accept_r');
Route::get('/rc_view_reports','loginController@get_view_reports');
