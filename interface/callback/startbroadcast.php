<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("../globals.php");
require_once("../../library/patient.inc");
require_once("fetchLogin.inc.php");  //registration information include
require_once("timeFunction.inc.php"); 

include_once("soap.inc.php");

$broadcastId = 1053335001;

		$request = new stdclass();
		$request->BroadcastId = $broadcastId; // required  
		$request->BroadcastSchedule = new stdclass(); // required 
		//$request->BroadcastSchedule->id = 1; // Unused.
		$request->BroadcastSchedule->StartTimeOfDay = $startTime; // required  
		$request->BroadcastSchedule->StopTimeOfDay = '18:00:00'; // required  
		$request->BroadcastSchedule->TimeZone = $newZone; // required  
		//$request->BroadcastSchedule->BeginDate = $today ;   
		//$request->BroadcastSchedule->EndDate = $today ;   
		$request->BroadcastSchedule->DaysOfWeek = $arrayDay; // required  [SUNDAY, MONDAY, TUESDAY, WEDNESDAY, THURSDAY, FRIDAY, SATURDAY]
		$broadcastScheduleId = $client->CreateBroadcastSchedule($request);
		echo "Broadcast is scheduled - broadcastScheduleId: " . $broadcastScheduleId . "</br>";
		

?>