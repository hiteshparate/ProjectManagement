@extends('layouts.coordinator')
@section('title')
View Mail template
@endsection
@section('css')
@endsection
@section('content')



<div class="row">
    <div class="col-xs-12" style="font-size: 15px">
        <select class="select2 mail_template" style="font-size: 13px; height: 38px;width: 100%">
            <option value="n">Select Email template to view</option>
            <option value="faculty_se_dates.blade.php">Subject Expert Nomination Date alert mail</option>
            <option value="mentorAddedStudent.blade.php">Coordinator change student's mentor mail( to mentor)</option>
            <option value="passwordReset.blade.php">Reset Password Mail</option>
            <option value="reject_on_behalf_of_se.blade.php">Coordinator rejects Reject on behalf subject Expert</option>
            <option value="reportReject.blade.php">Mentor rejects student's report</option>
            <option value="requestReject.blade.php">Mentor rejects request of student</option>
            <option value="SE_Added_Student.blade.php">Coordinator changes student's subject expert( to subject expert)</option>
            <option value="sendRequest.blade.php">Student sends mentor request to faculty member</option>
            <option value="studentChangeMentor.blade.php">Coordinator change student's mentor mail( to student)</option>
            <option value="studentChangeSubjectExpert.blade.php">Coordinator changes student's subject expert( to student)</option>
            <option value="sub_exp_accept.blade.php">Subject Expert accepts request</option>
            <option value="sub_exp_reject.blade.php">Subject Expert rejects request</option>
            <option value="subject_expert_reminder.blade.php">Coordinator sends reminder to subject expert to respond on subject expert request</option>
            <option value="sub_exp_reminder.blade.php">Coordinator sends reminder mail to mentor to appoint subject expert for student</option>
            <option value="accept_on_behalf_of_se.blade.php">Coordinator Accept request on behalf of subject expert</option>
            <option value="acceptReport.blade.php">Student Report for event is accepted by mentor</option>
            <option value="acceptRequest.blade.php">Student Project Request is accepted by mentor</option>
            <option value="addedToProgram.blade.php">Coordinator added student to program</option>
            <option value="addedToSubphase.blade.php">Coordinator added student to Subphase</option>
            <option value="addStudentToEvent.blade.php">Coordinator added student to Event</option>
            <option value="committee_member_added.blade.php">Coordinator added faculty in the committee</option>
            <option value="coordinator_consent_deadline_set.blade.php">Coordinator set deadline for student consent form</option>
            <option value="coordinator_remind_mentor.blade.php">Coordinator remind mentor to respond to student request</option>
            <option value="coordinator_remind_std.blade.php">Coordinator remind  student to fill the consent form</option>
            <option value="coordinator_req_acc.blade.php">Coordinator accept the project request of student on behalf of mentor</option>
            <option value="coordinator_req_rej.blade.php">Coordinator rejected the the project request of student on behalf of mentor</option>
            <option value="createProfile.blade.php">System create the profile of user</option>
            <option value="createProfileFaculty.blade.php">System create the profile of faculty</option>
            <option value="createProfileRCuser.blade.php">System create the profile of RC user</option>
            
        
        
        
        </select>
    </div>
    
</div>
<br>
<div class="row">
    <div class="col-xs-12 disp_centent" style="font-size: 15px">
        
    </div>
</div>
@endsection
@section('js')
<script src="{{ asset('js/coordinator_mail_template_backend.js') }}" type="text/javascript"></script>
@endsection
