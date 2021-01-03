<?php

namespace App\Http\Controllers\faculty;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\student_program;
use Illuminate\Support\Facades\Auth;
use App\program;
use App\off_campus_mentor;
use Illuminate\Support\Facades\Mail;
use App\student_group;
use App\Student_event;
use Illuminate\Support\Facades\Storage;
use App\Event;
use Illuminate\Support\Facades\Validator;
use App\student_mentor_grade;
use App\committee;
use App\committee_faculty;
use App\student_committee_grade;
use App\Jobs\sendMail;
use App\Helpers\LogActivity;

class facultyController extends Controller {

    public function get_mentor_request($prg) {
        Session::put('program', $prg);
        $mentor = Auth::guard('faculty')->id();
        $p_id = program::where('name', Session::get('program'))->first()->id;
        $data = array();
        $area_of_interest = \App\area_of_interest::all();
        $data[] = student_program::where(['program_id' => $p_id, 'mentor_1' => $mentor,])
                ->where('status', '!=', 'completed')
                ->get();
        $data[] = student_program::where(['program_id' => $p_id, 'mentor_2' => $mentor])
                ->where('status', '!=', 'completed')
                ->get();
        $group = student_group::where(['program_id' => $p_id, 'mentor_id' => $mentor])->distinct('name')->pluck('name');
//        $tmp = $data[0][0];
//        dd($tmp->groups()->where(['program_id' => $p_id, 'mentor_id' => $mentor])->get());
        return view('faculty/faculty_mentor_request')->with(['faculty_data' => $data,
        'groups' => $group,
        'p_id' => $p_id,
        'mentor' => $mentor,
        'area_of_interest' => $area_of_interest,
        ]);
    }


    public function get_off_campus_data(Request $request) {
        $off_campus_id = $request->input('id');
        $mentor = off_campus_mentor::find($off_campus_id);
        $output = '';
        $output .= '
                    <div class="form-group row m-t-10">
                        <div class="col-xs-5" >
                            <span name="m_name" class="font-bold" style="font-size: 14px" type="text">Off campus Mentor\'s Name</span>
                        </div>
                        <div class="col-xs-7" >
                            <span name="m_name" style="font-size: 14px" class=" off form-control" type="text" placeholder="Off Campus Mentor Name *">' . $mentor->name . '</span>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-xs-5" >
                            <span name="m_name" class="font-bold" style="font-size: 14px" type="text">Contact Number</span>
                        </div>
                        <div class="col-xs-7" >
                            <span name="m_name" style="font-size: 14px" class=" off form-control" type="text" placeholder="Off Campus Mentor Name *">' . $mentor->contact_number . '</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-xs-5" >
                            <span name="m_name" class="font-bold" style="font-size: 14px" type="text">Email</span>
                        </div>
                        <div class="col-xs-7" >
                            <span name="m_name" style="font-size: 14px" class=" off form-control" type="text" placeholder="Off Campus Mentor Name *">' . $mentor->email . '</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-xs-5" >
                            <span name="m_name" class="font-bold" style="font-size: 14px" type="text">Company Name</span>
                        </div>
                        <div class="col-xs-7" >
                            <span name="m_name" style="font-size: 14px" class=" off form-control" type="text" placeholder="Off Campus Mentor Name *">' . $mentor->company_name . '</span>
                        </div>
                    </div>                    
                    
               ';

        return $output;
    }

    public function accept_r(Request $request) {
        $p_id = program::where('name', Session::get('program'))->first()->id;
        $std_id = $request->input('std_id');
        $mentor_id = $request->input('mentor_id');
        $m1 = student_program::where(['program_id' => $p_id, 'student_id' => $std_id, 'mentor_1' => $mentor_id, 'status' => 'pending'])->first();
        $m2 = student_program::where(['program_id' => $p_id, 'student_id' => $std_id, 'mentor_2' => $mentor_id, 'status' => 'pending'])->first();
        if ($m1 != null) {
            $m1->status = "accepted";
            $m1->save();
        }
        if ($m2 != null) {
            $m2->status = "accepted";
            $m2->save();
        }
        $std = \App\Student::find($std_id);
        $data = [
            'student' => $std,
            'mentor' => \App\Faculty::find($mentor_id),
        ];
        $email = $std->email;
        LogActivity::addToLog('Faculty accepted request of '.$std->username);
        sendMail::dispatch($email, "Project Request Accepted" , $data,'emails.acceptRequest',null,null);
//        Mail::send('emails.acceptRequest', $data, function($m) use ($email) {
//            $m->subject("Project Request Accepted");
//            $m->to($email);
//        });
    }

    public function reject_r(Request $request) {
        $p_id = program::where('name', Session::get('program'))->first()->id;
        $std_id = $request->input('std_id');
        $mentor_id = $request->input('mentor_id');
        $m1 = student_program::where(['program_id' => $p_id, 'student_id' => $std_id, 'mentor_1' => $mentor_id, 'status' => 'pending'])->first();
        $m2 = student_program::where(['program_id' => $p_id, 'student_id' => $std_id, 'mentor_2' => $mentor_id, 'status' => 'pending'])->first();
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
        }
        if ($m2 != null) {
            $m2->mentor_2 = null;
            $m2->save();
        }
        $mentor_name = \App\Faculty::find($mentor_id)->name;
        $email = \App\Student::find($std_id)->email;
        $data = ['mentor_name' => $mentor_name];
        sendMail::dispatch($email, "Request Rejected", $data, 'emails.requestReject',null,null);
        LogActivity::addToLog('Faculty rejected request of '. \App\Student::find($std_id)->username);
//        
//        Mail::send('emails.requestReject', $data, function($m) use ($email) {
//            $m->subject('Request Rejected');
//            $m->to($email);
//        });

        //  return redirect()->route('/login');
    }

    public function create_group(Request $request) {
//        $group_id = $request->input('group_id');
        $group_name = $request->input('group_name');
        $std_id = $request->input('std_id');
        $mentor = Auth::guard('faculty')->id();
        $p_id = program::where('name', Session::get('program'))->first()->id;
        $group = new student_group();
        $group->name = $group_name;
        $group->student_id = $std_id;
        $group->mentor_id = $mentor;
        $group->program_id = $p_id;
        $group->save();
        LogActivity::addToLog('Faculty added students to the group '.$group_name);
        return "success";
    }

    public function get_project_details() {
        $mentor = Auth::guard('faculty')->id();
        $p_id = program::where('name', Session::get('program'))->first()->id;
        $students[] = student_program::where(['mentor_1' => $mentor, 'program_id' => $p_id, 'status' => 'accepted'])->get();
        $students[] = student_program::where(['mentor_2' => $mentor, 'program_id' => $p_id, 'status' => 'accepted'])->get();
        $events = array();
//        dd($students);
        foreach ($students as $std) {
            foreach ($std as $student) {
                $id = $student->student_id;
//            echo $id . "\n";
                array_push($events, \App\Student_event::where(['student_id' => $id, 'program_id' => $p_id])->get());
            }
        }
        return view('faculty/faculty_project_details')->with(['event_data' => $events]);
    }

    public function get_report($id, $s_id) {
        $s_events = Student_event::where(['student_id' => $s_id, 'event_id' => $id])->first();
        $file = $s_events->report_location;
        return response()->file(storage_path() . $file);
    }
    public function get_plag_report($id, $s_id) {
        $s_events = Student_event::where(['student_id' => $s_id, 'event_id' => $id])->first();
        $file = $s_events->plagiarism_report;
        return response()->file(storage_path() . $file);
    }
    

    public function accept_report(Request $request) {
        $std_id = $request->input("id");
        $event_id = $request->input("event_id");
        $p_id = program::where('name', Session::get('program'))->first()->id;
        $event = Student_event::where(['student_id' => $std_id, 'event_id' => $event_id, 'program_id' => $p_id])->first();
        $event->isAccepted = 1;
        $event->status = "accepted";
        $event->save();
        $email = \App\Student::find($std_id)->email;
        $s = \App\Student::find($std_id);
        $data = [
            'event_name' => Event::find($event_id)->name,
            'student' => $s->name,
            'mentor' => Auth::guard('faculty')->user()->name,
        ];
        sendMail::dispatch($email, "Report Accepted", $data,'emails.acceptReport',null,null );
        LogActivity::addToLog('Faculty has accepted report for '.Event::find($event->event_id)->name.' event of student '.$s->username);
//        Mail::send('emails.acceptReport', $data, function($m) use ($email) {
//            $m->subject('Report Accepted');
//            $m->to($email);
//        });

        return "true";
    }

    public function reject_report(Request $request) {
        $std_id = $request->input("id");
        $event_id = $request->input("event_id");
        $reason = $request->input("reason");
        $p_id = program::where('name', Session::get('program'))->first()->id;
        $event = Student_event::where(['student_id' => $std_id, 'event_id' => $event_id, 'program_id' => $p_id])->first();
        $filename = storage_path() . $event->report_location;
        unlink($filename);
        $s = \App\Student::find($std_id);
        $email = $s->email;
        $data = [
            'reason' => $reason,
            'mentor_name' => $mentor = Auth::guard('faculty')->user()->name,
            'event_name' => Event::find($event_id)->name,
        ];
        
        sendMail::dispatch($email, "Report Rejected" , $data, 'emails.reportReject',null,null);
//        Mail::send('emails.reportReject', $data, function($m) use ($email) {
//            $m->subject('Report Rejected');
//            $m->to($email);
//        });
        $event->isAccepted = 0;
        $event->status = "pending";
        $event->report_location = "";
        $event->save();
        
        LogActivity::addToLog('Faculty has rejected report for '.Event::find($event->event_id)->name.' event of student '.$s->username);
        return "true";
    }

    public function get_manage_groups() {
        $mentor = Auth::guard('faculty')->id();
        $p_id = program::where('name', Session::get('program'))->first()->id;
        $groupdata = student_group::where(['program_id' => $p_id, 'mentor_id' => $mentor])->distinct('name')->pluck("name");
        return view('faculty/faculty_manage_groups')->with(['group' => $groupdata]);
    }

    public function delete_group(Request $request) {
        $p_id = program::where('name', Session::get('program'))->first()->id;
        $mentor = Auth::guard('faculty')->id();
        $group = $request->input('group_name');
        $m1 = student_group::where(['program_id' => $p_id, 'mentor_id' => $mentor, 'name' => $group])->delete();
        
        LogActivity::addToLog('Faculty has deleted '.$group.' group');
    }

    public function store_report(Request $request, $e_id, $student_id) {
        $mentor = Auth::guard('faculty')->id();
        $std = $student_id;
        $event = Student_event::where(['student_id' => $std, 'event_id' => $e_id])->first();
        $extension = $event->event->subphase->file_extension;
        $size = $event->event->subphase->file_size * 1024;
        $validator = Validator::make($request->all(), [
                    'report' => "required|mimes:$extension|file|max:$size"
        ]);
        if ($validator->fails()) {
            return redirect('/project_details')->withErrors($validator);
        } else {
            if ($request->file('report')->isValid()) {
                $file = $request->file('report');
                $name = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $phase = $event->event->subphase->phase->phase_name;
                $subphase = $event->event->subphase->name;
                $e = $event->event->name;
                $program = $event->event->subphase->phase->program->name;
                $year = $event->event->subphase->phase->program->year;
                $storage_path = "public/" . $program . "_" . $year . "/" . $phase . "/" . $subphase . "/" . $e;
                $path = $request->file('report')->storeAs(
                        $storage_path, $name);

                $event->report_location = '/app/' . $path;
                $event->status = "accepted";
                $event->isAccepted = 1;
                $event->save();
                LogActivity::addToLog('Faculty has manually upload report for '.\App\Student::find($student_id)->username.' for event '.$e);
                return redirect('/project_details')->with('status', 'Report Submitted Successfully');
            }else{
                
            }
        }
    }

    public function modified_std_data(Request $request) {
        $p_id = program::where('name', Session::get('program'))->first()->id;
        $project_topic = $request->input("project_topic");
        $aoi = $request->input("area_of_interest");
        $std_id = $request->input("std_id");
        $date = $request->input("date");

        $std = student_program::where(['student_id' => $std_id, 'program_id' => $p_id])->first();
        $std->project_topic = $project_topic;
        $std->area_of_interest = $aoi;
        $std->project_start_date = $date;
        $std->save();
        LogActivity::addToLog('Faculty has modiefied project details of student  '.\App\Student::find($std_id)->username);
        return "true";
    }

    public function get_grading() {
        $mentor = Auth::guard('faculty')->id();
        $p_id = program::where('name', Session::get('program'))->first()->id;
        $students[] = student_program::where(['mentor_1' => $mentor, 'program_id' => $p_id, 'status' => 'accepted'])->get();
        $students[] = student_program::where(['mentor_2' => $mentor, 'program_id' => $p_id, 'status' => 'accepted'])->get();
        $subphase = array();
        foreach ($students as $student) {
            foreach ($student as $s) {
                $id = $s->student_id;
//                echo $id . "\n";
                array_push($subphase, \App\student_subphase::where(['student_id' => $id])->get());
            }
        }
        $all_students = array();
        foreach ($subphase as $std_sub) {
            foreach ($std_sub as $sub) {
                $s = student_program::where([
                            'program_id' => $p_id,
                            'student_id' => $sub->student_id,
                        ])->first();
                if ($s->status != "completed") {
                    $tmp = array();
                    $tmp["sub"] = $sub;
                    $tmp["grade"] = \App\grade::where("g_id",\App\Subphase::find($sub->subphase_id)->g_id)->get();
                    $all_students[] = $tmp;
                }
            }
        }
        $committee_faculty = committee_faculty::where('faculty_id', $mentor)->get(["committee_id"]);
        $final_committee_id = array();
        foreach ($committee_faculty as $cm) {
            if (committee::find($cm->committee_id)->program_id == $p_id) {
                $final_committee_id[] = $cm->committee_id;
            }
        }
        $com_students = array();
        foreach ($final_committee_id as $fc) {
            $com_students[] = student_program::where(['committee_id' => $fc, 'status' => "accepted"])->get();
        }
        $committee_subphase_students = array();
        foreach ($com_students as $std) {
            foreach ($std as $cs) {
                $id = $cs->student_id;
                array_push($committee_subphase_students, \App\student_subphase::where(['student_id' => $id])->get());
            }
        }

        $final_committee_students = array();
        foreach ($committee_subphase_students as $css) {
            foreach ($css as $com_std) {
                $s = student_program::where([
                            'program_id' => $p_id,
                            'student_id' => $com_std->student_id,
                        ])->first();
                $c_s = \App\Subphase::find($com_std->subphase_id);
                if ($s->status != "completed" && $c_s->evaluation_committee == 1) {
                    $tmp = array();
                    $tmp["sub"] = $com_std;
                    $tmp["grade"] = \App\grade::where("g_id",$c_s->g_id)->get();
                    $final_committee_students[] = $tmp;
                }
            }
        }
        
//        $grade = \App\grade::where('g_id', program::where('name', Session::get('program'))->first()->grading_system)->get();

        return view('faculty/faculty_grading')->with([
                    "subphase" => $all_students,
                    "mentor" => Auth::guard('faculty')->id(),
                    "committee_std" => $final_committee_students,
                    "program_id" => $p_id,
        ]);
    }

    public function save_grade_mentor(Request $request) {
        $mentor = Auth::guard('faculty')->id();
        $s_id = $request->input('s_id');
        $grade = $request->input('grade');
        if ($grade == "select") {
            return "false";
        } else {
            $std = student_mentor_grade::where(["subphase_std_id" => $s_id, "mentor_id" => $mentor])->first();
            if ($std == null) {
                $std_grd = new student_mentor_grade();
                $std_grd->subphase_std_id = $s_id;
                $std_grd->grade = $grade;
                $std_grd->mentor_id = $mentor;
                $std_grd->save();
            } else {
                $std->grade = $grade;
                $std->save();
            }

            return "true";
        }
    }

    public function submit_grade_mentor(Request $request) {
        $mentor = Auth::guard('faculty')->id();
        $s_id = $request->input('s_id');
        $grade = $request->input('grade');
        if ($grade == "select") {
            return "false";
        } else {
            $std = student_mentor_grade::where(["subphase_std_id" => $s_id, "mentor_id" => $mentor])->first();
            if ($std == null) {
                $std_grd = new student_mentor_grade();
                $std_grd->subphase_std_id = $s_id;
                $std_grd->grade = $grade;
                $std_grd->mentor_id = $mentor;
                $std_grd->isFinal = 1;
                $std_grd->save();
            } else {
                $std->grade = $grade;
                $std->isFinal = 1;
                $std->save();
            }
            $s = \App\Student::find(\App\student_subphase::find($s_id)->student_id)->username;
            $subphase = \App\Subphase::find(\App\student_subphase::find($s_id)->subphase_id)->name;
            $prg = program::where('name', Session::get('program'))->first()->name;
            LogActivity::addToLog('Faculty has submitted grade '. $grade .' to student '.$s.' in '.$subphase.' for '.$prg.' program');
            return "true";
        }
    }

    public function save_mentor_comments(Request $request) {
        $mentor = Auth::guard('faculty')->id();
        $s_id = $request->input('s_id');
        $comment = $request->input('comment');
        $std = student_mentor_grade::where(["subphase_std_id" => $s_id, "mentor_id" => $mentor])->first();
//        dd($std);
        if ($std == null) {
            $std_grd = new student_mentor_grade();
            $std_grd->subphase_std_id = $s_id;
            $std_grd->comment = $comment;
            $std_grd->mentor_id = $mentor;
            $std_grd->save();
        } else {
            $std->comment = $comment;
            $std->save();
        }
        return "true";
    }

    public function get_mentor_comment(Request $request) {
        $mentor = Auth::guard('faculty')->id();
        $s_id = $request->input('s_id');
        $std = student_mentor_grade::where(["subphase_std_id" => $s_id, "mentor_id" => $mentor])->first();
        return $std->comment;
    }

    public function get_mentor_view_report($std_sub_id) {
        $mentor = Auth::guard('faculty')->id();
//        $sub_std_id = $request->input('sub_std_id');
        $sub_std = \App\student_subphase::find($std_sub_id);
        $p_id = program::where('name', Session::get('program'))->first()->id;
        $std_prg = student_program::where(['program_id' => $p_id, 'student_id' => $sub_std->student_id])->first();
        if ($std_prg->mentor_1 == $mentor || $std_prg->mentor_2 == $mentor) {
            $events = Event::where(['subphase_id' => $sub_std->subphase_id, 'submission' => 1])->get();
            // dd($events);
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
            return redirect('/mentor_grading');
        }
    }

    public function get_subject_expert_nomination() {
        $mentor = Auth::guard('faculty')->id();
        $p_id = program::where('name', Session::get('program'))->first()->id;
        $students = student_program::where([
                    'program_id' => $p_id,
                    'status' => "accepted",
                    'mentor_1' => $mentor,
                ])->get();
        $nominations = student_program::where([
                    'program_id' => $p_id,
                    'status' => 'accepted',
                    'subject_expert' => $mentor,
                ])->get();
        $faculty = \App\Faculty::all();
        return view('faculty/faculty_se_nomination')->with([
                    'students' => $students,
                    'nomination_request' => $nominations,
                    'faculty' => $faculty,
        ]);
    }

    public function subject_expert_nomination(Request $request) {
        $sub_exp = $request->input('sub_exp');
        $p_id = $request->input('program_id');
        $std_prg = student_program::find($p_id);
        $std_prg->subject_expert = $sub_exp;
        $std_prg->save();
        LogActivity::addToLog('Faculty has nominated '.\App\Faculty::find($sub_exp)->name.' for student '.\App\Student::find($std_prg->student_id)->username.' for '.program::find($std_prg->program_id)->name.' program');
        return "true";

        //SEND MAIL TO NOMINEE FACULTY
    }

    public function accept_se_nomination(Request $request) {
        $std_prg_id = $request->input('std_prg_id');
        $std_prg = student_program::find($std_prg_id);
        $std_prg->is_final_se = 1;
        $std_prg->save();
        //Mail to be sent to accept request
        $mentor = Auth::guard('faculty')->user();
        $email = $mentor->email;
        $data = [
            'mentor' => \App\Faculty::find($std_prg->mentor_1),
            'std' => \App\Student::find($std_prg->student_id),
            'sub_exp' => $mentor->name,
        ];
        sendMail::dispatch($email,"subject expert nomination request accepted",$data,"emails.sub_exp_accept",null,null);
        $prg = program::find($std_prg->program_id)->name;
        LogActivity::addToLog('Faculty has accepted subject expert nomination  for '.$prg.' program');
        return "true";
    }

    public function reject_se_nomination(Request $request) {
        $std_prg_id = $request->input('std_prg_id');
        $std_prg = student_program::find($std_prg_id);
        $std_prg->is_final_se = 0;
        $std_prg->subject_expert = null;
        $std_prg->save();
        $prg = program::find($std_prg->program_id)->name;
        LogActivity::addToLog('Faculty has rejected subject expert nomination  for '.$prg.' program');
        //Mail to be sent to accept request
        $mentor = Auth::guard('faculty')->user();
        $email = $mentor->email;
        $data = [
            'mentor' => \App\Faculty::find($std_prg->mentor_1),
            'std' => \App\Student::find($std_prg->student_id),
            'sub_exp' => $mentor->name,
        ];
        sendMail::dispatch($email,"subject expert nomination request rejected",$data,"emails.sub_exp_reject",null,null);
        
        return "true";
    }

    public function get_bid_project() {
        $mentor = Auth::guard('faculty')->id();
        $p_id = program::where('name', Session::get('program'))->first()->id;
        $max_bid = program::find($p_id)->bid_max_limit;
        $min_bid = program::find($p_id)->bid_min_limit;
        $no_of_bid = \App\bid_request::where([
                    'faculty_id' => $mentor,
                    'program_id' => $p_id,
                ])->count();
        $students = student_program::where([
                    'program_id' => $p_id,
                    'status' => "accepted",
                ])->where('mentor_1', '!=', $mentor)->get();
        return view('faculty/faculty_bid_requests')->with([
                    'students' => $students,
                    'mentor' => $mentor,
                    'max_bid' => $max_bid,
                    'min_bid' => $min_bid,
                    'no_of_bid' => $no_of_bid,
        ]);
    }

    public function save_bid_request(Request $request) {
        $std_prg_id = $request->input('std_prg_ids');
        if ($std_prg_id == null) {
            return "null";
        }
        $mentor = Auth::guard('faculty')->id();
        $p_id = program::where('name', Session::get('program'))->first()->id;
        $prv_bids = \App\bid_request::where([
                    'faculty_id' => $mentor,
                    'program_id' => $p_id,
                ])->get();
        $total_bids = $prv_bids->count();
        $max_bid = program::find($p_id)->bid_max_limit;
        $min_bid = program::find($p_id)->bid_min_limit;
        $new = array();
//        dd($prv_bids);
        foreach ($std_prg_id as $std) {
            foreach ($prv_bids as $pb) {
                if ($pb->std_prg_id == $std) {
                    unset($std_prg_id[array_search($std, $std_prg_id)]);
                }
            }
        }
//        dd($std_prg_id);
        $tot = sizeof($std_prg_id) + $total_bids;
//        dd($tot);
        if ($tot < $min_bid) {
            return "less";
        } else if ($tot > $max_bid) {
            return "more";
        } else {
            foreach ($std_prg_id as $n) {
                $b = new \App\bid_request();
                $b->program_id = $p_id;
                $b->faculty_id = $mentor;
                $b->std_prg_id = $n;
                $b->save();
            }
            return "true";
        }
    }

    public function save_grade_committee(Request $request) {
        $s_id = $request->input('s_id');
        $grade = $request->input('grade');
        $com_fac_id = $request->input('com_fac_id');
        if ($grade == "select") {
            return "false";
        } else {
            $std = student_committee_grade::where(["subphase_std_id" => $s_id, "committee_faculty_id" => $com_fac_id])->first();
            if ($std == null) {
                $std_grd = new student_committee_grade();
                $std_grd->subphase_std_id = $s_id;
                $std_grd->grade = $grade;
                $std_grd->committee_faculty_id = $com_fac_id;
                $std_grd->save();
            } else {
                $std->grade = $grade;
                $std->save();
            }
            return "true";
        }
    }

    public function submit_grade_committee(Request $request) {
        $s_id = $request->input('s_id');
        $grade = $request->input('grade');
        $com_fac_id = $request->input('com_fac_id');
        if ($grade == "select") {
            return "false";
        } else {
            $std = student_committee_grade::where(["subphase_std_id" => $s_id, "committee_faculty_id" => $com_fac_id])->first();
            if ($std == null) {
                $std_grd = new student_committee_grade();
                $std_grd->subphase_std_id = $s_id;
                $std_grd->grade = $grade;
                $std_grd->committee_faculty_id = $com_fac_id;
                $std_grd->isFinal = 1;
                $std_grd->save();
            } else {
                $std->grade = $grade;
                $std->isFinal = 1;
                $std->save();
            }
            $s = \App\Student::find(\App\student_subphase::find($s_id)->student_id)->username;
            $subphase = \App\Subphase::find(\App\student_subphase::find($s_id)->subphase_id)->name;
            $prg = program::where('name', Session::get('program'))->first()->name;
            LogActivity::addToLog('Faculty has given '. $grade .' to the student '. $s .' in the subphase '. $subphase .' of program '. $prg .' as committee member ');
            return "true";
        }
    }

    public function committee_comment(Request $request) {
        $s_id = $request->input('s_id');
        $comment = $request->input('comment');
        $com_fac_id = $request->input('com_fac_id');
        $std = student_committee_grade::where(["subphase_std_id" => $s_id, "committee_faculty_id" => $com_fac_id])->first();
        if ($std == null) {
            $std_grd = new student_committee_grade();
            $std_grd->subphase_std_id = $s_id;
            $std_grd->comment = $comment;
            $std_grd->committee_faculty_id = $com_fac_id;
            $std_grd->save();
        } else {
            $std->comment = $comment;
            $std->save();
        }
        return "true";
    }

}
