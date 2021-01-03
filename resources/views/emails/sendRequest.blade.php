Hello <?php echo $mentor->name . "," ?> <br>
<br>
You have a new mentor request from student <?php echo $std->name ?>.Student and project details are as follows.<br>
<html>
    <head>
        <style>
            table,th,td {
                border: 1px solid black;
                border-collapse: collapse;
            }
        </style>
    </head>
    <body>
        <div class="col-xs-12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Student Name</th>
                        <th>Project Topic</th>
                        <th>Area of Interest</th>
                        <th>Project Type</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $std->username }}</td>
                        <td>{{ $std->name }}</td>
                        <td>{{ $program->project_topic }}</td>
                        <td>{{ $program->area_of_interest }}</td>
                        <td>{{ $program->project_type }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <br>
        Please take appropriate action for this event on <a href="<?php echo env('APP_URL') ?>"><b>USPMES</b></a> website.<br>.
        <br>
        Thank you,<br>
        USPMES System.<br>
        <br>
        <ul>
            <li>
                <h3>This is auto generated mail. Please do not Reply.</h3>
            </li>
        </ul>

    </body>
</html>

