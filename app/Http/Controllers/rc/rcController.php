<?php

namespace App\Http\Controllers\rc;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\resource_centre;
use App\Jobs\sendMail;

class rcController extends Controller {

    public function get_reports(Request $request) {
        $validator = Validator::make($request->all(), [
                    'program_select' => [
                        'required',
                        Rule::notIn(['n']),
                    ],
        ]);
        if($validator->fails()){
            return redirect()->back()->withErrors($validator);
        }else{
            $program = $request->input("program_select");
            $students = \App\final_report::where(["program_id" => $program])->get();
            $data = array();
            foreach($students as $s){
                $tmp = array();
                $tmp["report"] = $s;
                $tmp["student"] = \App\student_program::where(["student_id" => $s->student_id,"program_id" => $s->program_id])->first();
                $data[] = $tmp;
            }
            return view("rc/rc_reports")->with("data",$data);
        }
    }
    
    public function bring_report($id){
        $report = \App\final_report::find($id);
        return response()->file(storage_path() .$report->report_location);
    }

    public function get_add_another_login() {
        $rc_users = resource_centre::all();
        return view('rc/rc_manage_users')->with([
            'rc_users'=>$rc_users,
            ]);
    }
    
    public function add_rc_user(Request $request) {
        $validator = Validator::make($request->all(), [
                    'add_rc_csv' => 'required|mimes:csv,txt|file',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        } else {
            $file = $request->file('add_rc_csv');
            $header = NULL;
            $data = array();
            $rc = fopen($file, "r");
            while (($row = fgetcsv($rc, ",")) !== FALSE) {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            
            foreach($data as $d){
                $rc = new resource_centre();
                $rc->username = $d["user_id"];
                $rc->name  = $d["name"];
                $rc->email = $d["email"];
                $password = str_random(10);
                $rc->password = bcrypt($password);
                $rc->save();
                
                $email = $rc->email;
                    $name = $d["name"];
                    $data = [
                        'user_id' => $d["user_id"],
                        'password' => $password,
                        'user' => $name,
                    ];
                 sendMail::dispatch($email,"Uspmes Profile created",$data,'emails.createProfileRCuser',null,null);
            }
             return redirect()->back()->with('status', 'Users have been added to the System successfully');
     
        }
        
    }
    public function delete_user(Request $request) {
        $user_id= $request->input('user_id');
        $rc = resource_centre::find($user_id);
        $rc->delete();
        return "true";
    }
}
