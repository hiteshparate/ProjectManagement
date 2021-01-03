<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\student_program;
use App\Helpers\LogActivity;

class loginController extends Controller {

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
                    'password' => 'required|string|min:6',
                    'username' => 'required|string',
        ]);
        if ($validator->fails()) {
            return redirect('/')->withErrors($validator)->withInput();
        } else {
            $username = $request->input('username');
            $password = $request->input('password');
            if ($username == "admin") {
                $check = Auth::guard('admin')->attempt(['username' => $username, 'password' => $password]);
                if ($check) {
                    Session::put("role","admin");
                    LogActivity::addToLog('Admin Logged in');
                    return redirect('admin_add_program');
                } else {
                    return redirect('/')->with('login_error', 'Login failed');
                }
            } else {
                $std = Student::where(['username' => $username])->first();
                $fact = \App\Faculty::where(['username' => $username])->first();
                $coord = \App\coordinator::where(['username' => $username])->first();
                $rc = \App\resource_centre::where(['username' => $username])->first();
                $role = null;
                if ($std != null) {
                    $role = 'student';
                } else if ($fact != null) {
                    $role = 'faculty';
                } else if ($coord != null) {
                    $role = 'coordinator';
                } else if ($rc != null) {
                    $role = 'rc';
                }
                if ($role == 'student') {
                    $check = Auth::guard('student')->attempt(['username' => $username, 'password' => $password]);
                    if ($check) {
                        Session::put('role', 'student');
                        LogActivity::addToLog('Student Logged in');
                        $cnt = student_program::where('student_id', Auth::guard('student')->id())->count();
                        if ($cnt == 0) {
                            return redirect('/get_dashboard');
                        } else {
                            return redirect('/option');
                        }
                    } else {
                        return redirect('/')->with('login_error', 'Login failed');
                    }
                } else if ($role == 'faculty') {
                    $check = Auth::guard('faculty')->attempt(['username' => $username, 'password' => $password]);
                    if ($check) {
                        Session::put('role', 'faculty');
                        LogActivity::addToLog('faculty Logged in');
                        return redirect('/faculty_option');
                    } else {
                        return redirect('/')->with('status', 'Login failed');
                    }
                } else if ($role == 'coordinator') {

                    $check = Auth::guard('coordinator')->attempt(['username' => $username, 'password' => $password]);
                    if ($check) {
                        Session::put('role', 'coordinator');
                        LogActivity::addToLog('coordinator Logged in');
                        $coord_id = \App\coordinator::where('username', $username)->first()->id;
                        $prg_id = \App\program::where('coordinator_id', $coord_id)->first()->id;
                        Session::put('coord_prg', $prg_id);
                        return redirect('/add_student_to_programme');
                    } else {
                        return redirect('/')->with('login_error', "credentials don't match");
                    }
                } else if ($role == 'rc') {
                    $check = Auth::guard('rc')->attempt(['username' => $username, 'password' => $password]);
                    if ($check) {
                        Session::put('role', 'rc');
                        LogActivity::addToLog('RC Logged in');
                        return redirect('/rc_view_reports');
                    } else {
                        return redirect('/')->with('login_error', "credentials don't match");
                    }
                }else{
                    return redirect('/')->with('login_error', "credentials don't match");
                }
            }
        }
    }

    public function get_admin_home() {
        return view('admin/admin_home');
    }

    public function get_dashboard() {
        return view('student/student_deshboard');
    }
    
    public function get_view_reports(){
        $programs = \App\program::all();
        return view('rc/rc_view_reports')->with("programs",$programs);
    }

    public function get_dashboard_coordinator() {
        return view('coordinator/dashboard');
    }

    public function logout() {
        Auth::guard(Session::get('role'))->logout();
        return redirect('/');
    }

    public function get_option() {
        $std = Auth::guard('student')->user();
        $programs = $std->programs()->get();

        return view('student/option')->with('programs', $programs);
    }

    public function get_faculty_option() {
        $program = \App\program::all();
        return view('faculty/option')->with('program', $program);
    }

}

?>
