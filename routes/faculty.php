<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Route::group(['namespace' => "faculty",'middleware' => 'faculty'], function() {
//    Route::get('/dashboard/{prg}', 'facultyController@get_dashboard_faculty');
    Route::get('/mentor_request/{prg}', 'facultyController@get_mentor_request');
    Route::post('/get_off_campus_data', 'facultyController@get_off_campus_data');
    Route::post('/accept_request', 'facultyController@accept_r');
    Route::post('/reject_request', 'facultyController@reject_r');
    Route::post('/create_group', 'facultyController@create_group');
    Route::get('project_details', 'facultyController@get_project_details');
    Route::post('/accept_report', 'facultyController@accept_report');
    Route::post('/reject_report', 'facultyController@reject_report');
    Route::get('/manage_groups', 'facultyController@get_manage_groups');
    Route::post('/delete_group', 'facultyController@delete_group');
    Route::post('/store_report/{id}/{student_id}', 'facultyController@store_report');
    Route::post('/modified_std_prog_data', 'facultyController@modified_std_data');
    Route::get('/mentor_grading', 'facultyController@get_grading');
    Route::post('/save_grade_mentor', 'facultyController@save_grade_mentor');
    Route::post('/submit_grade_mentor', 'facultyController@submit_grade_mentor');
    Route::post('/mentor_comment', 'facultyController@save_mentor_comments');
    Route::post('/get_mentor_comment', 'facultyController@get_mentor_comment');
    Route::get('/mentor_view_report/{std_sub_id}','facultyController@get_mentor_view_report');
    Route::get('/committee_formation','facultyController@get_subject_expert_nomination');
    Route::post('/nominate_se','facultyController@subject_expert_nomination');
    Route::post('/accept_se_nomination','facultyController@accept_se_nomination');
    Route::post('/reject_se_nomination','facultyController@reject_se_nomination');
    Route::get('/bid_project','facultyController@get_bid_project');
    Route::post('/save_bid_request','facultyController@save_bid_request');
    Route::post('/save_grade_committee','facultyController@save_grade_committee');
    Route::post('/submit_grade_committee','facultyController@submit_grade_committee');
    Route::post('/com_comment','facultyController@committee_comment');
});
Route::get('view_report/{id}/{s_id}', 'faculty\facultyController@get_report');
Route::get('/view_plag_report/{id}/{s_id}', 'faculty\facultyController@get_plag_report');