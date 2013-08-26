<?php
//TODO This needs to be made part of a configuration 
date_default_timezone_set('America/New_York');

//Sets the date function
$tzone = date("T");           //TODO Need this built into the configuration 
$day = date("l");
$today = date("Y-m-d");
$tomorrow = date("Y-m-d", strtotime("+1 day"));
$yesterday = date("Y-m-d", strtotime("-1 day"));
$weekend = date("Y-m-d", strtotime("+3 day"));
$sunCall = date("Y-m-d", strtotime("+2 day"));

//Round up the time for the Broadcast Schedule
$now = date("H:i:s");

$upOne = strtotime($now) + 48*48;
$startTime = date('H:i:s', $upOne);
$stopTime = '18:00:00';
//Force day to all caps
$arrayDay = strtoupper($day);

//Convert timezone:  

//TODO build out for world time zones   
switch ($tzone) {
     case "EDT":
	     $newZone = "EST";
		 break;

     case "PDT":
         $newZone = "PST";
		 break;

     case "CDT":
         $newZone = "CST";
         break;

     case "MDT":
         $newZone = "MST";
         break;
		 
}




?>