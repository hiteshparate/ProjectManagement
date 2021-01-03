<?php

namespace App\Http\Controllers\coordinator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\coordinator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\program;
use App\Phase;
use Illuminate\Support\Facades\Input;
use App\Subphase;
use Illuminate\Validation\Rule;
use App\Event;
use App\grading_system;
use App\Student;
use App\student_program;
use Illuminate\Support\Facades\Mail;
use App\student_subphase;
use App\Student_event;
use App\Faculty;
use App\committee;
use App\committee_faculty;
use Illuminate\Support\Facades\File;
use App\email_template;
use App\Jobs\sendMail;
use App\Helpers\LogActivity;
use App\Jobs\sendMail_coordinator;

class coordinatorController extends Controller {

    public function get_phase() {
        LogActivity::addToLog('Enter in phase');
        $coord_id = Auth::guard('coordinator')->id();
        $prg = program::where('coordinator_id', $coord_id)->first();
        $phase = Phase::where('program_id', $prg->id)->get();
        return view('coordinator/coordinator_phase')->with(['phase' => $phase, 'prg' => $prg->name]);
    }

    public function get_subphase() {
        $coord_id = Auth::guard('coordinator')->id();
        $prg = program::where('coordinator_id', $coord_id)->first();
        $phase = Phase::where('program_id', $prg->id)->get();
        $grade = grading_system::all();
        $subphase[] = array();
        foreach ($phase as $p) {
            $subphase[] = Subphase::where('phase_id', $p->id)->get();
        }
        return view('coordinator/coordinator_subphase')->with(['phases' => $phase, 'grade' => $grade, 'subphase' => $subphase, 'prg_name' => $prg->name]);
    }

    public function get_event() {
        $coord_id = Auth::guard('coordinator')->id();
        $prg = program::where('coordinator_id', $coord_id)->first();
        $phase = Phase::where('program_id', $prg->id)->get();


        return view('coordinator/coordinator_event')->with(['phase' => $phase, 'prg_name' => $prg->name]);
    }

    public function add_phase(Request $request) {
        $validator = Validator::make($request->all(), [
                    'phase' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        } else {
            $phase_name = $request->input('phase');
            $coord_id = Auth::guard('coordinator')->id();
            $prg = program::where('coordinator_id', $coord_id)->first();
            $phase = Phase::where(['phase_name' => $phase_name, 'program_id' => $prg->id])->first();
            if ($phase == null) {
                $phase = new phase();
                $phase->program_id = $prg->id;
                $phase->phase_name = $phase_name;
                $phase->save();
                $prg->no_of_phase = $prg->no_of_phase + 1;
                $prg->save();
                LogActivity::addToLog('Coordinator has added a phase ' . $phase_name . ' in program ' . $prg->name);
                return redirect("/subphase")->with('status', 'Phase created successfully. Now you can add subphase.');
            } else {
                return redirect()->back()->with('error', 'Entered phase name is already exists.Please enter different phase name.');
            }
        }
    }

    public function add_subphase(Request $request) {
        $validator = Validator::make($request->all(), [
                    'phase_select' => [
                        'required',
                        Rule::notIn(['n']),
                    ],
                    'subphase_name' => 'required',
                    'subphase_code' => 'required',
                    'grading_type' => [
                        'required',
                        Rule::notIn(['n']),
                    ],
                    'committee' => 'required',
                    'submission' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        } else {
            $coord_id = Auth::guard('coordinator')->id();
            $prg = program::where('coordinator_id', $coord_id)->first();
            $phase_id = $request->input('phase_select');
            $code = $request->input('subphase_code');
            $name = $request->input('subphase_name');
            $subphase = Subphase::where(['phase_id' => $phase_id, 'name' => $name])->first();
            if ($subphase == null) {

                $g_id = $request->input('grading_type');
                $evaluation_committee = $request->input('committee');
                $submission = $request->input('submission');
                $file_name = "";
                $file_ext = "";
                $file_size = "";
                if ($submission == '1') {
                    $file_name = $request->input('file_name');
                    $file_ext = $request->input('file_ext');
                    $file_size = $request->input('file_size');
                }
                $subphase = new Subphase();
                $subphase->phase_id = $phase_id;
                $subphase->name = $name;
                $subphase->code = $code;
                $subphase->g_id = $g_id;
                $subphase->evaluation_committee = $evaluation_committee;
                $subphase->submission = $submission;
                if ($file_name != null) {
                    $subphase->file_name = $file_name;
                }
                if ($file_ext != null) {
                    $subphase->file_extension = $file_ext;
                }
                if ($file_size != null) {
                    $subphase->file_size = $file_size;
                }
                $subphase->save();
                LogActivity::addToLog('Coordinator has added a subphase ' . $name . ' in phase ' . Phase::find($phase_id)->phase_name . ' in program ' . $prg->name);
                return redirect('/event')->with('status', 'SubPhase created successfully. Now you can add events and add students to subphase.');
            } else {
                return redirect()->back()->with('error', 'Entered subphase name is already exists.Please enter different subphase name.');
            }
        }
    }

    public function add_event(Request $request) {
        $validator = Validator::make($request->all(), [
                    'phase_select' => [
                        'required',
                        Rule::notIn(['n']),
                    ],
                    'subphase_select' => [
                        'required',
                        Rule::notIn(['n']),
                    ],
                    'event_name' => 'required',
                    'event_des' => 'required',
                    'submission' => 'required',
                    'send_mail' => 'required',
                    'start_date' => 'required|date',
                    'end_date' => 'required|date',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        } else {
            $prg_id = Session::get('coord_prg');
            $name = $request->input('event_name');
            $subphase_id = $request->input('subphase_select');
            $evnt = Event::where(["subphase_id" => $subphase_id, "name" => $name])->first();
            if ($evnt == null) {
                $description = $request->input('event_des');
                $submission = $request->input('submission');
                $mail = $request->input('send_mail');
                $start_date = $request->input('start_date');
                $end_date = $request->input('end_date');

                $event = new Event();
                $event->name = $name;
                $event->description = $description;
                $event->submission = $submission;
                $event->mail = $mail;
                $event->subphase_id = $subphase_id;
                $event->start_date = $start_date;
                $event->end_date = $end_date;
                $event->save();

                $subphase_stds = student_subphase::where(["subphase_id" => $subphase_id])
                        ->where("status", "!=", "completed")
                        ->get();
                if ($subphase_stds != null) {
                    foreach ($subphase_stds as $std) {
                        $std_event = new \App\Student_event();
                        $std_event->event_id = $event->id;
                        $std_event->student_id = $std->student_id;
                        $std_event->program_id = $prg_id;
                        $std_event->status = "pending";
                        $std_event->start_date = $start_date;
                        $std_event->end_date = $end_date;
                        $std_event->save();
                    }
                }
                LogActivity::addToLog('Coordinator has added an event ' . $name . ' in phase ' . $event->subphase->phase->phase_name . ' in subphase ' . $event->subphase->name . 'in program ' . $event->subphase->phase->program->name);
                return redirect()->back()->with('status', 'event created successfully.');
            } else {
                return redirect()->back()->with('error', 'Entered event name is already exists.Please enter different event name.');
            }
        }
    }

    public function get_phase_option(Request $request) {
        $phase_id = $request->input('phase_id');
        $subphase = Subphase::where('phase_id', $phase_id)->get();
        return response()->json(json_decode(json_encode($subphase)));
    }

    public function get_subphase_option(Request $request) {
        $subphase_id = $request->input('subphase_id');
        $event = Event::where('subphase_id', $subphase_id)->get();
        return response()->json(json_decode(json_encode($event)));
    }

    public function edit_subphase(Request $request) {
        $subphase_id = $request->input('subphase_id');
        $subphase_details = Subphase::find($subphase_id);
        return response()->json(json_decode(json_encode($subphase_details)));
    }

    public function edit_event(Request $request) {
        $event_id = $request->input('event_id');
        $event_details = Event::find($event_id);
        return response()->json(json_decode(json_encode($event_details)));
    }

    public function edit_save_subphase(Request $request) {
        $sub_id = $request->input('sub_id');
        $subphase = Subphase::find($sub_id);
        $subphase->name = $request->input('subphase_name');
        $subphase->code = $request->input('subphase_code');
        $subphase->g_id = $request->input('grading_type');
        $subphase->evaluation_committee = $request->input('committee');
        $subphase->submission = $request->input('submission');
        $file_name = "";
        $file_ext = "";
        $file_size = "";
        if ($request->input('submission') == '1') {
            $file_name = $request->input('file_name');
            $file_ext = $request->input('file_ext');
            $file_size = $request->input('file_size');
        }
        if ($file_name != null) {
            $subphase->file_name = $file_name;
        }
        if ($file_ext != null) {
            $subphase->file_extension = $file_ext;
        }
        if ($file_size != null) {
            $subphase->file_size = $file_size;
        }
        $subphase->save();
        LogActivity::addToLog('Coordinator has modified details of subphase ' . $request->input('subphase_name') . 'in program ' . $subphase->phase->program->name);
        return redirect()->back()->with('status', 'subphase details has been edited successfully');
    }

    public function edit_save_event(Request $request) {
        $event_id = $request->input('e_id');
        $event = Event::find($event_id);
        $event->name = $request->input('event_name');
        $event->description = $request->input('event_des');
        $event->submission = $request->input('submission');
        $event->mail = $request->input('send_mail');
        $event->start_date = $request->input('start_date');
        $event->end_date = $request->input('end_date');
        $event->save();

        $students = Student_event::where(["event_id" => $event_id, "status" => "pending"])->get();
        foreach ($students as $std) {
            $std->start_date = $event->start_date;
            $std->end_date = $event->end_date;
            $std->save();
        }

        LogActivity::addToLog('Coordinator has modified details of event ' . $request->input('event_name') . ' in phase ' . $event->subphase->phase->phase_name . ' in subphase ' . $event->subphase->name . ' in program ' . $event->subphase->phase->program->name);
        return redirect()->back()->with('status', 'event details has been edited successfully');
    }

    public function edit_phase(Request $request) {
        $phase_id = $request->input('phase_id');
        $phase_details = Phase::find($phase_id);
        return response()->json(json_decode(json_encode($phase_details)));
    }

    public function edit_save_phase(Request $request) {
        $phase_id = $request->input('phase_id');
        // dd($phase_id);
        $phase = Phase::find($phase_id);
        //  dd($phase);
        $phase->phase_name = $request->input('edit_phase_name');
        $phase->save();
        LogActivity::addToLog("Coordinator has modified details of phase " . $phase->phase_name . " in program " . $phase->program->name);
        return redirect()->back()->with('status', 'phase has been edited successfully');
    }

    public function add_student_to_programme() {
        $prg_id = Session::get('coord_prg');
        $std_prg = student_program::where('program_id', $prg_id)
                        ->where('status', '!=', 'compelted')->get();
        $students = array();
        foreach ($std_prg as $s) {
            $students[] = Student::find($s->student_id);
        }
        return view('coordinator/coordinator_add_student_to_programme')->with([
                    'students' => $students,
        ]);
    }

    public function add_student_prg(Request $request) {
        $validator = Validator::make($request->all(), [
                    'add_prog_csv' => 'required|mimes:csv,txt|file',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        } else {
            $coord_id = Auth::guard('coordinator')->id();
            $prg = program::where('coordinator_id', $coord_id)->first();
            $prg_id = $prg->id;
            $file = $request->file('add_prog_csv');
            $header = NULL;
            $data = array();
            $std = fopen($file, "r");
            while (($row = fgetcsv($std, ",")) !== FALSE) {
                if (!$header)
                    $header = $row;
                else {
                    if ($header[0] == "student_id" && $header[1] == "name" && $header[2] == "email") {
                        $data[] = array_combine($header, $row);
                    } else {
                        return redirect()->back()->with("error", "Please verify CSV file.");
                    }
                }
            }
            $added_std = 0;
            foreach ($data as $d) {

                $std = Student::where('username', $d["student_id"])->first();

                if ($std == null) {
                    $password = str_random(10);
                    $s = new Student();
                    $s->name = $d["name"];
                    $s->username = $d["student_id"];
                    $s->email = $d["email"];
                    $s->password = bcrypt($password);
                    $s->save();

                    $email = $s->email;
                    $name = $d["name"];
                    $data = [
                        'std_id' => $d["student_id"],
                        'password' => $password,
                        'student' => $name,
                    ];
                    sendMail::dispatch($email, "Uspmes Profile created", $data, 'emails.createProfile', null, null);
//                    Mail::send('emails.createProfile', $data, function($m) use ($email) {
//                        $m->subject("Uspmes Profile created");
//                        $m->to($email);
//                    });
                    LogActivity::addToLog("Coordinator has modified added students in program " . $prg->name);
                    $added_std += $this->add_std_prg_helper($s->id, $prg_id, $prg->name, $s->name, $email);
                } else {
                    $added_std += $this->add_std_prg_helper($std->id, $prg_id, $prg->name, $std->name, $std->email);
                }
            }
            return redirect()->back()->with('status', $added_std . ' Students have been added to the program successfully');
        }
    }

    public function add_std_prg_helper($id, $prg_id, $prg_name, $s_name, $email) {
        $coord = coordinator::find(Auth::guard('coordinator')->id());
        $std = student_program::where(['student_id' => $id, 'program_id' => $prg_id])->first();
        if ($std == null) {
            $std = new student_program();
            $std->student_id = $id;
            $std->program_id = $prg_id;
            $std->save();
        } else {
            $std->project_topic = null;
            $std->area_of_interest = null;
            $std->mentor_1 = 0;
            $std->mentor_2 = null;
            $std->project_type = null;
            $std->status = "a";
            $std->isRegistered = 0;
            $std->off_campus_mentor_id = 0;
            $std->save();
        }
        $data = [
            'prg' => $prg_name,
            'student' => $s_name,
        ];
        sendMail::dispatch($email, "Added to the program", $data, 'emails.addedToProgram', $coord->username, $coord->email);
        return 1;
//        Mail::send('emails.addedToProgram', $data, function($m) use ($email) {
//            $m->subject("Added to the program");
//            $m->to($email);
//        });
    }

    public function get_all_student() {
        $prg_id = Session::get('coord_prg');
        $area_of_interest = \App\area_of_interest::all();
        $students = student_program::where(['program_id' => $prg_id])
                ->where('status', '!=', 'completed')
                ->get();
        return view('coordinator/coordinator_all_student')->with(['students' => $students,
                    'area_of_interest' => $area_of_interest,
        ]);
    }

    public function get_std_to_subphase() {
        $prg_id = Session::get('coord_prg');
        $phase = Phase::where('program_id', $prg_id)->get();
        $student = student_program::where(['program_id' => $prg_id])
                ->where('status', '!=', 'completed')
                ->get();
        $allstd = array();
        foreach ($student as $s) {
            $allstd[] = Student::find($s->student_id);
        }
//        dd($std);
        return view('coordinator/coordinator_add_student_to_subphase')->with(['phases' => $phase, 'student' => $allstd]);
    }

    public function add_std_to_subphase(Request $request) {
        $validator = Validator::make($request->all(), [
                    'phase_choose' => [
                        Rule::notIn(['n']),
                    ],
                    'subphase_choose' => [
                        Rule::notIn(['n']),
                    ],
                    'to' => 'required',
                        ], [
                    'required' => "Please select at least one student to add"
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        } else {
            $prg_id = Session::get('coord_prg');
            $prg_name = program::find($prg_id)->name;
            $phase = $request->input('phase_choose');
            $subphase = $request->input('subphase_choose');
            $subp_name = Subphase::find($subphase)->name;
            $students = $request->input('to');
            $this->add_std_subphase_helper($students, $subphase, $phase);
            $events = Event::where(["subphase_id" => $subphase])->get();
            foreach ($events as $e) {
                if ($e->mail == 1) {
                    foreach ($students as $std) {
                        $std_evnt = \App\Student_event::where(["student_id" => $std, "event_id" => $e->id])
                                ->where("status", "!=", "completed")
                                ->first();
                        if ($std_evnt != null) {
                            $std_evnt->status = "pending";
                            $std_evnt->report_location = "";
                            $std_evnt->isAccepted = 0;
                            $std_evnt->save();
                        } else {
                            $std_evnt = new \App\Student_event();
                            $std_evnt->student_id = $std;
                            $std_evnt->event_id = $e->id;
                            $std_evnt->program_id = $prg_id;
                            $std_evnt->status = "pending";
                            $std_evnt->start_date = $e->start_date;
                            $std_evnt->end_date = $e->end_date;
                            $std_evnt->save();
                        }
                        $email = Student::find($std)->email;
                        $data = [
                            "student" => Student::find($std)->name,
                            "event" => $e->name,
                            "program" => $prg_name,
                            "subphase" => $subp_name,
                        ];
                        $coo = coordinator::find(Auth::guard('coordinator')->id());
                        sendMail::dispatch($email, "Added to the new event", $data, "emails.addStudentToEvent", $coo->username, $coo->email);
                    }
                } else {
                    foreach ($students as $std) {
                        $std_evnt = \App\Student_event::where(["student_id" => $std, "event_id" => $e->id])
                                ->where("status", "!=", "completed")
                                ->first();
                        if ($std_evnt != null) {
                            $std_evnt->status = "pending";
                            $std_evnt->report_location = "";
                            $std_evnt->isAccepted = 0;
                            $std_evnt->save();
                        } else {
                            $std_evnt = new \App\Student_event();
                            $std_evnt->student_id = $std;
                            $std_evnt->event_id = $e->id;
                            $std_evnt->program_id = $prg_id;
                            $std_evnt->status = "pending";
                            $std_evnt->start_date = $e->start_date;
                            $std_evnt->end_date = $e->end_date;
                            $std_evnt->save();
                        }
                    }
                }
            }
            LogActivity::addToLog("Coordinator has added students to subphase " . $subp_name . " in program " . $prg_name);
            return redirect()->back()->with('status', 'Students added to subphase successfully');
        }
    }

    public function add_std_subphase_helper($students, $subphase, $phase) {
        $prg_id = Session::get('coord_prg');
        $prg_name = program::find($prg_id)->name;
        foreach ($students as $student) {
            $std_sub = student_subphase::where([
                        'student_id' => $student,
                        'subphase_id' => $subphase,
                    ])->first();
            if ($std_sub != null) {
                if ($std_sub->status == "completed") {
                    $std_sub->final_grade = null;
                    $std_sub->comment = null;
                    $std_sub->status = "added";
                    //HERE WE NEED TO EDIT GRADE PART FOR REPEITER IN MENTOR SIDE
                    $std_sub->save();
                }
            } else {
                $std_sub = new student_subphase();
                $std_sub->student_id = $student;
                $std_sub->subphase_id = $subphase;
                $std_sub->status = "added";
                $std_sub->save();
            }
            $std = Student::where('id', $student)->first();
            // dd($std->email);
            $subp = Subphase::find($std_sub->subphase_id);
            $subphase_name = $subp->name;
            $subphase_code = $subp->code;
            $email = $std->email;
            $std_name = $std->name;
            //  dd($subphase_name ." " . $subphase_code);
            $data = [
                'subphase_code' => $subphase_code,
                'subphase_name' => $subphase_name,
                'student_name' => $std_name,
                'program' => $prg_name,
            ];
            $coo = coordinator::find(Auth::guard('coordinator')->id());
            sendMail::dispatch($email, "Added to the subphase", $data, 'emails.addedToSubphase', $coo->username, $coo->email);
//            Mail::send('emails.addedToSubphase', $data, function($m) use ($email) {
//                $m->subject("Added to the subphase");
//                $m->to($email);
//            });
        }
    }

    public function add_std_to_subphase_csv(Request $request) {
        $validator = Validator::make($request->all(), [
                    'phase_choose' => [
                        Rule::notIn(['n']),
                    ],
                    'subphase_choose' => [
                        Rule::notIn(['n']),
                    ],
                    'add_subphase_csv' => 'required',
                        ], [
                    'required' => "Please attach csv file"
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        } else {
            $prg_id = Session::get('coord_prg');
            $prg_name = program::find($prg_id)->name;
            $file = $request->file('add_subphase_csv');
            $phase = $request->input('phase_choose');
            $subphase = $request->input('subphase_choose');
            $header = NULL;
            $students = array();
            $std = fopen($file, "r");
            while (($row = fgetcsv($std, ",")) !== FALSE) {
                if (!$header)
                    $header = $row;
                else
                    $students[] = array_combine($header, $row);
            }

            $not_added_std = array();
            $added_std = array();
            foreach ($students as $student) {
                foreach ($student as $key => $value) {
                    $s = Student::where('username', $value)->first();
                    if ($s != null) {
                        $std_id = $s->id;
                        $std_prg = student_program::where(["student_id" => $std_id, "program_id" => $prg_id])->first();
                        if ($std_prg != null) {
                            $std_sub = student_subphase::where([
                                        'student_id' => $std_id,
                                        'subphase_id' => $subphase,
                                    ])->first();
                            if ($std_sub != null) {
                                if ($std_sub->status == "completed") {
                                    $std_sub->final_grade = null;
                                    $std_sub->comment = null;
                                    $std_sub->status = "added";
                                    //HERE WE NEED TO EDIT GRADE PART FOR REPEITER IN MENTOR SIDE
                                    $std_sub->save();
                                }
                            } else {
                                $std_sub = new student_subphase();
                                $std_sub->student_id = $std_id;
                                $std_sub->subphase_id = $subphase;
                                $std_sub->status = "added";
                                $std_sub->save();
                            }
                            //SEND MAIL TO ALL ADDED STUDENTS
                            $std = Student::where('id', $std_id)->first();

                            $subph = Subphase::find($std_sub->subphase_id);
                            $subphase_name = $subph->name;
                            $subphase_code = $subph->code;
                            $email = $std->email;
                            $std_name = $std->name;
                            $data = [
                                'subphase_code' => $subphase_code,
                                'subphase_name' => $subphase_name,
                                'student_name' => $std_name,
                                'program' => $prg_name,
                            ];
                            $coo = coordinator::find(Auth::guard('coordinator')->id());
                            sendMail::dispatch($email, "Added to the subphase", $data, 'emails.addedToSubphase', $coo->username, $coo->email);
                            $added_std[] = $s;
//                        Mail::send('emails.addedToSubphase', $data, function($m) use ($email) {
//                            $m->subject("Added to the subphase");
//                            $m->to($email);
//                        });
                        } else {
                            $not_added_std[] = $s->username;
//                            return redirect()->back()->with("error", "some of the students are not in program.First add them in program and then add them is subphase");
                        }
                    } else {
                        $not_added_std[] = $value;
//                        return redirect()->back()->with("error", "some of the students are not in program.First add them in program and then add them is subphase");
                    }
                }
            }
            $events = Event::where(["subphase_id" => $subphase])->get();
            $subp_name = Subphase::find($subphase)->name;
            foreach ($events as $e) {
                if ($e->mail == 1) {
                    foreach ($added_std as $stu) {
                        $std = $stu->id;
                        $std_evnt = \App\Student_event::where(["student_id" => $std, "event_id" => $e->id])
                                ->where("status", "!=", "completed")
                                ->first();
                        if ($std_evnt != null) {
                            $std_evnt->status = "pending";
                            $std_evnt->report_location = "";
                            $std_evnt->isAccepted = 0;
                            $std_evnt->save();
                        } else {
                            $std_evnt = new \App\Student_event();
                            $std_evnt->student_id = $std;
                            $std_evnt->event_id = $e->id;
                            $std_evnt->program_id = $prg_id;
                            $std_evnt->status = "pending";
                            $std_evnt->start_date = $e->start_date;
                            $std_evnt->end_date = $e->end_date;
                            $std_evnt->save();
                        }

                        $email = Student::find($std)->email;
                        $data = [
                            "student" => $stu->name,
                            "event" => $e->name,
                            "program" => $prg_name,
                            "subphase" => $subp_name,
                        ];
                        $coo = coordinator::find(Auth::guard('coordinator')->id());
                        sendMail::dispatch($email, "Added to the new event", $data, "emails.addStudentToEvent", $coo->username, $coo->email);
                    }
                } else {
                    foreach ($added_std as $stu) {
                        $std = $stu->id;
                        $std_evnt = \App\Student_event::where(["student_id" => $std, "event_id" => $e->id])
                                ->where("status", "!=", "completed")
                                ->first();
                        if ($std_evnt != null) {
                            $std_evnt->status = "pending";
                            $std_evnt->report_location = "";
                            $std_evnt->isAccepted = 0;
                            $std_evnt->save();
                        } else {
                            $std_evnt = new \App\Student_event();
                            $std_evnt->student_id = $std;
                            $std_evnt->event_id = $e->id;
                            $std_evnt->program_id = $prg_id;
                            $std_evnt->status = "pending";
                            $std_evnt->start_date = $e->start_date;
                            $std_evnt->end_date = $e->end_date;
                            $std_evnt->save();
                        }
                    }
                }
            }
            $subp = Subphase::find($subphase);
            $std_ids = null;
            foreach ($not_added_std as $a) {
                $std_ids .= $a . " , ";
            }
            LogActivity::addToLog("Coordinator has added students to subphase " . $subp->name . " in phase " . $subp->phase->phase_name . " in program " . $prg_name);
            if (sizeof($not_added_std) == 0) {
                return redirect()->back()->with('status', 'Students added to subphase successfully');
            } else {
                if (sizeof($not_added_std) == 1) {
                    return redirect()->back()->with("error", sizeof($not_added_std) . " student is not added to the subphase. student is : " . $std_ids);
                } else {
                    return redirect()->back()->with("error", sizeof($not_added_std) . " students are not added to the subphase. students are : " . $std_ids);
                }
//                dd("ABCD");
            }
        }
    }

    public function reject_request(Request $request) {
        $prg_id = Session::get('coord_prg');
        $std_id = $request->input('std_id');
        $student = Student::find($std_id);
        $student_name = $student->name;
        $email = $student->email;

        $m1 = student_program::where([
                    'student_id' => $std_id,
                    'program_id' => $prg_id,
                    'status' => 'pending',
                ])->first();
        $mentor1_name = \App\Faculty::find($m1->mentor_1)->name;

        if ($m1 != null) {
            if ($m1->off_campus_mentor_id != 0) {
                \App\off_campus_mentor::find($m1->off_campus_mentor_id)->delete();
            }
            $m1->status = "a";
            $m1->isRegistered = 0;
            $m1->mentor_1 = 0;
            $m1->mentor_2 = null;
            $m1->off_campus_mentor_id = 0;
            $m1->project_topic = null;
            $m1->project_type = null;
            $m1->area_of_interest = null;
            $m1->project_start_date = null;
            $m1->save();

            //SEND MAIL TO STUDENT
            $data = [
                'student_name' => $student_name,
                'mentor_name' => $mentor1_name,
            ];
            $coo = coordinator::find(Auth::guard('coordinator')->id());
            sendMail::dispatch($email, "Request Rejected", $data, 'emails.coordinator_req_rej', $coo->username, $coo->email);
//            Mail::send('emails.coordinator_req_rej', $data, function($m) use ($email) {
//                $m->subject("Request Rejected");
//                $m->to($email);
//            });
        }
        LogActivity::addToLog("Coordinator has rejected student " . $student->username . " request on the behalf of " . $mentor1_name);

        return "true";
    }

    public function accept_request(Request $request) {
        $prg_id = Session::get('coord_prg');
        $std_id = $request->input('std_id');
        $student = Student::find($std_id);
        $student_name = $student->name;
        $email = $student->email;
        $m1 = student_program::where([
                    'student_id' => $std_id,
                    'program_id' => $prg_id,
                    'status' => 'pending',
                ])->first();
        $mentor1_name = \App\Faculty::find($m1->mentor_1)->name;
        if ($m1 != null) {
            $m1->status = "accepted";
            $m1->save();

            //MAIL SEND TO STUDENT
            $data = [
                'student_name' => $student_name,
                'mentor_name' => $mentor1_name,
            ];
            $coo = coordinator::find(Auth::guard('coordinator')->id());
            sendMail::dispatch($email, "Request Accepted", $data, 'emails.coordinator_req_acc', $coo->username, $coo->email);
//            Mail::send('emails.coordinator_req_acc', $data, function($m) use ($email) {
//                $m->subject("Request Accepted");
//                $m->to($email);
//            });
        }
        LogActivity::addToLog("Coordinator has accepted student " . $student->username . " request on the behalf of " . $mentor1_name);
        return "true";
    }

    public function send_reminder_std(Request $request) {
        $prg_id = Session::get('coord_prg');
        $prg_name = program::find($prg_id)->name;
        $std_id = $request->input('std_id');
        $std = Student::find($std_id);
        $email = $std->email;
        $data = [
            'std' => $std->name,
            'program' => $prg_name,
        ];
        $coo = coordinator::find(Auth::guard('coordinator')->id());
        //SEND MAIL TO STUDENT
        sendMail::dispatch($email, "Reminder to fill consent form", $data, 'emails.coordinator_remind_std', $coo->username, $coo->email);
//        Mail::send('emails.coordinator_remind_std', $data, function($m) use ($email) {
//            $m->subject("Reminder to fill consent form");
//            $m->to($email);
//        });
        //return $email;

        LogActivity::addToLog("Coordinato has sent reminder to the student " . $std->username . " regarding consent form");
    }

    public function get_grading_page() {
        $prg_id = Session::get('coord_prg');
        $phases = Phase::where("program_id", $prg_id)->get();
        return view('coordinator/coordinator_grading')->with([
                    'phases' => $phases,
        ]);
    }

    public function get_students_for_grade(Request $request) {
        $validator = Validator::make($request->all(), [
                    'phase_choose' => [
                        Rule::notIn(['n']),
                    ],
                    'subphase_choose' => [
                        Rule::notIn(['n']),
                    ],
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        } else {
            $prg_id = Session::get('coord_prg');
            $phase = $request->input('phase_choose');
            $subphase = $request->input("subphase_choose");
            return redirect("/student_grading/$phase/$subphase");
        }
    }

    public function get_coordinator_grading_page($phase, $subphase) {
        $prg_id = Session::get('coord_prg');
        $all_std = student_subphase::where([
                    'subphase_id' => $subphase,
                ])->get();
        $students = array();
        foreach ($all_std as $std) {
            $s = student_program::where([
                        'program_id' => $prg_id,
                        'student_id' => $std->student_id,
                    ])->first();
            if ($s->status != "completed") {
                $students[] = $std;
            }
        }
        $subp = Subphase::find($subphase);
        $grades = \App\grade::where('g_id', $subp->g_id)->get();
        $is_committee = $subp->evaluation_committee;

        return view("coordinator/coordinator_grading_students")->with([
                    'current_phase' => Phase::find($phase),
                    'current_subphase' => $subp,
                    'students' => $students,
                    'prg_id' => $prg_id,
                    'grades' => $grades,
                    "com" => $is_committee,
        ]);
    }

    public function c_view_report($std_sub_id) {
//        $sub_std_id = $request->input('sub_std_id');
        $coor_id = Auth::guard('coordinator')->id();
        $sub_std = \App\student_subphase::find($std_sub_id);
        if ($sub_std->subphase->phase->program->coordinator_id == $coor_id) {
            $events = Event::where(['subphase_id' => $sub_std->subphase_id, 'submission' => 1])->get();
            $event_show = array();
            foreach ($events as $event) {
                array_push($event_show, Student_event::where(['event_id' => $event->id, 'student_id' => $sub_std->student_id])->get());
            }
//            dd($event_show);
            return view('faculty/faculty_view_report')->with([
                        'event_show' => $event_show,
                        'std_id' => \App\Student::find($sub_std->student_id)->username,
                        'phase_name' => $sub_std->subphase->phase->phase_name,
                        'subphase_name' => $sub_std->subphase->name,
                    ])->render();
        } else {
            return redirect('grading');
        }
    }

    public function get_mentor_comment(Request $request) {
        $sub_std_id = $request->input('sub_std_id');
        $sub_std = \App\student_subphase::find($sub_std_id);
        $mentor_comments = $sub_std->mentor_grade;
        $output = "";
        foreach ($mentor_comments as $mc) {
            $output .= '
                            <div class="form-group row">
                                <div class="col-xs-3">
                                    <p class="text-center font-13 font-bold form-check-label">' . \App\Faculty::find($mc->mentor_id)->name . '</p>
                                </div>
                                <div class="col-xs-9" >
                                    <input readonly="" class="text-center form-control" style="font-size: 15px" value="' . $mc->comment . '">

                                </div>
                            </div>';
        }
        return $output;
    }

    public function save_grade_coordinator(Request $request) {
        $sub_std_id = $request->input('s_id');
        $grade = $request->input('grade');
        $sub_std = student_subphase::find($sub_std_id);
        if ($grade == "select") {
            return "false";
        } else {
            $sub_std->final_grade = $grade;
            $sub_std->save();
            return "true";
        }
    }

    public function submit_grade_coordinator(Request $request) {
        $sub_std_id = $request->input('s_id');
        $grade = $request->input('grade');
        $sub_std = student_subphase::find($sub_std_id);
        if ($grade == "select") {
            return "false";
        } else {
            $subphase = Subphase::find($sub_std->subphase_id);
            $prg_id = Session::get('coord_prg');
            if ($subphase->name == "final_subphase") {
                $student = student_program::where([
                            "student_id" => $sub_std->student_id,
                            "program_id" => $prg_id,
                        ])->first();
                if ($sub_std->is_fail == "1") {
                    $student->is_completed_program = 0;
                } else {
                    $student->is_completed_program = 1;
                }
                $student->save();
            }
            $sub_std->final_grade = $grade;
            $sub_std->isFinal = 1;
            $sub_std->status = "completed";
            $sub_std->save();
            LogActivity::addToLog("Coordinator has given " . $grade . " to student " . Student::find($sub_std->student_id)->username . " in program " . program::find($prg_id)->name);
            return "true";
        }
    }

    public function save_coordinator_comment(Request $request) {
        $sub_std_id = $request->input('s_id');
        $comment = $request->input('comment');
        $sub_std = student_subphase::find($sub_std_id);
        $sub_std->comment = $comment;
        $sub_std->save();
        return "true";
    }

    public function get_manage_faculties() {
        $prg_id = Session::get('coord_prg');

        $added_faculties = \App\faculty_program::where('program_id', $prg_id)->get();
        $a_faculties = array();
        foreach ($added_faculties as $a_f) {
            $a_faculties[] = \App\Faculty::find($a_f->faculty_id);
        }
        $all_faculties = \App\Faculty::all();
        $diff = array();
        foreach ($all_faculties as $f) {
            $diff[$f->id] = $f;
        }
        $dd = array();
        foreach ($added_faculties as $f) {
            if ($diff[$f->faculty_id] != null) {
                unset($diff[$f->faculty_id]);
            }
        }
        return view('coordinator/coordinator_manage_faculties')->with([
                    'faculties' => $diff,
                    'added_faculties' => $a_faculties,
                    'prg_id' => $prg_id,
        ]);
    }

    public function add_faculties_to_program(Request $request) {
        $p_id = Session::get('coord_prg');
        $validator = Validator::make($request->all(), [
                    'to' => 'required'
        ]);
        $faculties = $request->input('to');
        foreach ($faculties as $f) {
            $faculty_program = new \App\faculty_program();
            $faculty_program->faculty_id = $f;
            $faculty_program->program_id = $p_id;
            $faculty_program->save();
            LogActivity::addToLog("Coordinator has added " . Faculty::find($f)->name . " to the program " . program::find($p_id)->name);
        }

        return redirect()->back()->with('status', 'Faculty  have been added to the program successfully');
    }

    public function co_delete_faculties(Request $request) {
        $p_id = Session::get('coord_prg');
        $f_id = $request->input('f_id');
        $faculty = \App\faculty_program::where([
                    'faculty_id' => $f_id,
                    'program_id' => $p_id
        ]);
        $faculty->delete();
        LogActivity::addToLog("Coordinator has deleted " . Faculty::find($f_id)->name . " in program " . program::find($p_id)->name);
        return "true";
    }

    public function manage_committee_events() {
        $p_id = Session::get('coord_prg');
        $prg = program::find($p_id);
        return view('coordinator/coordinator_manage_committee_events')->with('program', $prg);
    }

    public function se_save_deadline(Request $request) {

        if ($request->input('start') == "" || $request->input('end') == "") {
            return "Please fill date field";
        }
        $start = date('Y-m-d', strtotime($request->input('start')));
        $end = date('Y-m-d', strtotime($request->input('end')));
        if ($start <= $end) {
            $p_id = Session::get('coord_prg');
            $prg = program::find($p_id);
            $start_date = $request->input('start');
            $end_date = $request->input('end');
            $prg->se_n_start_date = $start_date;
            $prg->se_n_end_date = $end_date;
            $curr_date = date('Y-m-d', strtotime(date('Y-m-d')));
            $prg->save();
            $faculties_id = student_program::where(['program_id' => $p_id])->where('status', '!=', 'completed')->distinct('mentor_1')->pluck('mentor_1');
            $faculties = array();
            foreach ($faculties_id as $f_id) {
                $faculty = Faculty::find($f_id);
                $email = $faculty->email;
                $data = [
                    "faculty" => $faculty,
                    "program" => $prg->name,
                    "start_date" => $start_date,
                    "end_date" => $end_date,
                ];
                $coo = coordinator::find(Auth::guard('coordinator')->id());
                sendMail::dispatch($email, "Subject expert Nomination dates", $data, 'emails.faculty_se_dates', $coo->username, $coo->email);
            }

            LogActivity::addToLog("Coordinator has edited deadlines for subject expert nominations for program " . $prg->name);
            return "true";
            //SEND MAIL TO ALL FACULTY MEMBERS.
        } else {
            return "Please enter appropriate start and end date";
        }
    }

    public function get_committee() {
        $prg_id = Session::get('coord_prg');
        $students = student_program::where(['program_id' => $prg_id, 'status' => 'accepted'])->get();
        $data = array();
        foreach ($students as $std) {
            $faculty = \App\bid_request::where(["std_prg_id" => $std->id])->get(["faculty_id"]);
            $tmp = array();
            $tmp["faculty"] = $faculty;
            $tmp["std_prg"] = $std;
            $data[] = $tmp;
        }
        //ADD MAX AND MIN FORM FOR BIDDING AND ADD IN CONTROLLER
        return view('coordinator/coordinator_committee')->with([
                    'students' => $data,
        ]);
    }

    public function sub_exp_reminder(Request $request) {
        $std_prg_id = $request->input('std_prg_id');
        $std_prg = student_program::find($std_prg_id);
        $mentor = Faculty::find($std_prg->mentor_1);
        $email = $mentor->email;
        $data = [
            'mentor' => $mentor,
            'std' => Student::find($std_prg->student_id),
            'prg' => program::find($std_prg->program_id),
        ];
        $coo = coordinator::find(Auth::guard('coordinator')->id());
        sendMail::dispatch($email, "Reminder for subject expert Nomination", $data, 'emails.sub_exp_reminder', $coo->username, $coo->email);
//        Mail::send('emails.sub_exp_reminder', $data, function($m) use ($email) {
//            $m->subject("Reminder for subject expert Nomination");
//            $m->to($email);
//        });
        return "true";
    }

    public function subject_expert_reminder(Request $request) {
        $std_prg_id = $request->input('std_prg_id');
        $std_prg = student_program::find($std_prg_id);
        $mentor = Faculty::find($std_prg->mentor_1);
        $student = Student::find($std_prg->student_id);
        $sub = Faculty::find($std_prg->subject_expert);
        $email = $sub->email;
        $data = [
            'sub_exp' => $sub->name,
            'mentor' => $mentor,
            'student' => $student,
            'program' => program::find($std_prg->program_id)->name,
        ];
        $coo = coordinator::find(Auth::guard('coordinator')->id());
        sendMail::dispatch($email, "Reminder to respond to subject expert request", $data, 'emails.subject_expert_reminder', $coo->username, $coo->email);
    }

    public function accept_on_behalf__of_se(Request $request) {
        $std_prg_id = $request->input('std_prg_id');
        $std_prg = student_program::find($std_prg_id);
        $std_prg->is_final_se = 1;
        $std_prg->save();
        $mentor = Faculty::find($std_prg->mentor_1);
        $student = Student::find($std_prg->student_id);
        $sub = Faculty::find($std_prg->subject_expert);
        $email = $sub->email;
        $prg_id = Session::get('coord_prg');
        $coordinator = coordinator::find(program::find($prg_id)->coordinator_id)->username;
        $data = [
            'sub_exp' => $sub->name,
            'mentor' => $mentor,
            'student' => $student,
            'program' => program::find($std_prg->program_id)->name,
            'coordinator' => $coordinator,
        ];
        $coo = coordinator::find(Auth::guard('coordinator')->id());
        sendMail::dispatch($email, "subject expert request accepted by coordinator", $data, 'emails.accept_on_behalf_of_se', $coo->username, $coo->email);
    }

    public function reject_on_behalf__of_se(Request $request) {
        $std_prg_id = $request->input('std_prg_id');
        $std_prg = student_program::find($std_prg_id);
        $sub = Faculty::find($std_prg->subject_expert);
        $std_prg->is_final_se = 0;
        $std_prg->subject_expert = null;
        $std_prg->save();
        $mentor = Faculty::find($std_prg->mentor_1);
        $student = Student::find($std_prg->student_id);

        $prg_id = Session::get('coord_prg');
        $coordinator = coordinator::find(program::find($prg_id)->coordinator_id)->username;
        $data = [
            'mentor' => $mentor,
            'student' => $student,
            'program' => program::find($std_prg->program_id)->name,
            'coordinator' => $coordinator,
        ];
        $coo = coordinator::find(Auth::guard('coordinator')->id());
        sendMail::dispatch($sub->email, "subject expert request rejected by coordinator", $data, 'emails.reject_on_behalf_of_se', $coo->username, $coo->email);
        sendMail::dispatch($mentor->email, "subject expert request rejected by coordinator", $data, 'emails.reject_on_behalf_of_se', $coo->username, $coo->email);
    }

    public function start_bid_date(Request $request) {
        if ($request->input('start') == "" || $request->input('end') == "" || $request->input('min') == "" || $request->input('max') == "") {
            return "Please fill required field";
        }
        $min = $request->input('min');
        $max = $request->input('max');
        $start = date('Y-m-d', strtotime($request->input('start')));
        $end = date('Y-m-d', strtotime($request->input('end')));
        if ($start <= $end && $min <= $max) {
            $p_id = Session::get('coord_prg');
            $prg = program::find($p_id);
            $prg->bid_start_date = $request->input('start');
            $prg->bid_end_date = $request->input('end');
            $prg->bid_min_limit = $request->input('min');
            $prg->bid_max_limit = $request->input('max');
            $curr_date = date('Y-m-d', strtotime(date('Y-m-d')));

            $prg->save();
            LogActivity::addToLog("Coordinator has edited bidding details for program " . program::find($p_id)->name);
            return "true";

            //SEND MAIL TO ALL FACULTY MEMBERS.
        } else {
            return "Please enter appropriate start and end date";
        }
    }

    public function get_bid_requests(Request $request) {
        $std_prg_id = $request->input('std_prg_id');
        $all_faculty = Faculty::all();
        $remaining_faculty = array();
        $std_prg = student_program::find($std_prg_id);
        $bids = \App\bid_request::where('std_prg_id', $std_prg_id)->get();
        foreach ($all_faculty as $af) {
            if ($af->id == $std_prg->mentor_1) {
                
            } else if ($af->id == $std_prg->mentor_2) {
                
            } else if ($af->id == $std_prg->subject_expert) {
                
            } else {
                foreach ($bids as $b) {
                    if ($af->id == $bid->faculty_id) {
                        
                    } else {
                        $remaining_faculty[] = $af;
                    }
                }
            }
        }
        $output = "";
        $output .= '<optgroup label="mentor">';
        $output .= '<option value="' . $std_prg->mentor_1 . '">' . Faculty::find($std_prg->mentor_1)->name . '</option>';
        if ($std_prg->mentor_2 != null) {
            $output .= '<option value="' . $std_prg->mentor_2 . '">' . Faculty::find($std_prg->mentor_2)->name . '</option>';
        }
        $output .= '</optgroup>';
        $output .= '<optgroup label="subject expert">';
        if ($std_prg->subject_expert != null) {
            $output .= '<option value="' . $std_prg->subject_expert . '">' . Faculty::find($std_prg->subject_expert)->name . '</option>';
        }
        $output .= '</optgroup>';
        $output .= '<optgroup label="bid members">';
        foreach ($bids as $bid) {
            $output .= '<option value="' . $bid->faculty_id . '">' . Faculty::find($bid->faculty_id)->name . '</option>';
        }
        $output .= '</optgroup>';
        $output .= '<optgroup label="remaining faculty">';
        foreach ($remaining_faculty as $af) {
            $output .= '<option value="' . $af->id . '">' . $af->name . '</option>';
        }
        $output .= '</optgroup>';

        return $output;
    }

    public function save_final_committee(Request $request) {
        $mentors = array_unique($request->input('to'));
        $std_prg_id = $request->input('std_prg');
        $validator = Validator::make($request->all(), [
                    'to' => 'required',
                        ], [
                    'required' => 'You need to select faculty members'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        } else {
            $prg_id = Session::get('coord_prg');
            $prg_name = program::find($prg_id)->name;
            $com = new committee();
            $com->program_id = $prg_id;
            $com->save();
            $std_prg = student_program::find($std_prg_id);
            $student = Student::find($std_prg->student_id);
            $members = array();
            foreach ($mentors as $m) {
                $members[] = Faculty::find($m);
            }
            foreach ($mentors as $m) {
                $com_fac = new committee_faculty();
                $com_fac->committee_id = $com->id;
                $com_fac->faculty_id = $m;
                $com_fac->save();
                $faculty = Faculty::find($m);
                $email = $faculty->email;
                $data = [
                    'faculty' => $faculty,
                    'mem' => $members,
                    'program' => $prg_name,
                    'student' => $student,
                ];
                $coo = coordinator::find(Auth::guard('coordinator')->id());
                sendMail::dispatch($email, "Added to Evaluation Committee", $data, 'emails.committee_member_added', $coo->username, $coo->email);
            }
//            \App\bid_request::where(['std_prg_id' => $std_prg_id])->delete();
            $std_p = $std_prg;
            $std_p->committee_id = $com->id;
            $std_p->save();
            LogActivity::addToLog("Coordinator has finalize committee for student " . $student->username . " for progrm " . $prg_name);
            return redirect()->back()->with('status', 'committee has been created successfully');
        }
    }

    public function edit_committee(Request $request) {
        $faculty = Faculty::all();
        $output = "";
        $output2 = "";
        $std_prg = student_program::find($request->input('std_prg_id'));
        $committee_id = $std_prg->committee_id;
        $com_mem = committee_faculty::where('committee_id', $committee_id)->get();
        $output .= '<optgroup label="all faculty">';
        foreach ($faculty as $f) {
            $output .= '<option value="' . $f->id . '">' . $f->name . '</option>';
        }
        $output2 .= '</optgroup>';
        $mentor = $std_prg->mentor_1;
        $se = $std_prg->subject_expert;
        foreach ($com_mem as $cm) {
            $c_member = Faculty::find($cm->faculty_id);
            if ($cm->faculty_id == $mentor) {
                $output2 .= '<optgroup label="mentor">';
                $output2 .= '<option value="' . $c_member->id . '">' . $c_member->name . '</option>';
                $output2 .= '</optgroup>';
            } else if ($cm->faculty_id == $se) {
                $output2 .= '<optgroup label="subject expert">';
                $output2 .= '<option value="' . $c_member->id . '">' . $c_member->name . '</option>';
                $output2 .= '</optgroup>';
            } else {
                $output2 .= '<optgroup label="other members">';
                $output2 .= '<option value="' . $c_member->id . '">' . $c_member->name . '</option>';
                $output2 .= '</optgroup>';
            }
        }
        $data[] = $output;
        $data[] = $output2;
        //  dd($data);
        return $data;
    }

    public function edit_final_committee(Request $request) {
        $std_prg_id = $request->input('std_prg');

        $mentors = array_unique($request->input('to'));

        $validator = Validator::make($request->all(), [
                    'to' => 'required',
                        ], [
                    'required' => 'You need to select faculty members'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        } else {
            $prg_id = Session::get('coord_prg');
            $prg_name = program::find($prg_id)->name;
            $com_id = student_program::find($std_prg_id)->committee_id;
            committee_faculty::where('committee_id', $com_id)->delete();

            $std_prg = student_program::find($std_prg_id);
            $student = Student::find($std_prg->student_id);
            $members = array();
            foreach ($mentors as $m) {
                $members[] = Faculty::find($m);
            }

            foreach ($mentors as $m) {
                $com_fac = new committee_faculty();
                $com_fac->committee_id = $com_id;
                $com_fac->faculty_id = $m;
                $com_fac->save();


                $faculty = Faculty::find($m);
                $email = $faculty->email;
                $data = [
                    'faculty' => $faculty,
                    'mem' => $members,
                    'program' => $prg_name,
                    'student' => $student,
                ];
                $coo = coordinator::find(Auth::guard('coordinator')->id());
                sendMail::dispatch($email, "Added to Evaluation Committee", $data, 'emails.committee_member_added', $coo->username, $coo->email);
            }
            //SEND MAIL TO COMMITTEE MEMBERS
            LogActivity::addToLog("Coordinator has edited committee for student " . $student->username . " for progrm " . $prg_name);
            return redirect()->back()->with('status', 'committee has been changed successfully');
        }
    }

    public function view_committee(Request $request) {
        $std_prg_id = $request->input('std_prg_id');
        $std_prg = student_program::find($std_prg_id);
        $mentor = Faculty::find($std_prg->mentor_1)->name . " (mentor)";
        if ($std_prg->subject_expert != null) {
            $sub_exp = Faculty::find($std_prg->subject_expert)->name . " (subject expert)";
        } else {
            $sub_exp = null;
        }
        $c_members = null;
        if ($std_prg->subject_expert == null) {
            $c_members = committee_faculty::where('committee_id', $std_prg->committee_id)
                    ->whereNotIn('faculty_id', [$std_prg->mentor_1])
                    ->get();
        } else {
            $c_members = committee_faculty::where('committee_id', $std_prg->committee_id)
                    ->whereNotIn('faculty_id', [$std_prg->mentor_1, $std_prg->subject_expert])
                    ->get();
        }

        $output = "";
        $output .= '
            <div class="form-group col-xs-12">
                                <p>' . $mentor . '</p>
                            </div>';
        if ($sub_exp != null) {
            $output .= '
            <div class="form-group col-xs-12">
                                <p>' . $sub_exp . '</p>
                            </div>';
        }
        foreach ($c_members as $m) {
            $output .= '
            <div class="form-group col-xs-12">
                                <p>' . Faculty::find($m->faculty_id)->name . '</p>
                            </div>';
        }
        return $output;
    }

    public function get_create_committee() {
        $prg_id = Session::get('coord_prg');
        $students = student_program::where(['program_id' => $prg_id, 'status' => 'accepted'])->get();
        return view('coordinator/coordinator_create_committee')->with([
                    'students' => $students,
        ]);
    }

    public function get_all_mentors(Request $request) {
        $std_prg_id = $request->input('std_prg_id');
        $std_prg = student_program::find($std_prg_id);
        $faculties = Faculty::all();
        $output = "";
        $output .= '<option value="' . $std_prg->mentor_1 . '">' . Faculty::find($std_prg->mentor_1)->name . " (mentor)" . '</option>';
        foreach ($faculties as $f) {
            if ($f->id != $std_prg->mentor_1) {
                $output .= '<option value="' . $f->id . '">' . $f->name . '</option>';
            }
        }
        return $output;
    }

    public function save_final_committee_btech(Request $request) {
        $mentors = $request->input('to');
        $std_prg_id = $request->input('std_prg');
        $validator = Validator::make($request->all(), [
                    'to' => 'required',
                        ], [
                    'required' => 'You need to select faculty members'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        } else {
            $prg_id = Session::get('coord_prg');
            $prg_name = program::find($prg_id)->name;
            $com = new committee();
            $com->program_id = $prg_id;
            $com->save();
            $std_prg = student_program::find($std_prg_id);
            $student = Student::find($std_prg->student_id);
            $members = array();
            foreach ($mentors as $m) {
                $members[] = Faculty::find($m);
            }
            foreach ($mentors as $m) {
                $com_fac = new committee_faculty();
                $com_fac->committee_id = $com->id;
                $com_fac->faculty_id = $m;
                $com_fac->save();

                $faculty = Faculty::find($m);
                $email = $faculty->email;
                $data = [
                    'faculty' => $faculty,
                    'mem' => $members,
                    'program' => $prg_name,
                    'student' => $student,
                ];
                $coo = coordinator::find(Auth::guard('coordinator')->id());
                sendMail::dispatch($email, "Added to Evaluation Committee", $data, 'emails.committee_member_added', $coo->username, $coo->email);
            }
            $std_p = student_program::find($std_prg_id);
            $std_p->committee_id = $com->id;
            $std_p->save();
            LogActivity::addToLog("Coordinator has edited committee for student " . $student->username . " for progrm " . $prg_name);
            return redirect()->back()->with('status', 'committee has been created successfully');
        }
    }

    public function edit_committee_btech(Request $request) {
        $faculty = Faculty::all();
        $output = "";
        $output2 = "";
        $std_prg = student_program::find($request->input('std_prg_id'));
        $committee_id = $std_prg->committee_id;
        $com_mem = committee_faculty::where('committee_id', $committee_id)->get();

        foreach ($faculty as $f) {
            $output .= '<option value="' . $f->id . '">' . $f->name . '</option>';
        }

        foreach ($com_mem as $cm) {
            if ($cm->faculty_id == $std_prg->mentor_1) {
                $c_member = Faculty::find($cm->faculty_id);
                $output2 .= '<option value="' . $c_member->id . '">' . $c_member->name . " (mentor)" . '</option>';
            } else {
                $c_member = Faculty::find($cm->faculty_id);
                $output2 .= '<option value="' . $c_member->id . '">' . $c_member->name . '</option>';
            }
        }
        $data[] = $output;
        $data[] = $output2;
        //  dd($data);
        return $data;
    }

    public function edit_final_committee_btech(Request $request) {
        $std_prg_id = $request->input('std_prg');
        $mentors = $request->input('to');

        $validator = Validator::make($request->all(), [
                    'to' => 'required',
                        ], [
                    'required' => 'You need to select faculty members'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        } else {
            $prg_id = Session::get('coord_prg');
            $prg_name = program::find($prg_id)->name;
            $com_id = student_program::find($std_prg_id)->committee_id;
            committee_faculty::where('committee_id', $com_id)->delete();

            $std_prg = student_program::find($std_prg_id);
            $student = Student::find($std_prg->student_id);
            $members = array();
            foreach ($mentors as $m) {
                $members[] = Faculty::find($m);
            }
            foreach ($mentors as $m) {
                $com_fac = new committee_faculty();
                $com_fac->committee_id = $com_id;
                $com_fac->faculty_id = $m;
                $com_fac->save();


                $faculty = Faculty::find($m);
                $email = $faculty->email;
                $data = [
                    'faculty' => $faculty,
                    'mem' => $members,
                    'program' => $prg_name,
                    'student' => $student,
                ];
                $coo = coordinator::find(Auth::guard('coordinator')->id());
                sendMail::dispatch($email, "Added to Evaluation Committee", $data, 'emails.committee_member_added', $coo->username, $coo->email);
            }
            //SEND MAIL TO COMMITTEE MEMBERS
            LogActivity::addToLog("Coordinator has edited committee for student " . $student->username . " for progrm " . $prg_name);
            return redirect()->back()->with('status', 'committee has been changed successfully');
        }
    }

    public function get_committee_grades(Request $request) {
        $committee_id = $request->input('committee_id');
        $subphase_id = $request->input('subphase_id');
        $members = \App\committee_faculty::where("committee_id", $committee_id)->get();
        $output = "";
        foreach ($members as $mem) {
            $grade = \App\student_committee_grade::where(['committee_faculty_id' => $mem->id, 'subphase_std_id' => $subphase_id])->first();
            if ($grade != null) {
                $output .= '
                    <div class="form-group">
                                <div class="col-xs-8">
                                    <input class="text-center form-control text-dark font-13" readonly value="' . \App\Faculty::find($mem->faculty_id)->name . '" >
                                </div>
                                <div class="col-xs-4">
                                    <p class="text-center font-bold form-control font-13">' . $grade->grade . '</p>
                                </div>
                                
                            </div>';
            } else {
                $output .= '
                    <div class="form-group">
                                <div class="col-xs-8">
                                    <input class="text-center form-control text-dark font-13" readonly value="' . \App\Faculty::find($mem->faculty_id)->name . '" >
                                </div>
                                <div class="col-xs-4">
                                    <p class="text-center font-bold form-control font-13"></p>
                                </div>
                                
                            </div>';
            }
        }
        return $output;
    }

    public function get_committee_comment(Request $request) {
        $committee_id = $request->input('committee_id');
        $subphase_id = $request->input('subphase_id');
        $members = \App\committee_faculty::where("committee_id", $committee_id)->get();
        $output = "";
        foreach ($members as $mem) {
            $grade = \App\student_committee_grade::where(['committee_faculty_id' => $mem->id, 'subphase_std_id' => $subphase_id])->first();
            if ($grade != null) {
                $output .= '
                    <div class="form-group">
                                <div class="col-xs-5">
                                    <input class="text-center form-control text-dark font-13" readonly value="' . \App\Faculty::find($mem->faculty_id)->name . '" >
                                </div>
                                <div class="col-xs-7">
                                    <textarea class="text-center form-control text-dark font-13" readonly >' . $grade->comment . '</textarea>
                                </div>
                                
                            </div>';
            } else {
                $output .= '
                    <div class="form-group">
                                <div class="col-xs-5">
                                    <input class="text-center form-control text-dark font-13" readonly value="' . \App\Faculty::find($mem->faculty_id)->name . '" >
                                </div>
                                <div class="col-xs-7">
                                    <textarea class="text-center form-control text-dark font-13" readonly ></textarea>
                                </div>
                                
                            </div>';
            }
        }
        return $output;
    }

    public function get_email_template() {
        $program_id = Session::get('coord_prg');
        $templates = email_template::where("program_id", $program_id)->get(["id", "subject"]);
        return view('coordinator/coordinator_email_template')->with(["prg_id" => $program_id, "templates" => $templates]);
    }

    public function send_mail(Request $request) {
        $validator = Validator::make($request->all(), [
                    'to' => 'required',
                    'subject' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        } else {
            $to = $request->input("to");
            $cc = $request->input("cc");
            $subject = $request->input("subject");
            $email = $request->input("email");
            $raw_email = $request->input("raw_email");

            $program_id = Session::get('coord_prg');
            $coord = coordinator::find($program_id);
            $coord_email = $coord->email;
            $coord_name = $coord->username;
            $email_template = email_template::where(["subject" => $subject, "program_id" => $program_id])->first();

            if ($email_template == null) {
                $email_template = new email_template();
                $email_template->program_id = $program_id;
                $email_template->subject = $subject;
                $email_template->email_text = $email;
                $email_template->raw_email = $raw_email;
                $email_template->save();
            } else {
                $email_template->email_text = $email;
                $email_template->raw_email = $raw_email;
                $email_template->save();
            }

            sendMail_coordinator::dispatch($email, $subject, $to, $cc, $coord_name, $coord_email);
//            Mail::raw($email, function($m) use ($subject, $to, $cc, $coord_email, $coord_name) {
//                $m->subject($subject);
//                $m->to($to);
//                $m->from("no-reply@example.com", "vision");
//                if ($cc[0] != null) {
//                    $m->cc($cc);
//                }
//            });
        }
    }

    public function bring_emails_of_students(Request $request) {
        $std_id = $request->input("std_id");
        $email = array();
        foreach ($std_id as $std) {
            array_push($email, Student::find($std)->email);
        }
        Session::put("email", $email);
        return Session::get("email");
    }

    public function get_mail_template_data(Request $request) {
        $tmp_id = $request->input("subject");
        return email_template::find($tmp_id)->raw_email;
    }

    public function get_manage_deadline_for_event() {
        $coord_id = Auth::guard('coordinator')->id();
        $prg = program::where('coordinator_id', $coord_id)->first();
        $prg_id = $prg->id;
        $phase = Phase::where('program_id', $prg->id)->get();


        return view('coordinator/coordinator_modify_deadline_for_event')->with([
                    'phase' => $phase,
                    'prg' => $prg,
        ]);
    }

    public function show_student_subphase(Request $request) {
        $sub_id = $request->input('sub_id');
        $std_sub = student_subphase::where([
                    'subphase_id' => $sub_id,
                ])->where('status', '!=', 'completed')
                ->get();
        $students = array();
        foreach ($std_sub as $ss) {
            $tmp = array();
            $tmp["username"] = Student::find($ss->student_id)->username;
            $tmp["id"] = $ss->student_id;
            $students[] = $tmp;
        }
        // dd($students);
        return $students;
    }

    public function modify_student_deadline(Request $request) {
        $validator = Validator::make($request->all(), [
                    'phase_choose' => [
                        Rule::notIn(['n']),
                    ],
                    'subphase_select' => [
                        Rule::notIn(['n']),
                    ],
                    'event_choose' => [
                        Rule::notIn(['n']),
                    ],
                    'start_date' => 'required|date',
                    'end_date' => 'required|date',
                    'to' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        } else {
            $sub_id = $request->input('subphase_select');
            $event_id = $request->input('event_choose');
            $students = $request->input('to');
            $std_ids = array();
            $s_date = $request->input('start_date');
            $e_date = $request->input('end_date');
            if ($s_date <= $e_date) {
                foreach ($students as $std) {
                    $std_event = Student_event::where([
                                'student_id' => $std,
                                'event_id' => $event_id
                            ])->first();
                    $std_event->start_date = $s_date;
                    $std_event->end_date = $e_date;
                    $std_event->save();
                    $std_ids[] = Student::find($std)->username;
                }
                $event = Event::find($std_event->event_id);
                LogActivity::addToLog("Coordinator has modified deadline for event " . $event->name . " in phase " . $event->subphase->phase->phase_name . " in subphase " . $event->subphase->name . " in program " . $event->subphase->phase->program->name . " for students " . implode(", ", $std_ids));
                return redirect()->back()->with('status', 'Dates have been edited for selected students');
            } else {
                return redirect()->back()->with('error_status', "please select valid start and end date")->withInput();
            }
        }
    }

    public function fail_student_subphase(Request $request) {
        $s_id = $request->input('s_id');
        $std_sub = student_subphase::find($s_id);
        $std_sub->is_fail = 1;
        $std_sub->save();
        $student_id = $std_sub->student_id;
        $program_id = Session::get('coord_prg');
        $std_prg = student_program::where([
                    'student_id' => $student_id,
                    'program_id' => $program_id
                ])->first();
        $std_prg->is_completed_program = 0;
        $std_prg->save();

//        $std_sub->delete();
        return "true";
    }

    public function do_final_grading(Request $request) {
        $phase = Phase::find($request->input("phase"));
        $subphase = Subphase::find($request->input("subphase"));
        $students = student_program::where("is_completed_program", "1")->get();
        $program_id = Session::get('coord_prg');

        if ($subphase->name == "final_subphase" && $phase->phase_name == "final_phase") {
            $events = Event::where('subphase_id', $subphase->id)->get();
            $event = null;
            foreach ($events as $e) {
                if ($e->name == "final_report_submission") {
                    $event = $e;
                    break;
                }
            }

            $students = student_program::where("is_completed_program", "1")->get();
            foreach ($students as $student) {
                $student->status = "completed";
                $student->is_completed_program = 0;
                $student->save();
                $std_event = Student_event::where([
                            'student_id' => $student->student_id,
                            'event_id' => $event->id
                        ])->first();
                $report = $std_event->report_location;
                $final_report = \App\final_report::where([
                            'student_id' => $student->student_id,
                            'program_id' => $program_id])->first();
                if ($final_report == null) {
                    $final_report = new \App\final_report();
                    $final_report->student_id = $student->student_id;
                    $final_report->program_id = $program_id;
                    $final_report->report_location = $report;
                    $final_report->save();
                } else {
                    $final_report->report_location = $report;
                    $final_report->save();
                }
            }
        }
        $subphase_students = student_subphase::where(["subphase_id" => $subphase->id, "is_fail" => "1"])->get();
        foreach ($subphase_students as $sub_std) {
            $sub_std->delete();
        }
        return "true";
    }

    public function modified_std_data(Request $request) {
        $program_id = Session::get('coord_prg');
        $project_topic = $request->input("project_topic");
        $aoi = $request->input("area_of_interest");
        $std_id = $request->input("std_id");
        $date = $request->input("date");

        $std = student_program::where(['student_id' => $std_id, 'program_id' => $program_id])->first();
        $std->project_topic = $project_topic;
        $std->area_of_interest = $aoi;
        $std->project_start_date = $date;
        $std->save();
        LogActivity::addToLog("Coordinator has modified project details of student " . Student::find($std_id)->username . " for program " . program::find($program_id)->name);
        return "true";
    }

    public function get_consent_form_deadline() {
        $p_id = Session::get('coord_prg');
        $prg = program::find($p_id);
        $prg_id = $prg->id;
        $std_prg = student_program::where(['program_id' => $prg_id])
                        ->where('status', '!=', 'completed')->get();
        $filled_form_students = student_program::where(['program_id' => $prg_id])
                        ->where('status', '!=', 'completed')
                        ->where('cf_start_date', '!=', 'null')
                        ->where('cf_end_date', '!=', 'null')->get();
        return view('coordinator/coordinator_consent_form')->with([
                    'program' => $prg,
                    'stud_prog' => $std_prg,
                    'filled_form_std' => $filled_form_students,
        ]);
    }

    public function save_consent_form_deadline(Request $request) {
        if ($request->input('start') == "" || $request->input('end') == "") {
            return "Please fill date field";
        }
        $start = date('Y-m-d', strtotime($request->input('start')));
        $end = date('Y-m-d', strtotime($request->input('end')));
        if ($start <= $end) {
            $p_id = Session::get('coord_prg');
            $prg = program::find($p_id);
            $start_date = $request->input('start');
            $end_date = $request->input('end');
            $prg->cf_start_date = $start_date;
            $prg->cf_end_date = $end_date;
            $curr_date = date('Y-m-d', strtotime(date('Y-m-d')));
            $prg->save();

            $students = student_program::where("program_id");
            LogActivity::addToLog("coordinator has modified consent form deadline for program " . $prg->name);
            return "true";
        } else {
            return "Please enter appropriate start and end date";
        }
    }

    public function give_std_subphase(Request $request) {
        $subphase = $request->input('subphase');
        $std_subphase = student_subphase::where('subphase_id', $subphase)
                        ->where('status', '!=', 'completed')->get();
        $students = array();
        foreach ($std_subphase as $std_sub) {
            $students[] = Student::find($std_sub->student_id);
        }
//        dd($students);
        $output = "";
        $output .= '<table id="subphase_datatable" class="display table table-striped table-bordered" >
                        <thead>
                        <tr>
                                <th class="_textcentre" >Sr.No</th>
                                <th class="_textcentre">Student ID</th>
                                <th class="_textcentre">Student Name</th>
                                <th class="_textcentre">Student Email</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 15px;align-content: center">';
        $c = 1;
        foreach ($students as $std) {
            $output .= '
                <tr>
                                <td class="text-center" style="color: black;font-size: 13px">' . $c . '</td>
                                <td class="text-center" style="color: black;font-size: 13px">' . $std->username . '</td>
                                <td class="text-center " style="color: black;font-size: 13px">' . $std->name . '</td>
                                <td class="text-center" style="color: black;font-size: 13px">' . $std->email . '</td>
                            </tr>';
            $c++;
        }
        $output .= '</tbody></table>';

        return $output;
    }

    public function give_student_sub(Request $request) {
        $subphase = $request->input('subphase');
        $prg_id = Session::get('coord_prg');

        $std_subphase = student_subphase::where('subphase_id', $subphase)
                        ->where('status', '!=', 'completed')->get();
        $students = array();
        $std_prg = student_program::where('program_id', $prg_id)
                        ->where('status', '!=', 'compelted')->get();
        $students_prg = array();
        foreach ($std_prg as $s) {
            $students_prg[] = Student::find($s->student_id);
        }
        foreach ($std_subphase as $std_sub) {
            $students[] = Student::find($std_sub->student_id);
        }
        $diff = array();
        foreach ($students_prg as $stds) {
            $diff[$stds->id] = 1;
        }
        foreach ($students as $stds) {
            $diff[$stds->id] = 0;
        }
        $d = array();
        foreach ($students_prg as $stds) {
            if ($diff[$stds->id] == 1) {
                $d[] = $stds;
            }
        }
        $data = array();
        $data["diff"] = $d;
        $data["sub"] = $students;
        // dd($students);
        return $data;
    }

    public function get_faculty_list(Request $request) {
        $faculty = Faculty::all();
        $output = '
                <select class="faculty">
                    <option value="n">Select Faculty</option>
                    ';
        foreach ($faculty as $f) {
            $output .= '<option value="' . $f->id . '">' . $f->name . '</option>';
        }
        $output.='</select>';
        return $output;
    }

    public function save_modified_mentor(Request $request) {
        $p_id = Session::get('coord_prg');
        $prg = program::find($p_id);
        $std_prg_id = $request->input("std_prg_id");
        $mentor = $request->input("mentor");
        $subject_expert = $request->input("subject_expert");
        $std_prg = student_program::find($std_prg_id);
        $student = Student::find($std_prg->student_id);
        $men = Faculty::find($mentor);
        $sub_exp = Faculty::find($subject_expert);
        $coordinator = coordinator::find($prg->coordinator_id)->username;
        if ($mentor != "n") {
            $std_prg->mentor_1 = $mentor;
            $std_prg->save();

            $data = [
                'student_name' => $student->name,
                'coordinator' => $coordinator,
                'mentor' => $men->name,
            ];
            $data2 = [
                'student' => $student,
                'mentor' => $men->name,
            ];
            $coo = coordinator::find(Auth::guard('coordinator')->id());
            sendMail::dispatch($student->email, "mentor has been changed", $data, "emails.studentChangeMentor", $coo->username, $coo->email);
            sendMail::dispatch($men->email, "student has been added", $data2, "emails.mentorAddedStudent", $coo->username, $coo->email);
        }
        if ($subject_expert != "n") {
            $std_prg->subject_expert = $subject_expert;
            $std_prg->save();
            $data3 = [
                'student_name' => $student->name,
                'coordinator' => $coordinator,
                'se' => $sub_exp->name,
            ];
            $data4 = [
                'se' => $sub_exp->name,
                'student' => $student,
            ];
            $coo = coordinator::find(Auth::guard('coordinator')->id());
            sendMail::dispatch($student->email, "subject Expert has been changed", $data3, "emails.studentChangeSubjectExpert", $coo->username, $coo->email);
            sendMail::dispatch($sub_exp->email, "Nominated as subject expert", $data4, "emails.SE_Added_Student", $coo->username, $coo->email);
        }
        if ($subject_expert == "n" && $mentor == "n") {
            return "false";
        }
    }

    public function student_consent_form_deadline(Request $request) {
        $validator = Validator::make($request->all(), [
                    'start_date' => 'required',
                    'end_date' => 'required',
                    'to' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        } else {
            $p_id = Session::get('coord_prg');
            $prg = program::find($p_id);
            $students = $request->input('to');
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');
            $coo = coordinator::find(Auth::guard('coordinator')->id());
            
            foreach ($students as $s) {
                $std_prg = student_program::find($s);
                $std = Student::find($std_prg->student_id);
                $std_id = $std->username;
                
                $std_prg->cf_start_date = $start_date;
                $std_prg->cf_end_date = $end_date;
                $std_prg->save();

                $data = [
                    'prg' => $prg->name,
                    'std' => $std_id,
                    's_date' => $start_date,
                    'e_date' => $end_date,
                ];
                 sendMail::dispatch($std->email, "Deadline For Consent form", $data, "emails.coordinator_consent_deadline_set", $coo->username, $coo->email);
           
            }
            return redirect()->back()->with('status', 'Dates of Consent form have been edited for selected students');
        }
    }

    public function remind_mentor_consent_form(Request $request) {
        $std_prg_id = $request->input('std_id');
        $std_prg = student_program::find($std_prg_id);

        $std_id = $std_prg->student_id;
        $student = Student::find($std_id);
        $student_id = $student->username;

        $prg_id = Session::get('coord_prg');
        $prg_name = program::find($prg_id)->name;

        $mentor_id = $std_prg->mentor_1;
        $mentor = Faculty::find($mentor_id);
        $mentor_name = $mentor->name;

        $email = $mentor->email;
        $data = [
            'prg_name' => $prg_name,
            'mentor_name' => $mentor_name,
            'student' => $student_id,
        ];

        //SEND MAIL TO faculty
        $coo = coordinator::find(Auth::guard('coordinator')->id());
        sendMail::dispatch($email, "Reminder to respond to consent form", $data, 'emails.coordinator_remind_mentor', $coo->username, $coo->email);
//        Mail::send('emails.coordinator_remind_mentor', $data, function($m) use ($email) {
//                $m->subject("Reminder to respond to consent form");
//                $m->to($email);
//            });
        return "true";
    }
    
    public function view_backend_email_template(){
        return view('coordinator/coordinator_view_backend_email_template');
    }
    
    public function get_backend_mail_template(Request $request){
        $fi = $request->input("template");
        $f = storage_path()."\app\public\mail_template" . "\\" . $fi;
        return show_source($f,TRUE);
    }

}
