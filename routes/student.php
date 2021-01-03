<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Route::group(['namespace' => "student",'middleware' => 'student'], function() {
    Route::get('consent_form/{prg}', 'studentController@get_form');
    Route::post('/register', 'studentController@register_student');
    Route::post('/off_campus_details', 'studentController@off_campus_data');
    Route::get('/view_project', 'studentController@get_view_project');
    Route::get('/submission', 'studentController@get_submission');
//    Route::get('/view_report/{id}', 'studentController@get_report');
//    Route::get('/std_view_report/{id}/{s_id}', 'studentController@get_report');
    Route::post('/store_report/{e_id}', 'studentController@store_report');
    Route::get('/get_edit_off_campus','studentController@get_edit_off_campus_details');
    Route::post('/edit_off_details','studentController@edit_off_details');
    Route::get('/get_std_sub_report/{std_sub_id}', 'studentController@get_std_sub_report');
    Route::post('/get_final_keywords','studentController@final_keywords');
    
});
