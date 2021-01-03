Hi <?php echo $student->name."," ?> <br>
<br>

Your report for event <?php echo $event_name."," ?>is rejected by Prof. <?php echo $mentor_name ?>.Please submit report again on <a href="<?php echo env('APP_URL') ?>"><b>USPMES</b></a> website.<br>.
<b>Reason for Rejection :</b> <?php echo $reason?>


<br>
Thank you,<br>
USPMES System.<br>
<br>
<ul>
    <li>
        <h3>This is auto generated mail. Please do not Reply.</h3>
    </li>
</ul>