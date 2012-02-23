<?php
/*******************************************************************/
// Copyright (C) 2010 Phyaura, LLC <info@phyaura.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
/*******************************************************************/
require_once("{$GLOBALS['srcdir']}/sql.inc");

$out_msg_types = array(
  'ADT',
  'CCD');


// this function can be updated to send to facilities using other methods and message types
function queueMessage($list_id='',$e_block='',$type, $pid = 0, $source_type = '', $source_id = '', $method = 'A08', $status = 1)
{
   
    $inbox = '';
    if( $source_type != '' && $source_id != '' )
	    $inbox = strtolower($source_type).".".$source_id;
    $recipient = '0';
    $sender = $GLOBALS['phydee'];  // coming from globals.php      ,it is taken is default , below is code for searching the practice id from user_rh_info table 
    $source = "101";
    $dest = "100";
    //$sender = $GLOBALS['rh_practice_id'];
    $facility = $GLOBALS['rh_practice_id'];
    $env = $GLOBALS['rh_api_env'];
	
    if( $_SESSION['rh_api_id'] != "" ) 
	    $provider = $_SESSION['rh_api_id'];
    else
    	    $provider = 0;
	
    if($pid) 
    {		  // check if primary provider is set for patient
		    $ssi_query = "SELECT ssi_relayhealth FROM users WHERE id=(select primaryProviderID from patient_data where pid=$pid )";
		    $row_ssi_query = sqlQuery($ssi_query);
		    $ssi_relayhealth = $row_ssi_query['ssi_relayhealth'];
		    if($ssi_relayhealth)
		    {
			     $provider=$ssi_relayhealth;          // take the e-suit id of primary provider id 
		    }
		    else // other wise get primary provider from global setting secreen
		    {
		    $ssi_query = "SELECT ssi_relayhealth FROM users WHERE id=(SELECT gl_value FROM globals WHERE gl_name LIKE 'default_primary_provider')";
		    $row_ssi_query = sqlQuery($ssi_query);
		    $ssi_relayhealth = $row_ssi_query['ssi_relayhealth'];
			if($ssi_relayhealth)
			{
			     $provider=$ssi_relayhealth;          // take the e-suit id of primary provider id 
			}
		    
			
		    }
		    
		    
	    
    }
    if($GLOBALS['rh_practice_id_global']) // from Adminstration->Global->setting
    {
	    $sender=$GLOBALS['rh_practice_id_global'];
    }
    $pub = "SELECT pubpid FROM patient_data WHERE pid = $pid";
    $row = sqlQuery($pub);
    $pubpid = $row['pubpid']; // patient external id
                    
    if( $type == 'CCD' ) $method = 'HL7';
    if($GLOBALS['activate_relay_link']){
    $query = "INSERT INTO e_outbox (e_type,e_block,e_block_id,e_date,e_sender,e_recipient,e_source,e_destination,e_facility,e_env_code,e_method,e_status,e_pid,e_mrn,e_provider_id,e_attending_id,e_referring_id,e_inbox) VALUES ('$type','$e_block','$list_id', NOW(),'$sender','$recipient','$source','$dest','$facility','$env','$method','$status',$pid,'$pubpid','$provider','$sender','$logged_in_user_id','$inbox')";
        return sqlInsert($query); 
}else
{
	return;
    }
}

?>
