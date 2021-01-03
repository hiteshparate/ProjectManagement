<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\coordinator;
use App\program;
use App\Classes\uspmes;
use App\Jobs\sendMail;
use App\Helpers\LogActivity;

class adminController extends Controller {

    public function get_admin_add_prg() {
        $faculty = \App\Faculty::all();
        $grades = \App\grading_system::all();
        return view('admin/admin_add_to_program')->with([
                    'faculty' => $faculty,
                    'grades' => $grades]);
    }

    public function add_new_program(Request $request) {
        $validator = Validator::make($request->all(), [
                    'p_name' => 'required',
                    'c_name' => 'required',
                    'c_mail' => 'required|email',
                    'faculty_name' => [
                        Rule::notIn(['n']),
                    ],
                    'committee_formation' => 'required',
                    'grading_type' => [
                        Rule::notIn(['n']),
                    ],
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $prg_name = $request->input('p_name');
            $co_username = $request->input('c_name');
            $co_email = $request->input('c_mail');
            $faculty_id = $request->input('faculty_name');
            $committee_formation = $request->input('committee_formation');
            $grading_type = $request->input('grading_type');

            $coordinator = new coordinator();
            $coordinator->username = $co_username;
            $coordinator->password = "0000000000";
            $coordinator->email = $co_email;
            $coordinator->faculty_id = $faculty_id;
            $coordinator->save();

            $coordinator_id = $coordinator->id;
            $program = new program();
            $program->name = $prg_name;
            $program->committee_formation_type = $committee_formation;
            $program->grading_system = $grading_type;
            $program->coordinator_id = $coordinator_id;
            $program->save();

            $phase = new \App\Phase();
            $phase->phase_name = "final_phase";
            $phase->program_id = $program->id;
            $phase->save();

            $subphase = new \App\Subphase();
            $subphase->name = "final_subphase";
            $subphase->code = "final_subphase";
            $subphase->g_id = $grading_type;
            $subphase->evaluation_committee = 1;
            $subphase->submission = 1;
            $subphase->phase_id = $phase->id;
            $subphase->save();

            $event = new \App\Event();
            $event->name = "final_report_submission";
            $event->description = "This is program's final submission";
            $event->subphase_id = $subphase->id;
            $event->submission = 1;
            $event->mail = 0;
            $event->save();


            $uspmes = new uspmes();
            $uspmes->sync_coordinator_password($program->id, $faculty_id);
            LogActivity::addToLog("Admin has created new program named " . $prg_name);
            return redirect()->back()->with('status', 'Program has been created successfully');
        }
    }

    public function admin_change_coordinator() {
        $program = program::all();
        $faculty = \App\Faculty::all();
        return view('admin/admin_change_coordinator')->with([
                    'program' => $program,
                    'faculty' => $faculty
        ]);
    }

    public function change_coordinator(Request $request) {
        $validator = Validator::make($request->all(), [
                    'prg_name' => [
                        Rule::notIn(['n']),
                    ],
                    'faculty_name' => [
                        Rule::notIn(['n']),
                    ],
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput();
        } else {
            $prg_id = $request->input('prg_name');
            $faculty_id = $request->input('faculty_name');
            $co_id = program::find($prg_id)->coordinator_id;
            $coordinator = coordinator::find($co_id);
            $coordinator->faculty_id = $faculty_id;
            $uspmes = new uspmes();
            $uspmes->sync_coordinator_password($prg_id, $faculty_id);
            $coordinator->save();
            LogActivity::addToLog("Admin has changed coordinator of program " . program::find($prg_id)->name . " new coordinator is " . \App\Faculty::find($faculty_id)->name);
            return redirect()->back()->with('status', 'Coordinator has been successfully changed');
        }
    }

    public function view_program() {
        $programs = program::all();
        return view('admin/admin_view_prg')->with('programs', $programs);
    }

    public function get_admin_manage_grades() {
        $grading_sys = \App\grading_system::all();
        $data = array();
        foreach ($grading_sys as $g) {
            $tmp = \App\grade::where('g_id', $g->id)->get();
            $data[] = [
                'grading_system' => $g,
                'grades' => $tmp,
            ];
        }
        return view('admin/admin_manage_grades')->with([
                    'grade' => $data,
        ]);
    }

    public function add_new_grading_system(Request $request) {
        $temp = 0;
        $grade_num = sizeof($request->all()) - 3;

        $validate_array = ['grade_name' => 'required'];
        for ($x = 1; $x <= $grade_num; $x++) {
            $validate_array['grade_' . $x] = 'required';
        }
        $temp = $this->validate($request, $validate_array);
        $grade_name = $request->input('grade_name');
        $grading_system = new \App\grading_system();
        $grading_system->name = $grade_name;
        $grading_system->save();
        $g_id = $grading_system->id;
        for ($x = 1; $x <= $grade_num; $x++) {
            $gr = new \App\grade();
            $gr->g_id = $g_id;
            $gr->type = $request->input('grade_' . $x);
            $gr->save();
        }
        LogActivity::addToLog("Admin has added new grading system named " . $grade_name);
        return redirect()->back()->with('status', 'New grading system has been Added');
    }

    public function get_admin_faculties() {
        $faculty = \App\Faculty::all();
        return view('admin/admin_manage_faculties')->with([
                    'faculty' => $faculty
        ]);
    }

    public function add_faculty(Request $request) {
        $validator = Validator::make($request->all(), [
                    'add_fac_prog_csv' => 'required|mimes:csv,txt|file',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        } else {
            $file = $request->file('add_fac_prog_csv');
            $header = NULL;
            $data = array();
            $faculty = fopen($file, "r");
            while (($row = fgetcsv($faculty, ",")) !== FALSE) {
                if (!$header)
                    $header = $row;
                else{
                    if($header[0] == "faculty_id" && $header[1] == "name" && $header[2] == "email"){
                        $data[] = array_combine($header, $row);
                    }else{
                        return redirect()->back()->with("error","Please verify CSV file");
                    }                    
                }                    
            }
            $added_faculty = 0;
            foreach ($data as $d) {
                $faculty = \App\Faculty::where("username", $d["faculty_id"])->first();
                if ($faculty != null) {
                    
                } else {
                    $faculty = new \App\Faculty();
                    $faculty->username = $d["faculty_id"];
                    $faculty->name = $d["name"];
                    $faculty->email = $d["email"];
                    $password = str_random(10);
                    $faculty->password = bcrypt($password);
                    $faculty->save();

                    $email = $faculty->email;
                    $name = $d["name"];
                    $data = [
                        'faculty_id' => $d["faculty_id"],
                        'password' => $password,
                        'faculty' => $name,
                    ];
                    $added_faculty++;
                    sendMail::dispatch($email, "Uspmes Profile created", $data, 'emails.createProfileFaculty',null,null);
                    LogActivity::addToLog("Amin has added new faculty : ".$d["name"]);
                }
            }
            return redirect()->back()->with('status', $added_faculty.' Faculty have been added to the System successfully');
        }
    }

    public function get_admin_manage_aoi() {
        $aoi = \App\area_of_interest::all();
        return view('admin/admin_manage_aoi')->with([
                    'area_of_interest' => $aoi,
        ]);
    }

    public function delete_aoi(Request $request) {
        $aoi_id = $request->input('id');
        $aoi = \App\area_of_interest::find($aoi_id);
        $aoi->delete();
        LogActivity::addToLog("Admin had deleted ".$aoi->name." area of interest");
        return "true";
    }

    public function add_new_aoi(Request $request) {
        $aoi_num = sizeof($request->all()) - 1;
        $validate_array = array();
        for ($x = 1; $x <= $aoi_num; $x++) {
            $validate_array['aoi_' . $x] = 'required';
        }
        $temp = $this->validate($request, $validate_array);
        for ($x = 1; $x <= $aoi_num; $x++) {
            $aoi = new \App\area_of_interest();
            $aoi->name = $request->input('aoi_' . $x);

            $aoi->save();
            LogActivity::addToLog("Admin had added new area of interest named ".$aoi->name);
        }

        return redirect()->back()->with('status', 'New Area Of Interests has been Added to the system');
    }
    public function get_reports(Request $request) {
       $validator = Validator::make($request->all(), [
                   'program_select' => [
                       'required',
                       Rule::notIn(['n']),
                   ],
       ]);
       if ($validator->fails()) {
           return redirect()->back()->withErrors($validator);
       } else {
           $program = $request->input("program_select");
           $students = \App\final_report::where(["program_id" => $program])->get();
           $data = array();
           foreach ($students as $s) {
               $tmp = array();
               $tmp["report"] = $s;
               $tmp["student"] = \App\student_program::where(["student_id" => $s->student_id, "program_id" => $s->program_id])->first();
               $data[] = $tmp;
           }
           return view("admin/admin_reports")->with("data", $data);
       }
   }

   public function bring_report($id) {
       $report = \App\final_report::find($id);
       return response()->file(storage_path() . $report->report_location);
   }

   public function get_view_reports() {
       $programs = \App\program::all();
       return view('admin/admin_view_reports')->with("programs", $programs);
   }

    public function logActivity() {
        $logs = LogActivity::logActivityLists();
        return view('logActivity', compact('logs'));
    }

}
