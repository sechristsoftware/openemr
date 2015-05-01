 <?php
/** 
* library/patient_tracker.inc.php Functions used in the Patient Flow Board. 
* 
* Functions for use in the Patient Flow Board
* 
* 
* Copyright (C) 2015 Terry Hill <terry@lillysystems.com> 
* 
* LICENSE: This program is free software; you can redistribute it and/or 
* modify it under the terms of the GNU General Public License 
* as published by the Free Software Foundation; either version 3 
* of the License, or (at your option) any later version. 
* This program is distributed in the hope that it will be useful, 
* but WITHOUT ANY WARRANTY; without even the implied warranty of 
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
* GNU General Public License for more details. 
* You should have received a copy of the GNU General Public License 
* along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;. 
* 
* @package OpenEMR 
* @author Terry Hill <terry@lillysystems.com>
* @link http://www.open-emr.org 
*/

function manage_tracker_status($apptdate,$appttime,$eid,$pid,$user,$status='',$room='',$enc_id='') {

  $datetime = date("Y-m-d H:i:s");

  #Check to see if there is an entry in the patient_tracker table.
  $tracker = sqlQuery("SELECT * from `patient_tracker` WHERE `apptdate` = ? AND `appttime` = ? " .
                      "AND `eid` = ? AND `pid` = ?", array($apptdate,$appttime,$eid,$pid));
  if (empty($tracker)) {
    #Add a new tracker.
    $tracker_id = sqlInsert("INSERT INTO `patient_tracker` " .
                            "(`date`, `apptdate`, `appttime`, `eid`, `pid`, `user`, `laststatus`, `lastroom`, `encounter`, `lastseq`) " .
                            "VALUES (?,?,?,?,?,?,?,?,?,'1')",
                            array($datetime,$apptdate,$appttime,$eid,$pid,$user,$status,$room,$enc_id));

    #If there is a status or a room, then add a tracker item.
    if (!empty($status) || !empty($room)) {
    sqlInsert("INSERT INTO `patient_tracker_element` " .
              "(`pt_tracker_id`, `start_datetime`, `user`, `status`, `room`, `seq`) " .
              "VALUES (?,?,?,?,?,'1')",
              array($tracker_id,$datetime,$user,$status,$room));
    }
  }
  else {
    #Tracker already exists.
    if (($status != $tracker['laststatus']) || ($room != $tracker['lastroom'])) {
      #Status or room has changed, so need to update tracker.

      #Update laststatus and lastroom in tracker.
      sqlStatement("UPDATE `patient_tracker` SET `laststatus` = ?, `lastroom` = ?, `lastseq` = ? WHERE `id` = ?",
                   array($status,$room,($tracker['lastseq']+1),$tracker['id']));

      #Add a tracker item.
      sqlInsert("INSERT INTO `patient_tracker_element` " .
                "(`pt_tracker_id`, `start_datetime`, `user`, `status`, `room`, `seq`) " .
                "VALUES (?,?,?,?,?,?)",
                array($tracker['id'],$datetime,$user,$status,$room,($tracker['lastseq']+1)));
    }
    if (!empty($enc_id)) {
      #enc_id is not blank, so update this in tracker.
      sqlStatement("UPDATE `patient_tracker` SET `encounter` = ? WHERE `id` = ?", array($enc_id,$tracker['id']));
    }  
  }

  #Ensure the entry in calendar appt entry has been updated.
  # TODO - also collect the `pc_room` field when it is there
  # $pc_appt =  sqlQuery("SELECT `pc_apptstatus`, `pc_room` FROM `openemr_postcalendar_events` WHERE `pc_eid` = ?", array($eid))
  $pc_appt =  sqlQuery("SELECT `pc_apptstatus` FROM `openemr_postcalendar_events` WHERE `pc_eid` = ?", array($eid));
  if ($status != $pc_appt['pc_apptstatus']) {
    sqlStatement("UPDATE `openemr_postcalendar_events` SET `pc_apptstatus` = ? WHERE `pc_eid` = ?", array($status,$eid));
  }
  # TODO - support for room number after this is added
  #if ($room != $pc_appt['pc_room']) {
  #  sqlStatement("UPDATE `openemr_postcalendar_events` SET `pc_room` = ? WHERE `pc_eid` = ?", array($room,$eid));
  #}
}

	
function  add_or_update_tracker_status($apptdate,$appttime,$pid,$user,$status,$pceid='',$encounter='',$room='',$record_id='')
     {	
      $tmptrk = sqlQuery("SELECT id FROM patient_tracker WHERE pid = ? AND apptdate = ? AND appttime = ? ", array($pid,$apptdate,$appttime) );
      $checkid = $tmptrk['id'];
      $track_date = date("Y-m-d H:i:s");	
      $endtime = "00:00:00";
	  $arrivetime = "00:00:00";
      $today   = date("Y-m-d");
        if(strlen($checkid) == 0) {  
		
          if (strpos($GLOBALS['arrival_code'],$status) !=0) {
            $arrivetime = substr($track_date,11);	 
          }	 
		
             sqlInsert("INSERT INTO patient_tracker SET " .
			   "date = ?, " .
			   "arrivetime = ?, " .
			   "apptdate = ?, " .
			   "appttime = ?, " .
               "eid = ?, " .
               "pid = ?, " .
			   "user = ?, " .
			   "laststatus = ?, " .
			   "lastseq = ?, " .
			   "encounter = ? ",
    			array($track_date,$arrivetime,$apptdate,$appttime,$pceid,$pid,$user,$status,'1',$encounter)	
              );
	
             $tmptrk = sqlQuery("SELECT id FROM patient_tracker WHERE pid = ? AND apptdate = ? AND appttime = ?", array($pid,$apptdate,$appttime) );
             $maintkid = $tmptrk['id'];
			 
		     sqlInsert("INSERT INTO patient_tracker_element SET " .
			   "pt_tracker_id = ?, " .
			   "start_datetime = ?, " .
			   "status = ?, " .
			   "seq = ?, " .
			   "user = ? ",
    			array($maintkid,$track_date,$status,'1',$user)
              );	
        }
        else
        {
         $tmptrk = sqlQuery("SELECT lastseq , arrivetime FROM patient_tracker WHERE id = ? ", array($checkid) );
         $nextseq = 1 + $tmptrk['lastseq'];
		
         if (strpos($GLOBALS['arrival_code'],$status) !=0 && $tmptrk['arrivetime'] == '00:00:00') {
            $arrivetime = substr($track_date,11);	 
          }
          else
          {
           $arrivetime = $tmptrk['arrivetime'];
		  }	  
		
         if (strpos($GLOBALS['discharge_code'],$status) !=0) {
            $endtime = substr($track_date,11);	 
         }	 
            sqlStatement("UPDATE patient_tracker SET " .
			   "arrivetime = ?, " . 
               "lastroom =? ," .
               "lastseq =? ," .
               "endtime =? ," .		   
               "laststatus =? " .
               "WHERE id =? AND apptdate =?", array($arrivetime,$room,$nextseq,$endtime,$status,$checkid,$apptdate));

            sqlInsert("INSERT INTO patient_tracker_element SET " .
               "pt_tracker_id = ?, " .
               "start_datetime = ?, " .
               "status = ?, " .
               "room =? ," .
               "seq =? ," .
               "user = ? ",
    			array($checkid,$track_date,$status,$room,$nextseq,$user)
             );	
	
         sqlStatement("UPDATE openemr_postcalendar_events SET " .
            "pc_apptstatus =? " .
            "where pc_pid =? AND pc_eventdate =? AND pc_startTime =? ", array($status,$pid,$today,$appttime)); 
        }	
	 }

