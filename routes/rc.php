<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Route::group(['namespace' => "rc",'middleware' => 'rc'], function() {
    Route::post("/get_reports","rcController@get_reports");
    Route::get("/get_std_report/{id}","rcController@bring_report");
    Route::get('add_another_login', 'rcController@get_add_another_login');
    Route::post('add_rc_user','rcController@add_rc_user');
    Route::post('delete_user','rcController@delete_user');
    
});
