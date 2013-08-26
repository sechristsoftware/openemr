<?php

require_once("../globals.php");
require_once("../../library/patient.inc");
require_once("fetchLogin.inc.php");  //registration information include
require_once("timeFunction.inc.php"); 


include_once("soap.inc.php");
	

//
// First lets create contact list from list of contacts.
//
   $pi = getProviderInfo();    //retrieve the facility name
                               //Load the facility name into a variable
   foreach ($pi as $provider){
     $showFacility['facility'] = $provider['facility'];
  }

$request = new stdClass();
$request->Name = $showFacility['facility'] . $today; // string required  
$request->ContactSource = new stdclass(); //  required  
$request->ContactSource->Contact = array(); //required choice
//calling array starts here
