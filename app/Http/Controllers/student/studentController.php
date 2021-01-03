<?php

namespace App\Http\Controllers\student;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\student_program;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\off_campus_mentor;
use App\program;
use App\Event;
use App\Student_event;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Helpers\LogActivity;

class studentController extends Controller {

//    public function get_dashboard($prg){
//        Session::put("program",$prg);
//        return view("student/student_deshboard");
//    }

    public function register_student(Request $request) {
        $p_type = $request->input('project_type');
        if ($p_type == "on_campus") {
            $validator = Validator::make($request->all(), [
                        'project_topic' => 'required|string',
                        'area_of_interest' => [
                            'required',
                            Rule::notIn(['n']),
                        ],
                        'mentor_1' => [
                            Rule::notIn(['n']),
                        ],
                        'project_type' => 'required',
                        'project_start_date' => 'required|date'
            ]);
            if ($validator->fails()) {
                return redirect('/consent_form')->withErrors($validator)->withInput();
            } else {
                $p_topic = $request->input('project_topic');
                $area_of_interest = $request->input('area_of_interest');
                $mentor_1 = $request->input('mentor_1');
                $mentor_2 = $request->input('mentor_2');
                $start_date = $request->input('project_start_date');

                $std = Auth::guard('student')->id();
                $program = program::where('name', Session::get('program'))->first()->id;
                $data = student_program::where(['student_id' => $std, 'program_id' => $program])->first();

                $data->project_topic = $p_topic;
                $data->project_type = $p_type;
                $data->mentor_1 = $mentor_1;
                $data->isRegistered = 1;
                $data->status = "pending";
                $data->area_of_interest = $area_of_interest;
                $data->project_start_date = $start_date;
                if ($mentor_2 != 'm2') {
                    $data->mentor_2 = $mentor_2;
                }
                $data->save();

                $m_1 = \App\Faculty::find($mentor_1);

                $data_1 = [
                    'mentor' => $m_1,
                    'std' => Auth::guard('student')->user(),
                    'program' => $data,
                ];
                Mail::send('emails.sendRequest', $data_1, function($m) use ($m_1) {
                    $m->subject("Project Mentor Request");
                    $m->to($m_1->email);
                });
                if ($mentor_2 != 'm2') {
                    $m_2 = \App\Faculty::find($mentor_2);
                    $data_2 = [
                        'mentor' => $m_2,
                        'std' => Auth::guard('student')->user(),
                        'program' => $data,
                    ];
                    Mail::send('emails.sendRequest', $data_2, function($m) use ($m_2) {
                        $m->subject("Project Mentor Request");
                        $m->to($m_2->email);
                    });
                }
                LogActivity::addToLog('Student submitted consent form');

                return redirect('/consent_form')->with('status', 'Form submitted Successfully');
            }
        } else {
            $validator = Validator::make($request->all(), [
                        'project_topic' => 'required|string',
                        'area_of_interest' => [
                            'required',
                            Rule::notIn(['n']),
                        ],
                        'mentor_1' => [
                            Rule::notIn(['n']),
                        ],
                        'project_type' => 'required',
                        'm_name' => 'required|string',
                        'contact_number' => 'required|alpha_num',
                        'email' => 'required|email',
                        'company_name' => 'required|string',
                        'project_duration' => 'required|numeric',
                        'project_start_date' => 'required|date'
            ]);

            if ($validator->fails()) {
                return redirect('/consent_form')->withErrors($validator)->withInput();
            } else {

                $mentor_name = $request->input('m_name');
                $contact_num = $request->input('contact_number');
                $email = $request->input('email');
                $company = $request->input('company_name');
                $duration = $request->input('project_duration');
                $start_date = $request->input('project_start_date');

                $off_campus = new off_campus_mentor;
                $off_campus->name = $mentor_name;
                $off_campus->contact_number = $contact_num;
                $off_campus->email = $email;
                $off_campus->company_name = $company;
                $off_campus->project_duration = $duration;
                $off_campus->save();

                $p_topic = $request->input('project_topic');
                $area_of_interest = $request->input('area_of_interest');
                $mentor_1 = $request->input('mentor_1');
                $mentor_2 = $request->input('mentor_2');


                $std = Auth::guard('student')->id();
                $program = program::where('name', Session::get('program'))->first()->id;
                $data = student_program::where(['student_id' => $std, 'program_id' => $program])->first();

                $data->project_topic = $p_topic;
                $data->project_type = $p_type;
                $data->mentor_1 = $mentor_1;
                $data->isRegistered = 1;
                $data->off_campus_mentor_id = $off_campus->id;
                $data->area_of_interest = $area_of_interest;
                $data->project_start_date = $start_date;
                $data->status = "pending";
                if ($mentor_2 != 'm2') {
                    $data->mentor_2 = $mentor_2;
                }
                $data->save();

                $m_1 = \App\Faculty::find($mentor_1);
                $data_1 = [
                    'mentor' => $m_1,
                    'std' => Auth::guard('student')->user(),
                    'program' => $data,
                ];
                Mail::send('emails.sendRequest', $data_1, function($m) use ($m_1) {
                    $m->subject("Project Mentor Request");
                    $m->to($m_1->email);
                });
                if ($mentor_2 != 'm2') {
                    $m_2 = \App\Faculty::find($mentor_2);
                    $data_2 = [
                        'mentor' => $m_2,
                        'std' => Auth::guard('student')->user(),
                        'program' => $data,
                    ];
                    Mail::send('emails.sendRequest', $data_2, function($m) use ($m_2) {
                        $m->subject("Project Mentor Request");
                        $m->to($m_2->email);
                    });
                }
                LogActivity::addToLog('Student submitted consent form');
                return redirect('/consent_form')->with('status', 'Data saved successfully');
            }
        }
    }

    public function get_form($prg) {
        $programs = Auth::guard("student")->user()->programs()->get();
        $cnt = 0;
        foreach ($programs as $p) {
            if ($prg == $p->name) {
                Session::put("program", $prg);
            } else {
                $cnt++;
            }
        }
        if ($cnt == sizeof($programs)) {
            return redirect("/")->withErrors("error");
        }
        Session::put("program", $prg);
        $std = Auth::guard('student')->id();
        $program = program::where('name', Session::get('program'))->first();
        $program_id = $program->id;
        $std = Auth::guard('student')->id();
        $std_prg = student_program::where(['student_id' => $std,"program_id" => $program_id])->first();
        $start_date = $std_prg->cf_start_date;
        $end_date = $std_prg->cf_end_date;
        $data = student_program::where(['student_id' => $std, 'program_id' => $program_id])->first();
        $faculty_id = \App\faculty_program::where('program_id', $program_id)->get();
        $faculties = array();
        foreach ($faculty_id as $f_id) {
            $faculties[] = \App\Faculty::find($f_id->faculty_id);
        }
        $aoi = \App\area_of_interest::all();
        $off_campus = off_campus_mentor::find($data->off_campus_mentor_id);
        return view('student/student_consent_form')->with([
                    'consent_data' => $data,
                    'username' => \App\Student::find($std)->username,
                    'ocm' => $off_campus,
                    'mentors' => $faculties,
                    'area_of_interest' => $aoi,
                    's_date' => $start_date,
                    'e_date' => $end_date
        ]);
    }

    public function get_view_project() {
        $std = Auth::guard('student')->id();
        $program = program::where('name', Session::get('program'))->first()->id;
        $phases = \App\Phase::where('program_id', $program)->get(['id']);
        $subphase = \App\Subphase::whereIn('phase_id', $phases)->get(["id"]);
        $std_subphase = \App\student_subphase::where('student_id', $std)
                        ->whereIn("subphase_id", $subphase)->get();

        $data = array();
        foreach ($std_subphase as $ss) {
            $tmp = array();
            $tmp["phase"] = $ss->subphase->phase->phase_name;
            $tmp["subphase"] = $ss->subphase->name;
            $tmp["std_sub"] = $ss;
            $data[] = $tmp;
        }
//        $s_events = Student_event::where('student_id', $std)->get();
//        $data = array();
//        $count = 1;
//        foreach ($s_events as $e) {
//            $tmp = array();
//            if ($e->event->subphase->phase->program->name == Session::get('program')) {
//                $tmp[] = $e->event->subphase->phase->phase_name;
//                $tmp[] = $e->event->subphase->name;
//                $tmp[] = $e->event->name;
//                $tmp[] = $e;
//
//                $data[$count] = $tmp;
//                $count = $count + 1;
//            }
//        }


        return view('student/project_details')->with('std_data', $data);
    }

    public function get_report($id, $s_id) {
        $s_events = Student_event::where(['student_id' => $s_id, 'event_id' => $id])->first();
        $file = $s_events->report_location;
        return response()->file(storage_path() . $file);
    }

    public function get_submission() {
        $std = Auth::guard('student')->id();
        $student = \App\Student::find($std);
        $s_events = Student_event::where('student_id', $std)->get();
        $data = array();
        $count = 1;
        date_default_timezone_set('Asia/Kolkata');
        foreach ($s_events as $e) {
            $tmp = array();
            if ($e->event->subphase->phase->program->name == Session::get('program') && $e->status != 'completed') {
                $startdate = strtotime($e->start_date);
                $enddate = strtotime($e->end_date);
                $date = strtotime(date('d-m-Y'));
                if ($startdate <= $date && $enddate >= $date && $e->report_location == null) {
                    $tmp[] = $e->event->subphase->phase->phase_name;
                    $tmp[] = $e->event->subphase->name;
                    $tmp[] = $e->event->name;
                    $tmp[] = $e->event->description;
                    $tmp[] = $e;
                  
                    $data[$count] = $tmp;
                    $count = $count + 1;
                }
            }
        }
        return view('student/submission')->with(['sub_data'=> $data,'student_id'=>$student->username]);
    }

    public function store_report(Request $request, $e_id) {
        $std = Auth::guard('student')->id();
        $student = \App\Student::find($std);
        $std_id = $student->username;
        $pr = program::where('name', Session::get('program'))->first()->id;
        $event = Student_event::where(['student_id' => $std, 'event_id' => $e_id])->first();
        $naming = $event->event->subphase->file_name;
        $extension = $event->event->subphase->file_extension;
        $needed_name = $std_id."_".$naming.".".$extension;
        $size = $event->event->subphase->file_size * 1024;
        $validator = Validator::make($request->all(), [
                    'report' => "required|mimes:$extension|file|max:$size",
        ]);
        if ($validator->fails()) {
            return redirect('/submission')->withErrors($validator);
        } else {
            if ($request->file('report')->isValid()) {
                $file = $request->file('report');
                $plag_file = $request->file('plag_report');

                if ($plag_file != null) {
                    $plag_name = $plag_file->getClientOriginalName();
                }


                $name = $file->getClientOriginalName();
                if($name!= $needed_name){
                    
                    return redirect()->back()->with("error","Name should be ". $needed_name);
                }
                $extension = $file->getClientOriginalExtension();

                $phase = $event->event->subphase->phase->phase_name;
                $subphase = $event->event->subphase->name;
                $e = $event->event->name;
                $program = $event->event->subphase->phase->program->name;
                if ($subphase == "final_subphase" && $e == "final_report_submission") {
                    $final_submission = \App\final_report::where([
                                "student_id" => $std,
                                "program_id" => $pr,
                            ])->first();
                    if ($final_submission != null) {
//                        $year = $event->event->subphase->phase->program->year;
                        $storage_path = "public/" . $program . "/" . $phase . "/" . $subphase . "/" . $e;
                        if ($plag_file != null) {
                            $plag_storage_path = "public/" . $program . "/" . $phase . "/" . $subphase . "/" . $e . "/" . "Plagiarism";
                        }

                        $path = $request->file('report')->storeAs(
                                $storage_path, $name);
                        if ($plag_file != null) {
                            $plag_path = $request->file('plag_report')->storeAs(
                                    $plag_storage_path, $plag_name);
                        }



                        $event->report_location = '/app/' . $path;
                        if ($plag_file != null) {
                            $event->plagiarism_report = '/app/' . $plag_path;
                        }

                        $event->status = "submitted";
                        $event->save();
//                File::makeDirectory('report', 0777, true, true);
                        $str = $phase . " " . $subphase . " " . $e;
                        LogActivity::addToLog('Student submitted report for ' . $str);
                        return redirect('/submission')->with('status', 'Report Submitted Successfully');
                    } else {
                        return redirect()->back()->with("error", "Please enter keywords before final submission");
                    }
                } else {
                    $year = $event->event->subphase->phase->program->year;
                    $storage_path = "public/" . $program . "_" . $year . "/" . $phase . "/" . $subphase . "/" . $e;
                    if ($plag_file != null) {
                        $plag_storage_path = "public/" . $program . "_" . $year . "/" . $phase . "/" . $subphase . "/" . $e . "/" . "Plagiarism";
                    }

                    $path = $request->file('report')->storeAs(
                            $storage_path, $name);
                    if ($plag_file != null) {
                        $plag_path = $request->file('plag_report')->storeAs(
                                $plag_storage_path, $plag_name);
                    }



                    $event->report_location = '/app/' . $path;
                    if ($plag_file != null) {
                        $event->plagiarism_report = '/app/' . $plag_path;
                    }

                    $event->status = "submitted";
                    $event->save();
//                File::makeDirectory('report', 0777, true, true);
                    $str = $phase . " " . $subphase . " " . $e;
                    LogActivity::addToLog('Student submitted report for ' . $str);
                    return redirect('/submission')->with('status', 'Report Submitted Successfully');
                }
            } else {
                
            }
        }
    }

    public function get_edit_off_campus_details() {
        $std = Auth::guard('student')->id();
        $program = program::where('name', Session::get('program'))->first()->id;

        $ocm_id = student_program::where(['student_id' => $std, 'program_id' => $program])
                        ->where('status', '!=', 'completed')
                        ->first()->off_campus_mentor_id;
        $off_data = null;
        if ($ocm_id == 0) {
            $off_data = null;
        } else {
            $off_data = off_campus_mentor::find($ocm_id);

            //dd($off_data);
        }
        return view('student/student_edit_off_campus')->with(['off_data' => $off_data, 'ocm_id' => $ocm_id]);
    }

    public function edit_off_details(Request $request) {
        $std = Auth::guard('student')->id();
        $validator = Validator::make($request->all(), [
                    'ocm_name' => 'required|string',
                    'ocm_number' => 'required',
                    'ocm_company' => 'required|string',
                    'ocm_email' => 'required|email'
        ]);
        if ($validator->fails()) {
            return redirect('/get_edit_off_campus')->withErrors($validator);
        } else {
            $off_name = $request->input('ocm_name');
            $off_number = $request->input('ocm_number');
            $off_company = $request->input('ocm_company');
            $off_email = $request->input('ocm_email');
            $off_id = $request->input('ocm_id');

            $off_data = off_campus_mentor::find($off_id);

            $off_data->name = $off_name;
            $off_data->contact_number = $off_number;
            $off_data->company_name = $off_company;
            $off_data->email = $off_email;
            $off_data->save();
            LogActivity::addToLog('Student edited off campus details');
            return redirect('/get_edit_off_campus')->with('status', 'Data edited successfully');
        }
    }

    public function get_std_sub_report($std_sub_id) {
//        $sub_std_id = $request->input('sub_std_id');
        $std = Auth::guard('student')->id();
        $sub_std = \App\student_subphase::find($std_sub_id);
        if ($std == $sub_std->student_id) {
            $events = Event::where(['subphase_id' => $sub_std->subphase_id, 'submission' => 1])->get();

            $event_show = array();
            foreach ($events as $event) {
                array_push($event_show, Student_event::where(['event_id' => $event->id, 'student_id' => $sub_std->student_id])->get());
            }

            return view('faculty/faculty_view_report')->with([
                        'event_show' => $event_show,
                        'std_id' => \App\Student::find($sub_std->student_id)->username,
                        'phase_name' => $sub_std->subphase->phase->phase_name,
                        'subphase_name' => $sub_std->subphase->name,
                    ])->render();
        } else {
            return redirect('view_project');
        }
    }

    public function final_keywords(Request $request) {
        $std = Auth::guard('student')->id();
        $std_event = Student_event::find($request->input('s_event'));
        $std_event->keywords_given = 1;
        $std_event->save();
        $program = program::where('name', Session::get('program'))->first()->id;
        $keywords = $request->input('keywords');
        $final_report = \App\final_report::where([
                    'student_id' => $std,
                    'program_id' => $program
                ])->first();
        if ($final_report == null) {
            $f_r = new \App\final_report();
            $f_r->student_id = $std;
            $f_r->program_id = $program;
            $f_r->keywords = $keywords;
            $f_r->save();
        } else {
            $final_report->keywords = $keywords;
            $final_report->save();
        }
        LogActivity::addToLog('Student added keywords for final report for ' . $program);
        return "true";
    }

}
