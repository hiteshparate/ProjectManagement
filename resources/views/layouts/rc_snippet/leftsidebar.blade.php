<head>
    <style>
        /* width */
        ::-webkit-scrollbar {
            width: 5px;
        }

        /* Track */
        ::-webkit-scrollbar-track {
            background: #f1f1f1; 
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
            background: #888; 
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #555; 
        }
    </style>
</head>
<div class="left side-menu "  >
    <div class="sidebar-inner slimscrollleft temp" style="overflow-y: auto" >
        <!--- Divider -->
        <div id="sidebar-menu"  style="padding-top: 10px">
            <ul>
                <li>
                    <a href="{{ url('rc_view_reports') }}" class="waves-effect"><i class="glyphicon glyphicon-star"></i> <span style="font-size: 13px">View Reports </span> </a>
                </li>
                <?php
                $rc = Auth::guard('rc')->user();
                $rc_name = $rc->name;
                if ($rc_name == "rc_admin") {
                    ?>
                    <li>
                        <a href="{{ url('add_another_login') }}" class="waves-effect"><i class="glyphicon glyphicon-star"></i> <span style="font-size: 13px">Add members </span> </a>
                    </li>
                    <?php
                }
               
                ?>

            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
