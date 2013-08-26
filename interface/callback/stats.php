<?php


require_once("../globals.php");
require_once("fetchLogin.inc.php");  
require_once("timeFunction.inc.php");


include_once("soap.inc.php");

	
$query = "SELECT * FROM call_reminders ORDER BY id DESC LIMIT 2"; 

 $results = sqlStatement($query);
 
   $bcid = array();
   while ($idrow = sqlFetchArray($results)){
       $bcid[] = $idrow['BroadCastId'];
	   
   }
 

$query = new stdclass();
$query->MaxResults = 100; // long   
$query->FirstResult = 0; // long   
$query->BroadcastId = $bcid[0]; // long   
$responses = $client->QueryCalls($query);


$tc = $responses->TotalResults . "<br>";
		echo "Total calls " . $tc  . "<br>";
		$rc = 0;
		$ni = 0;
		
if($responses->Call[0]->State == "READY" ){
    echo "No Results for today's calls. Please check back in 1 hour. ";
	
   } else {
       
       echo "<table border='0' width='55%' style='border-collapse: collapse'>";
       echo "<th>Number Called</th> <th>Confirm/Cancel</th> <th>Call Time</th> ";

			do {     
				  echo "<tr>";
					//echo $responses->Call[$ni]->State . "<br>";  This line to show that the call was finished
				  echo "<td>";
				  echo "<p align=center>" . $responses->Call[$ni]->ToNumber->{'_'} . "<br>";
				  echo "</td>";
				  echo "<td>";
						$qAnswer = $responses->Call[$ni]->CallRecord->QuestionResponse->Response ;
						$callTime = $responses->Call[$ni]->CallRecord->AnswerTime;
						if ($qAnswer != NULL && $callTime != 00){
							 echo "<p align=center>" . $qAnswer . "<br>" ;
						}  
						elseif($qAnswer == NULL && $callTime == 00){
							echo "<p align=center> Not Reached";
						}
						else {
							 echo "<p align=center>Voice Mail<br>";
						}

				  echo "</td>";
				  echo "<td>";
					list($c_date, $c_time) = explode("T", $callTime); //This was to bust up the time date return
					echo "<p align=center>" . DATE("H:i", STRTOTIME($c_time)) . "<br>";
				  echo "</td>";
					$rc++ ;
					$ni++;
				  echo "</tr>";
				} while ($rc < $tc);

			echo "</table><br>";
			echo "** Not Reached means the call did not connect. Manually call patient. ";
				}
	
echo "<br><br>";
	$query = new stdclass();
$query->MaxResults = 100; // long   
$query->FirstResult = 0; // long   
$query->BroadcastId = $bcid[1]; // long   
$responses = $client->QueryCalls($query);


$tc = $responses->TotalResults . "<br>";
		echo "Total previous calls " . $tc  . "<br>";
		$rc = 0;
		$ni = 0;
		
if($tc == 0 ){
    echo "No Results for yesterdays calls to be displayed. ";
	exit;
} else {
echo "<table border='0' width='55%' style='border-collapse: collapse'>";
echo "<th>Number Called</th> <th>Confirm/Cancel</th> <th>Call Time</th> ";

do {     
      echo "<tr>";
		//echo $responses->Call[$ni]->State . "<br>";  This line to show that the call was finished
	  echo "<td>";
	  echo "<p align=center>" . $responses->Call[$ni]->ToNumber->{'_'} . "<br>";
	  echo "</td>";
	  echo "<td>";
			$qAnswer = $responses->Call[$ni]->CallRecord->QuestionResponse->Response ;
			$callTime = $responses->Call[$ni]->CallRecord->AnswerTime;
			if ($qAnswer != NULL && $callTime != 00){
				 echo "<p align=center>" . $qAnswer . "<br>" ;
			}  
			elseif($qAnswer == NULL && $callTime == 00){
				echo "<p align=center> Not Reached";
			}
			else {
				 echo "<p align=center>Voice Mail<br>";
			}
	  echo "</td>";
	  echo "<td>";
		$callTime = $responses->Call[$ni]->CallRecord->AnswerTime;
		    list($c_date, $c_time) = explode("T", $callTime); //This was to bust up the time date return
		echo "<p align=center>" . DATE("H:i", STRTOTIME($c_time)) . "<br>";
	  echo "</td>";
		$rc++ ;
		$ni++;
	  echo "</tr>";
    } while ($rc < $tc);

echo "</table>";
    }

echo "<br>Done!";

?>