//Call array ends here
echo "If you are seeing this screen please contact tech support.";
$response = $client->CreateContactList($request);
echo "Confirmation Code is " . $response;

echo "<br> Upload Completed </br>";
//Build the broadcast

$request = new stdclass();
$request->Broadcast = new stdclass(); // required
$request->Broadcast->Name = $showFacility['facility'] .' -'. $today; // required
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
'<play name="Play_Q1" type="tts" voice="female1">Hello, '.
'We are calling to confirm you have a doctors appointment tomorrow at ' . $showFacility['facility'] . 
'Please Press 1 '.
'to confirm appointment and not be billed for missed appointment.'.
'Press 2 to cancel appointment.</play>'.
'<keypress name="Keypress_Q1R1" pressed="1">'.
'<stash varname="Q1R1">Confirm</stash>'.
'<play name="play_confirm" type="tts" voice="female1">Thank you. We will
see you tomorrow at ' . $showFacility['facility'] .
' Goodbye.</play>'.
'</keypress>'.
'<keypress name="Keypress_Q1R2" pressed="2">'.
'<stash varname="Q1R2">Cancel</stash>'.
'<play name="play_cancel" type="tts" voice="female1">Thank you. You will
receive a call to reschedule your appointment. Goodbye.</play>'.
'</keypress>'.
'</menu>'.
'<hangup/>'.
'</dialplan>'; // required

$broadcastId = $client->CreateBroadcast($request);
echo "Broadcast Created - broadcastId:= " . $broadcastId . "</br>";


//Add uploaded contacts to the broadcast.

$request = new stdclass();
$request->BroadcastId = $broadcastId; // required  
$request->Name = $showFacility['facility'] . $today;   
$request->ContactListId = $response; // required choice 
$request->ScrubBroadcastDuplicates = true;   
$contactBatchId = $client->CreateContactBatch($request);
echo "Contacts associated to broadcast - contactBatchId: " . $contactBatchId . "</br>";



// Start the broadcast

if ($day != Friday ){
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
	
  }
    else {

		$request = new stdclass();
		$request->BroadcastId = $broadcastId; // required  
		$request->BroadcastSchedule = new stdclass(); // required 
		//$request->BroadcastSchedule->id = 1; // Unused.
		$request->BroadcastSchedule->StartTimeOfDay = '10:00:00'; // required  
		$request->BroadcastSchedule->StopTimeOfDay = '18:00:00'; // required  
		$request->BroadcastSchedule->TimeZone = 'EST'; // required  
		//$request->BroadcastSchedule->BeginDate = $sunCall ;   
		//$request->BroadcastSchedule->EndDate = $sunCall ;   
		$request->BroadcastSchedule->DaysOfWeek = array('SUNDAY'); // required  [SUNDAY, MONDAY, TUESDAY, WEDNESDAY, THURSDAY, FRIDAY, SATURDAY]
		$broadcastScheduleId = $client->CreateBroadcastSchedule($request);
		echo "Broadcast is scheduled for Sunday - broadcastScheduleId: " . $broadcastScheduleId . "</br>";
	
	
  }

  sqlStatement("INSERT INTO call_reminders ( BroadCastId, Contact_Batch, batch_date ) VALUES ( $broadcastId, $response, CURDATE() )");

//Redirect back to first page. Comment out to stop on this page.
header("Location: pull_appt.php");




?>