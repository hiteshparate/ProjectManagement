<?php


namespace App\Helpers;
use Request;
use App\LogActivity as LogActivityModel;
use Illuminate\Support\Facades\Auth;


class LogActivity
{


    public static function addToLog($subject)
    {
        $std = Auth::guard('student')->user();
        $faculty = Auth::guard('faculty')->user();
        $coord = Auth::guard('coordinator')->user();
        $rc = Auth::guard('rc')->user();
        $admin = Auth::guard('admin')->user();
    	$log = [];
    	$log['subject'] = $subject;
    	$log['url'] = Request::fullUrl();
    	$log['method'] = Request::method();
    	$log['ip'] = Request::ip();
    	$log['agent'] = Request::header('user-agent');
        if($std != null){
            $log['user_id'] = $std->username;
        }else if($faculty != null){
            $log['user_id'] = $faculty->username;
        }else if($coord != null){
            $log['user_id'] = $coord->username;
        }else if($rc != null){
            $log['user_id'] = $rc->username;
        }else if($admin != null){
            $log['user_id'] = $admin->username;
        }else if($rc != null){
            $log['user_id'] = $rc->username;
        }else{
            $log['user_id'] = 1;
        }
    	
    	LogActivityModel::create($log);
    }


    public static function logActivityLists()
    {
    	return LogActivityModel::latest()->get();
    }


}