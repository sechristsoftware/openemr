<?php

// +-----------------------------------------------------------------------------+
// Copyright (C) 2013 OMP <sgaddis@jse.net>
//
// 
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
//
// A copy of the GNU General Public License is included along with this program:
// openemr/interface/login/GnuGPL.html
// 
//
// Author:   Sherwin Gaddis <sgaddis@jse.net>
//           Nathan Srinivas <seenu4043@gmail.com>
//           
// Anyone that would like to be paid for adding features to this software,
// please contact us.
//
// +------------------------------------------------------------------------------+


require_once("../globals.php");
require_once("../../library/patient.inc");
require_once "$srcdir/appointments.inc.php";
require_once("$srcdir/formatting.inc.php");
require_once "$srcdir/formdata.inc.php";
require_once("fetchLogin.inc.php");
require_once("timeFunction.inc.php");


//Test if the day is the end of the week to pull appointments for Monday
if($day != 'Friday') {
       $from_date = $tomorrow;
	   $to_date = $tomorrow;
     } else {
	    $from_date = $weekend;
		$to_date = $weekend;
	 }

if($day )


//Check to see if there has been a batch recorded for today
$query = "SELECT batch_date FROM call_reminders ORDER BY id DESC LIMIT 1";
  $res = sqlStatement($query);
    $BatchDate = array();
   
   while ($brow = sqlFetchArray($res)){
       $BatchDate[] = $brow['batch_date'];
	   $Broadcast[] = $brow['BroadCastId'];
   }



?>


<html>

<head>
<?php html_header_show();?>
<link rel="stylesheet" href='<?php  echo $css_header ?>' type='text/css'>
<title>
Appointment Reminder Calls
</title>


</script>

</head>
<body class="body_top">

<h2>OMP Appt Reminder</h2>

<?php

       //Shows the registration form 
   if ($Username == null || $Password == null){
     echo "Please register software <br>";
	 include("register.php");
	 exit;
   }
   
  //Displays that call reminders have been done for today and pulls in the results page
   foreach($BatchDate as $lastDate){
       if ($lastDate == $today ){
      echo "Call reminders for today have been scheduled <br><br>";
	  
	  include "stats.php";
	  //include "yesterday.php";
	   exit;
	    }
	  }

//Calls function to fetch appointments array
$appointments = fetchAppToCall( $from_date, $to_date);
 
if(empty($appointments)){
   echo "There are no appointments on the calendar for tomorrow <br>";
   exit;
  }
//builds the call back array to upload to CallFire
    $callBacks = array();
	$i=0;	
foreach ($appointments as $appointment) {
  if($appointment['phone_home'] == null){ 
             echo  $appointment['fname'] . " " . $appointment['lname'] . " is missing phone number in home phone. If there is a mobile number, copy it to home phone block.  <br>"; 
			 exit;
			 } else {
 $callBacks[] = '$request'."->ContactSource->Contact[$i] = new stdClass()"  . ";\n";
 $callBacks[] = '$request'."->ContactSource->Contact[$i]->firstName = " . "'".$appointment['fname']."'" . ";\n";
 $callBacks[] = '$request'."->ContactSource->Contact[$i]->lastName = ". "'".$appointment['lname']."'" . ";\n";
 $callBacks[] = '$request'."->ContactSource->Contact[$i]->mobilePhone = " . "'".str_replace( "-", "", $appointment['phone_home'])."'" . ";\n";
 $callBacks[] = '$request'."->ContactSource->Contact[$i]->appTime = " . "'".DATE("H:i", STRTOTIME($appointment['pc_startTime']))."'" . ";\n";								  
						
						  $i++;
     }
   }
   
//Creates the call back file on the fly
  $startFile = file_get_contents('upload_header.php', true);
  
  $endFile = file_get_contents('upload_footer.php', true);
  
    file_put_contents('callbacks.inc.php', $startFile, true);
	
	file_put_contents('callbacks.inc.php', implode('',$callBacks), FILE_APPEND );
	
	file_put_contents('callbacks.inc.php', $endFile, FILE_APPEND );
	
echo "Call batch is being uploaded to the call center. <br> <br> Do not navigate from this page till it reloads."	
 
 
 

?>
<!-- Use this line for troubleshooting or to make system manual -->
<!-- <a href="callbacks.inc.php">Click to Schedule Calls</a> --> 
<?php header("Location: callbacks.inc.php");  //Use this line to make to automate the process
      
   ?>
 </body>
</html>