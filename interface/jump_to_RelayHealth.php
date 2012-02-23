<?php
// Copyright (C) 2008 Phyaura, LLC <info@phyaura.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

include_once('globals.php');
include_once('../includes/phyaura.inc.php');
include_once('../library/auth.inc');
include_once('../library/relayhealth.inc.php');
ini_set("display_errors", 0);
error_reporting(0);
set_time_limit(0);

if( $GLOBALS['rh_api'] && $_SESSION['rh_api_id'] != "" ) {
    $errdiv = '<div style="background-color:#FFFAAE; border:2px solid red; padding:20px;font-family:verdana;font-size:14px;"><font color="red"><b>%s</b></font><br/><br/>%s</div>';
    $errstr1 = 'Error: Unable to connect to RelayHealth';
    
    $rhid = trim($_SESSION['rh_api_id']);
    $aaid = trim($GLOBALS['rh_api_aaid']);
    $wsdl = trim($GLOBALS['RHssiwsdl']);
    $location = trim($GLOBALS['RHssilocation']);
    $msglocation=trim($GLOBALS['RHmsglocation']);
    $msgWsdl=trim($GLOBALS['RHmsgwsdl']);
    $partner = trim($GLOBALS['RHPartnerName']);
    $appname = trim($GLOBALS['RHApplicationName']);
    $apppass = trim($GLOBALS['RHApplicationPassword']);

    $rh = new RelayHealthHeader();
    $rh->PartnerName =      $partner;
    $rh->ApplicationName =      $appname;
    $rh->ApplicationPassword =  $apppass;
    $header = new SoapHeader("http://api.relayhealth.com/7.3/SSI", 'RelayHealthHeader', $rh, 1);
    //if no patient is selected then show the home page of relayhealth
    $source=$pid ? trim($_REQUEST['dest']) : "home";
    if($_REQUEST['dest']=='CountMessages')
        $source='CountMessages';
    if($source!='CountMessages'){
	 // make soap call to RH
	try {
	    $client = new SoapClient($wsdl, array(
		    'classmap' => $classmap,
		    'trace' => 1
	    ));
	    /*
	     printf("<!-- %s\n%s\n%s\n%s\n%s\n%s\n%s\n%s -->", 
		    $wsdl, 
		    print_r($client, true),
		    $location,
		    $partner,
		    $appname,
		    $apppass,
		    $pid,
		    $_REQUEST['dest']
	    );
	    */
	} catch (Exception $e) {
	    $errstr2 = "Temporary error.<br/><br/>Please check your internet connection, may be proxy is blocking to call SOAP Services.";
	    die(sprintf($errdiv, $errstr1, $errstr2));
	}
	
    }
    switch( $source )
    {
      case "inbox":
            $call = new ViewInbox();
            $call->partnerUserId = $rhid;
            $params = new SoapVar($call, SOAP_ENC_OBJECT, "ViewInbox", "http://api.relayhealth.com/7.3/SSI");

            try {
                $token = $client->__soapCall("ViewInbox",
                    array('partnerUserId' => $call),
                    array(
                        'location' => $location,
                        'uri' => 'http://api.relayhealth.com/7.3/SSI'
                    ),
                    array($header));
            } catch (Exception $e) {
		die(sprintf($errdiv, $errstr1, "<pre>error $e</pre>"));
            }
	    
	    if ($token) {
	    header("Location: ". $token->Url);
		exit;
	    }
	    
        break;
      case "renewals":
            $call = new ViewRenewals();
            $call->partnerUserId = $rhid;
            $params = new SoapVar($call, SOAP_ENC_OBJECT, "ViewRenewals", "http://api.relayhealth.com/7.3/SSI");

            try {
                $token = $client->__soapCall("ViewRenewals",
                    array('partnerUserId' => $call),
                    array(
                        'location' => $location,
                        'uri' => 'http://api.relayhealth.com/7.3/SSI'
                    ),
                    array($header));
            } catch (Exception $e) {
                die(sprintf($errdiv, $errstr1, "<pre>error $e</pre>"));
            }
	    
	    if ($token) {
	header("Location: ". $token->Url);
		exit;
	    }
	    
        break;
        case "CountMessages":
            $call = new CountMessages();
            $call->UserIds = array($rhid);
             try {
                $msg_client = new SoapClient($msgWsdl, array(
                    'classmap' => $classmap,
                    'trace' => 1
                ));
           
                $msg_header = new SoapHeader("http://api.relayhealth.com/7.4/RelayHealthMessage", 'RelayHealthHeader', $rh, 1);
              
                $token = $msg_client->__soapCall("CountMessages",
                    array('UserIds' => $call),
                    array(
                        'location' => $msglocation,
                        'uri' => 'http://api.relayhealth.com/7.4/RelayHealthMessage'
                    ),
                    array($msg_header));
		if ($token) {
		 $data= objectToArray($token);
		  echo "(".$data['MessageCount']['MessageCount']['Unread'].")";
		}else{
                 echo "";
            }
            } catch (Exception $e) {
                 echo "";
            }
	   
	   
	    
        break;
      case "escripts":
	 if($pid){
	    $pub = sqlStatement("SELECT pubpid FROM patient_data WHERE pid = $pid");
		while ($row = sqlFetchArray($pub)) {
		    $pubpid = $row['pubpid'];
		    }	    
		   
		    $call = new StartPrescription();
		    $call->partnerUserId = $rhid;
		    $call->identifierType = 'MRN';
		    $call->patientId = $pubpid;
		    $call->assigningAuthority = $aaid;
		    $params = new SoapVar($call, SOAP_ENC_OBJECT, "StartPrescription", "http://api.relayhealth.com/7.3/SSI");
	
		    try {
			$token = $client->__soapCall("StartPrescription",
			    array('partnerUserId' => $call),
			    array(
				'location' => $location,
				'uri' => 'http://api.relayhealth.com/7.3/SSI'
			    ),
			    array($header));
		    } catch (Exception $e) {
			die(sprintf($errdiv, $errstr1, "<pre>error $e</pre>"));
		    }
		    
		    if ($token) {
		    header("Location: ". $token->Url);
			exit;
		    }
	    }
	    else {
		die(sprintf($errdiv, $errstr1, "No patient selected"));
	    }
	    
        break;

	case "summary":
	 if($pid){
	    $pub = sqlStatement("SELECT pubpid FROM patient_data WHERE pid = $pid");
		while ($row = sqlFetchArray($pub)) {
		    $pubpid = $row['pubpid'];
		    }	    
		   
		    $call = new PatientSummary();
            	    $call->partnerUserId = $rhid;
		    $call->identifierType = 'MRN';
		    $call->patientId = $pubpid;
		    $call->assigningAuthority = $aaid;
		    $params = new SoapVar($call, SOAP_ENC_OBJECT, "ViewHealthSummary", "http://api.relayhealth.com/7.3/SSI");

            try {
			$token = $client->__soapCall("ViewHealthSummary",
                    array('partnerUserId' => $call),
                    array(
                        'location' => $location,
                        'uri' => 'http://api.relayhealth.com/7.3/SSI'
                    ),
                    array($header));
            } catch (Exception $e) {
			die(sprintf($errdiv, $errstr1, "<pre>error $e</pre>"));
            }
             header("Location: ". $token->Url);
		    }	    
	    else
		die(sprintf($errdiv, $errstr1, "No patient selected"));
		   
        break;
      case "home":
      default:
            $call = new ViewWelcome();
		    $call->partnerUserId = $rhid;
            $params = new SoapVar($call, SOAP_ENC_OBJECT, "ViewWelcome", "http://api.relayhealth.com/7.3/SSI");
	
		    try {
                $token = $client->__soapCall("ViewWelcome",
			    array('partnerUserId' => $call),
			    array(
				'location' => $location,
				'uri' => 'http://api.relayhealth.com/7.3/SSI'
			    ),
			    array($header));
		    } catch (Exception $e) {
		die(sprintf($errdiv, $errstr1, "<pre>error $e</pre>"));
		    }
	    
            if ($token) {
		    header("Location: ". $token->Url);
		exit;
	    }
	    
        break;

    }
}else{
        xl('Relay Health credentials are missing from this user account.', 'e');
        die;
}
function objectToArray($d) {
		if (is_object($d)) {
			// Gets the properties of the given object
			// with get_object_vars function
			$d = get_object_vars($d);
		}
 
		if (is_array($d)) {
			/*
			* Return array converted to object
			* Using __FUNCTION__ (Magic constant)
			* for recursive call
			*/
			return array_map(__FUNCTION__, $d);
		}
		else {
			// Return array
			return $d;
		}
	}
