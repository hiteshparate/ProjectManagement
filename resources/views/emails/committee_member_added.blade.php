Hi <?php echo $faculty->name."," ?> <br>
<br>
You are added to the evaluation committee for <?php echo $student->name."(".$student->username.")" ?> in <?php echo $program?>.<br>
Other Committee Members are:<br>
<?php 

for($a=0 ; $a < sizeof($mem)-1 ; $a++){
    echo $mem[$a]->name.", ";
}
echo $mem[sizeof($mem)-1];
?>
Please Login  to <a href="<?php echo env('APP_URL') ?>"><b>USPMES</b></a> website for further process.
<br>
Thank you,<br>
USPMES System.<br>
<br>
<ul>
    <li>
        <h3>This is auto generated mail. Please do not Reply.</h3>
    </li>
</ul>

