<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Student;
use App\Faculty;
use App\coordinator;
use App\resource_centre;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use App\Jobs\updatePasswordToken;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use App\admin;

class passwordResetController extends Controller {

    public function getPasswordView() {
        return view('forgotPassword');
    }

//    public function resetPassword(Request $request) {
//        $user = null;
//        $std = Student::where('email', $request->input('email'))->first();
//        $var = "";
//        $link = "";
//        $email = "";
//        if ($std == null) {
//            $var = "faculty";
//            $validator = Validator::make($request->all(), [
//                        'email' => 'required|exists:faculties,email|email'
//            ]);
//        } else {
//            $var = "student";
//            $validator = Validator::make($request->all(), [
//                        'email' => 'required|exists:students,email|email'
//            ]);
//        }
//
//        if ($validator->fails()) {
//            return redirect('/forgotPassword')->withErrors($validator);
//        } else {
//            $id = 0;
//            $email = $request->input('email');
//            $str = md5(str_random(50));
//            if ($var == 'student') {
//                $user = Student::where('email', $request->input('email'))->first();
//                $id = $user->id;
//                $link = URL::current() . "/reset?id=$id&r=s&key=" . $str;
//                $std->forgot_password_key = $str;
//                $std->save();
//            } else {
//                $faculty = Faculty::where('email', $request->input('email'))->first();
//                if ($faculty == null) {       //RC
//                } else {      //MENTOR
//                    $user = Faculty::where('email', $request->input('email'))->first();
//                    $id = $user->id;
//                    $link = URL::current() . "/reset?id=$id&r=f&key=" . $str;
//                    $faculty->forgot_password_key = $str;
//                    $faculty->save();
//                }
//            }
//            $data = ['link' => $link];
//            Mail::send('emails.passwordReset',$data,function($m) use ($email){
//               $m->subject("Reset Password Link");
//               $m->to($email); 
//            });
//
//            $job = (new updatePasswordToken($user))
//                    ->delay(Carbon::now()->addMinutes(60));
//
//            dispatch($job);
//            
//            return redirect('/')->with('status','Password Reset mail has been sent to your email address');
//
//
//            
//        }
//    }

    public function resetPassword(Request $request) {
        $email = $request->input('email');
        $std = Student::where('email', $email)->first();
        $coord = coordinator::where('email', $email)->first();
        $fac = Faculty::where('email', $email)->first();
        $rc = resource_centre::where('email', $email)->first();
        $admin = admin::where('email',$email)->first();
        $validator = null;
        $var = "";
        $link = "";
        $user = "";
        $job = "";

        if ($std != null) {
            $var = "student";
            $validator = Validator::make($request->all(), [
                        'email' => 'required|exists:students,email|email'
            ]);
        } else if ($fac != null) {
            $var = "faculty";
            $validator = Validator::make($request->all(), [
                        'email' => 'required|exists:faculties,email|email'
            ]);
        } else if ($coord != null) {
            $var = "coordinator";
            $validator = Validator::make($request->all(), [
                        'email' => 'required|exists:coordinators,email|email'
            ]);
        } else if ($rc != null) {
            $var = "rc";
            $validator = Validator::make($request->all(), [
                        'email' => 'required|exists:resource_centres,email|email'
            ]);
        }else if($admin != null){
            $var = "admin";
            $validator = Validator::make($request->all(), [
                        'email' => 'required|exists:admins,email|email'
            ]);
        }

        if ($validator->fails()) {
            return redirect('/forgotPassword')->withErrors($validator);
        } else {
            $id = 0;
            $str = md5(str_random(50));
            if ($var == 'student') {
                $user = Student::where('email', $email)->first();
                $id = $user->id;
                $link = URL::current() . "/reset?id=$id&r=s&key=" . $str;
                $user->forgot_password_key = $str;
                $user->save();
                $job = (new updatePasswordToken($user, null, null, null,null))
                        ->delay(Carbon::now()->addMinutes(60));
            } else if ($var == "faculty") {
                $user = Faculty::where('email', $email)->first();
                $id = $user->id;
                $link = URL::current() . "/reset?id=$id&r=f&key=" . $str;
                $user->forgot_password_key = $str;
                $user->save();
                $job = (new updatePasswordToken(null, $user, null, null,null))
                        ->delay(Carbon::now()->addMinutes(60));
            } else if ($var == "coordinator") {
                $user = coordinator::where('email', $email)->first();
                $id = $user->id;
                $link = URL::current() . "/reset?id=$id&r=c&key=" . $str;
                $user->forgot_password_key = $str;
                $user->save();
                $job = (new updatePasswordToken(null, null, $user, null,null))
                        ->delay(Carbon::now()->addMinutes(60));
            } else if ($var == "rc") {
                $user = resource_centre::where('email', $email)->first();
                $id = $user->id;
                $link = URL::current() . "/reset?id=$id&r=r&key=" . $str;
                $user->forgot_password_key = $str;
                $user->save();
                $job = (new updatePasswordToken(null, null, null, $user,null))
                        ->delay(Carbon::now()->addMinutes(60));
            }else if($var == "admin"){
                $user = admin::where('email', $email)->first();
                $id = $user->id;
                $link = URL::current() . "/reset?id=$id&r=a&key=" . $str;
                $user->forgot_password_key = $str;
                $user->save();
                $job = (new updatePasswordToken(null, null, null, null,$user))
                        ->delay(Carbon::now()->addMinutes(60));
            }
            $data = ['link' => $link];
            Mail::send('emails.passwordReset', $data, function($m) use ($email) {
                $m->subject("Reset Password Link");
                $m->to($email);
            });




            dispatch($job);
            return redirect('/')->with('status', 'Password Reset mail has been sent to your email address');
        }
    }

    public function get_reset_password() {
        $key = Input::get('key');
        $role = Input::get('r');
        $id = Input::get('id');
        $user = null;
        if ($role == 's') {
            $user = Student::find($id);
        } else if ($role == 'f') {
            $user = Faculty::find($id);
        } else if ($role == 'c') {
            $user = coordinator::find($id);
        } else if ($role == 'r') {
            $user = resource_centre::find($id);
        }else if($role == "a"){
            $user = admin::find($id);
        }
        if ($user->forgot_password_key == $key) {
            Session::put('user', $user);
            Session::put('u', $role);
            return view('passwordReset');
        } else {
            return redirect('/')->with('sw_alert', 'Your token has been expired, try forgot password again');
        }
    }

    public function reset(Request $request) {
        $user = Session::get('user');

        $pass1 = $request->input('password');
        $pass2 = $request->input('confirm_password');
        $validator = Validator::make($request->all(), [
                    'password' => 'required|string|min:6',
                    'confirm_password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            if ($pass1 == $pass2) {
                $user->password = bcrypt($pass1);
                $user->save();
                if (Session::get('u') == "f") {
                    $coordinators = coordinator::all();
                    foreach ($coordinators as $c) {
                        if ($c->faculty_id == $user->id) {
                            $program_id = \App\program::where("coordinator_id", $c->id)->first();
                            $sync = new \App\Classes\uspmes();
                            $sync->sync_coordinator_password($program_id->id, $user->id);
                        }
                    }
                }
                if (Session::get('u') == 'c') {
                    $fac = Faculty::find($user->faculty_id);
                    $fac->password = $user->password;
                    $fac->save();
                }
                return redirect('/')->with('status', 'Password Changed Successfully');
            } else {
                return redirect()->back()->with('sw_alert', 'Passwords dont match');
            }
        }
    }

}
