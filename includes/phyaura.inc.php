<?php
/*******************************************************************/
// Copyright (C) 2008 Phyaura, LLC <info@phyaura.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
/*******************************************************************/
/* ------------ Phyaura - RelayHealth global variables ----------- */
/*******************************************************************/

/* ------------ set booleans to turn on/off services ------------- */
// SOAP API - set TRUE if using soap calls on Left Nav menu with RelayHealth
//$GLOBALS['rh_api'] = TRUE;
/* !!! rh_api requires $_SESSION['rh_api_id'] set in library/auth.inc !!! */
/* !!! rh_api requires $_SESSION['rh_api_id'] set in library/auth.inc !!! */

// eScripts - set TRUE if using e-prescribing with RelayHealth
//$GLOBALS['rh_escripts'] = TRUE;

// Patient ADT (New and Update) - set TRUE if sending
// outbound ADT messages to RelayHealth for patient creation and updates
//$GLOBALS['rh_patient'] = TRUE;

// Patient/medication CCD (New and Update) - set TRUE if sending
// outbound CCD messages to RelayHealth for patient medication/allergy adding and updates
//$GLOBALS['rh_summary'] = FALSE;

// eNotes - set TRUE if receiving e-notes (HL7/MDM) from RelayHealth
//$GLOBALS['rh_enotes'] = TRUE;

// eNotes - Activate which enotes you want to receive
// Add the code for the enote description below seperated by commas
// used with rh_enotes, table e_types, and list_options message_type
$GLOBALS['rh_allow_enotes'] = "NT,MD";  // string - NT,MD
/*
('WV','webVisit')
('NT','Note to Office')
('MD','Note to Doctor')
('LQ','Lab/Test Result Request')
('RN','Rx Renewal Request')
('RF','Referral Request')
('BL','Billing Question')
('RQ','New Patient')
('AP','Appointment Request')
('RX','New Medication')
('RE','Referral from Physician')
('RD','Renewal Denied')
('ER','eRenewal Request')
*/

/* ------------- set environment for params and credentials ------------- */
//$GLOBALS['rh_api_env'] = "T";  // env codes T->Test P->Prod

$rh_info = false;
if (isset($_SESSION['authUserID']) && !empty($_SESSION['authUserID'])) {
  //$rh_info = sqlQuery("SELECT * FROM users_rh_info WHERE user_id='".$_SESSION['authUserID']."'");
  $rh_info=array("rh_api_aaid" => $GLOBALS['rh_relay_guid'],
		 "rh_practice_id" =>$GLOBALS['rh_practice_id_global'],
                 "rh_app_name"=>$GLOBALS['rh_app_name_ssi'],
		 "rh_app_passwd"=>$GLOBALS['rh_app_password_ssi'],
		 "rh_partner_name" =>$GLOBALS['rh_partner_name_ssi'],
  		);
  //print_r($rh_info);
  
  
}

// valid all fields are available
if ($rh_info) {
  if (!isset($_SESSION['rh_api_id']) || empty($_SESSION['rh_api_id']))
      $rh_info = false;
  else
    $rh_info['rh_api_id'] = $_SESSION['rh_api_id'];
  
  if (empty($rh_info['rh_practice_id']))
    $rh_info = false;
}

switch( trim( $GLOBALS['rh_api_env'] ) )
{
  case "P":
    
    if ($rh_info) {
      /* --------- set variables for required params and credentials ---------- */
      // set the health system var - provided by Relay Health
      $GLOBALS['rh_var'] = "";
      // the PartnerUserId should be set for each user login that is to utilize the rh api
      // the PartnerUserId is stored in OpenEMR->users->ssi_relayhealth
      // set the relay health Partner ID - provided by Relay Health
      $GLOBALS['rh_partner_id'] = $rh_info['rh_api_id'];
      // set the relay health Practice ID - provided by Relay Health
      $GLOBALS['rh_practice_id'] = $rh_info['rh_practice_id'];
  
      // set the assigning authority id for the practice
      // this is the aaid for health link
      $GLOBALS['rh_api_aaid'] 		= $rh_info['rh_api_aaid'];
      $GLOBALS['RHApplicationName']     = $rh_info['rh_app_name'];
      $GLOBALS['RHApplicationPassword'] = $rh_info['rh_app_passwd'];
      $GLOBALS['RHPartnerName']         = $rh_info['rh_partner_name'];
      unset($rh_info);
    }
    
    // production
    // globals for relay health SingleSignIn
    $GLOBALS['RHssilocation']            = $GLOBALS['RHssilocation_prod'] ;
    $GLOBALS['RHssiwsdl']                = $GLOBALS['RHssiwsdl_prod'];

    // globals for relay health message service - temporary location
    // production
    $GLOBALS['RHmsglocation'] = $GLOBALS['RHmsglocation_prod'];
    $GLOBALS['RHmsgwsdl'] = $GLOBALS['RHmsgwsdl_prod'];
    break;

  case "D":
  case "T":
  default:
    if ($rh_info) {
      /* --------- set variables for required params and credentials ---------- */
      // set the health system var - provided by Relay Health
      $GLOBALS['rh_var'] = "";
      // the PartnerUserId should be set for each user login that is to utilize the rh api
      // the PartnerUserId is stored in OpenEMR->users->ssi_relayhealth
      // set the relay health Partner ID - provided by Relay Health
      $GLOBALS['rh_partner_id'] = $rh_info['rh_api_id'];
      // set the relay health Practice ID - provided by Relay Health
      $GLOBALS['rh_practice_id'] = $rh_info['rh_practice_id'];
  
      // set the assigning authority id for the practice
      // this is the aaid for health link
      $GLOBALS['rh_api_aaid'] 		= $rh_info['rh_api_aaid'];
      $GLOBALS['RHApplicationName']     = $rh_info['rh_app_name'];
      $GLOBALS['RHApplicationPassword'] = $rh_info['rh_app_passwd'];
      $GLOBALS['RHPartnerName']         = $rh_info['rh_partner_name'];
      
      unset($rh_info);
    }
    /*// globals for relay health SingleSignIn
    // dev-testing
    $GLOBALS['RHssilocation']            = 'https://api.integration.relayhealth.com/SSI/SingleSignIn.svc';
    $GLOBALS['RHssiwsdl']                = 'https://api.integration.relayhealth.com/SSI/SingleSignIn.svc?wsdl';

    // globals for relay health message service - temporary location
    $GLOBALS['RHmsglocation'] = "https://api.integration.relayhealth.com/Message/RelayHealthMessageService.svc";
    $GLOBALS['RHmsgwsdl'] = "https://api.relayhealth.com/Message/RelayHealthMessageService.svc?wsdl";
    */
    break;
}



