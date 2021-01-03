<?php

Route::group(['namespace' => "admin", 'middleware' => 'admin'], function() {

    Route::get('/admin_add_program', 'adminController@get_admin_add_prg');
    Route::post('/add_new_program', 'adminController@add_new_program');
    Route::get('/admin_change_coordinator', 'adminController@admin_change_coordinator');
    Route::post('/change_coordinator', 'adminController@change_coordinator');
    Route::get('/admin_view_program', 'adminController@view_program');
    Route::get('/admin_manage_grades', 'adminController@get_admin_manage_grades');
    Route::post('/add_new_grading_system', 'adminController@add_new_grading_system');
    Route::get('/admin_manage_faculties', 'adminController@get_admin_faculties');
    Route::post('/add_faculty', 'adminController@add_faculty');
    Route::get('/admin_manage_aoi', 'adminController@get_admin_manage_aoi');
    Route::post('/delete_aoi', 'adminController@delete_aoi');
    Route::post('/add_new_aoi', 'adminController@add_new_aoi');
    Route::get('/admin_view_reports', 'adminController@get_view_reports');
    Route::post("/get_admin_reports", "adminController@get_reports");
    Route::get("/admin_get_std_report/{id}", "adminController@bring_report");
    Route::get('/logActivity', 'adminController@logActivity');
});
