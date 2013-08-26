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
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../globals.php");
include("$srcdir/reminders.php");
require_once("fetchLogin.inc.php");
require_once("timeFunction.inc.php");

/**
$sql_query = sqlStatement ("SELECT `patient_data`.`pid`, `patient_data`.`fname`, `patient_data`.`lname`, `patient_data`.`phone_home`, " . 
                             "`patient_reminders`.`category`, `patient_reminders`.`item` " . 
							 "FROM `patient_data` " . 
							 "RIGHT OUTER JOIN `patient_reminders` ON `patient_data`.`pid` = `patient_reminders`.`pid` " .
							 "WHERE `patient_data`.`hipaa_voice` LIKE 'yes' ");

    $query_res = array();
	
     while($res = sqlFetchArray($sql_query)){
   
     $query_res[] = $res;
   
   }
**/

 $sql = sqlStatement ("SELECT p.pid, p.category, p.item, e.fname, e.lname, e.phone_home FROM patient_reminders p RIGHT OUTER JOIN patient_data e ON p.pid = e.pid WHERE e.hipaa_voice LIKE 'yes'");
 
	  $results = array();
     while($res = sqlFetchArray($sql)){

  			 $results[] = $res;
     
   }
 
  $grouped = array();
   foreach($results as $r){
        if(!isset($grouped[$r['pid']])){
		$grouped[$r['pid']][] = array();
      }
	  $grouped[$r['pid']][] = $r;
	}


 
echo "<pre>";

      var_dump($grouped[807]);
	  echo $grouped[807][1]['fname'] . "<br>";
	  echo $grouped[807][1]['lname'] . "<br>";
	  echo $grouped[807][1]['item'] . "<br>";
	  echo $grouped[807][2]['item'];
     
echo "</pre>";
exit;
include_once("soap.inc.php");

/**
 * CreateBroadcast.
 */
$request = new stdclass();
$request->Broadcast = new stdclass(); // required
$request->Broadcast->Name = $showFacility['facility'] .' - '. $today; // required
$request->Broadcast->Type = 'IVR'; // required
$request->Broadcast->IvrBroadcastConfig = new stdclass(); // required choice
$request->Broadcast->IvrBroadcastConfig->FromNumber = $FromNumber; //choice
$request->Broadcast->IvrBroadcastConfig->Create = $today; //choice
$request->Broadcast->IvrBroadcastConfig->RetryConfig->MaxAttempts = 2;
$request->Broadcast->IvrBroadcastConfig->RetryConfig->MinutesBetweenAttempts = 15;
$request->Broadcast->IvrBroadcastConfig->RetryConfig->RetryResults = 'BUSY';
$request->Broadcast->IvrBroadcastConfig->RetryConfig->RetryResults = 'NO_ANS';
$request->Broadcast->IvrBroadcastConfig->DialplanXml = 
'<dialplan name="Root">'.
'<menu name="Menu_Q1" maxDigits="1" timeout="3500">'.
'<play name="Play_Q1" type="tts" voice="female1">Hello, "' . $patientfname .
'This call is to remind you to come in for ' . $category_title . $item_title . ' at ' . $showFacility['facility'] . 
'Please call to make an appointment, have a great day, Goodbye'.
'</menu>'.
'<hangup/>'.
'</dialplan>'; // required

$broadcastId = $client->CreateBroadcast($request);

// Add a list of 2 numbers to campaign.
//
$request = new stdclass();
$request->BroadcastId = $broadcastId; // required  
$request->Name = $showFacility['facility'] . ' Clinical Reminders';   
$request->ToNumber = array($patientphone); // required choice 
$request->ContactListId = rand(10000001, 3000000); // required choice 
$request->ScrubBroadcastDuplicates = false;   
$contactBatchId = $client->CreateContactBatch($request);

exit;
//Start Call

$request = new stdclass();
$request->BroadcastId = $broadcastId; // required  
$request->BroadcastSchedule = new stdclass(); // required 
//$request->BroadcastSchedule->id = 1; // Unused.
$request->BroadcastSchedule->StartTimeOfDay = $startTime; // required  
$request->BroadcastSchedule->StopTimeOfDay = $stopTime; // required  
$request->BroadcastSchedule->TimeZone = $newZone; // required  
//$request->BroadcastSchedule->BeginDate = $today ;   
//$request->BroadcastSchedule->EndDate = $today ;   
$request->BroadcastSchedule->DaysOfWeek = $arrayDay; // required  [SUNDAY, MONDAY, TUESDAY, WEDNESDAY, THURSDAY, FRIDAY, SATURDAY]
$broadcastScheduleId = $client->CreateBroadcastSchedule($request);
echo "Broadcast is scheduled - broadcastScheduleId: " . $broadcastScheduleId . "</br>";



?>