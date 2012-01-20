<?php  
//  ------------------------------------------------------------------------ //
//                OpenEMR Electronic Medical Records System                  //
//                   Copyright (c) 2005-2010 oemr.org                        //
//                       <http://www.oemr.org/>                              //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA // 
// --------------------------------------------------------------------------//
// Original Author of this file: Craig Bezuidenhout (Tajemo Enterprises)     //
// Purpose of this file: Used for displaying dated reminders                 //
// --------------------------------------------------------------------------//     

        $fake_register_globals=false;
        $sanitize_all_escapes=true;
         
        $checkFolder = '/interface/main/calendar/';
        // set to true to make sure the script is run from the above folder
        $checkForFolder = false;   
             
        $days_to_show = 5;
        $alerts_to_show = 5;
        $updateDelay = 60; // time is seconds 
        
        
// ----- get time stamp for start of today, this is used to check for due and overdue reminders
        $today = strtotime(date('Y/m/d'));
        
 // ----- set $hasAlerts to false, this is used for auto-hiding messages if there are no due or overdue reminders        
        $hasAlerts = false;

// mulitply $updateDelay by 1000 to get miliseconds             
        $updateDelay = $updateDelay * 1000;
// ------------------------------------------------
// @ RemindersArray function
// @ returns array with reminders for current user
// ------------------------------------------------   
   function RemindersArray(){
        global $days_to_show,$today,$hasAlerts,$alerts_to_show;
        
// ----- define a blank reminders array
        $reminders = array();         
        
// ----- sql statement for getting uncompleted reminders (sorts by date, then by priority)  
          $drSQL = sqlStatement(
                            "SELECT 
                                    dr.pid, dr.dr_id, dr.dr_message_text,dr.dr_message_due_date, 
                                    u.fname ffname, u.mname fmname, u.lname flname
                            FROM `dated_reminders` dr 
                            JOIN `users` u ON dr.dr_from_ID = u.id 
                            JOIN `dated_reminders_link` drl ON dr.dr_id = drl.dr_id  
                            WHERE drl.to_id = ? 
                            AND dr.`message_processed` = 0
                            AND dr.`dr_message_due_date` < ADDDATE(NOW(), INTERVAL $days_to_show DAY) 
                            ORDER BY `dr_message_due_date` ASC , `message_priority` ASC LIMIT 0,$alerts_to_show"
                            , array($_SESSION['authId'])
                            );                       
        
// --------- loop through the results
        for($i=0; $drRow=sqlFetchArray($drSQL); $i++){  
// --------- need to run patient query seperately to allow for messages not linked to a patient  
          $pSQL = sqlStatement("SELECT pd.title ptitle, pd.fname pfname, pd.mname pmname, pd.lname plname FROM `patient_data` pd WHERE pd.id = ?",array($drRow['pid']));  
          $pRow = sqlFetchArray($pSQL);
             
// --------- fill the $reminders array 
      		$reminders[$i]['messageID'] = $drRow['dr_id'];  
          $reminders[$i]['PatientID'] = $drRow['pid'];
          
// -------------------------------------  if there was a patient linked, set the name, else set it to blank          
          $reminders[$i]['PatientName'] = (empty($pRow) ? '' : $pRow['ptitle'].' '.$pRow['pfname'].' '.$pRow['pmname'].' '.$pRow['plname']); 
          $reminders[$i]['PatientName'] =  (empty($pRow) ? '' : '('.ucwords(strtolower($reminders[$i]['PatientName'])).')');
// -------------------------------------
          
      		$reminders[$i]['message'] = $drRow['dr_message_text'];             
      		$reminders[$i]['dueDate'] = $drRow['dr_message_due_date'];  
      		$reminders[$i]['fromName'] = $drRow['ffname'].' '.$drRow['fmname'].' '.$drRow['flname'];        
          $reminders[$i]['fromName'] =  ucwords(strtolower($reminders[$i]['fromName']));
           
// --------- if the message is due or overdue set $hasAlerts to true, this will stop autohiding of messages
          if(strtotime($drRow['dr_message_due_date']) <= $today) $hasAlerts = true;  
      	} 
// --------- END OF loop through the results

        return $reminders;
     }   
// ------------------------------------------------
// @ END OF RemindersArray function 
// ------------------------------------------------   
     
      


// ------------------------------------------------
// @ RemindersArray getRemindersHTML(array $reminders)
// @ returns HTML as a string, for printing 
// ------------------------------------------------   
     function getRemindersHTML($reminders = array()){ 
       global $today,$hasAlerts;      
// --- initialize the string as blank
       $pdHTML = '';        
// --- loop through the $reminders
       foreach($reminders as $r){
           
// --- initialize $warning as the date, this is placed in front of the message
            $warning = $r['dueDate'];                 
// --- initialize $class as 'text dr', this is the basic class
            $class='text dr'; 
             
// --------- check if reminder is  overdue   
            if(strtotime($r['dueDate']) < $today){
              $warning = '! OVERDUE';   
              $class = 'bold alert dr';
            }         
// --------- check if reminder is due 
            elseif(strtotime($r['dueDate']) == $today){
              $warning = 'TODAY';                                              
              $class='bold alert dr'; 
            } 
            // end check if reminder is due or overdue
            // apend to html string
            $mID = htmlspecialchars($r['messageID']); 
            $class = htmlspecialchars($class);
            $warning = htmlspecialchars($warning);
            $patID = htmlspecialchars($r['PatientID']); 
            $patientName = htmlspecialchars($r['PatientName']); 
            $message = htmlspecialchars($r['message']);      
            $fromName = htmlspecialchars($r['fromName']);  
            $pdHTML .= '<p id="p_'.$mID.'">
                          <a class="dnRemover css_button_small" onclick="updateme('."'".$mID."'".')" id="'.$mID.'" href="#">
                            <span>'.xl('Set As Completed').'</span>
                          </a> 
                          <span title="'.xl('Click to Open Patient').'" class="'.$class.'">'. 
                            $warning.' 
                            <span onclick="goToPatient('.$patID.')" class="patLink" id="'.$patID.'">'.
                              $patientName.'
                            </span> '.
                            $message.' - ['.$fromName.']
                          </span> -----> 
                          <a onclick="openAddScreen('.$mID.')" class="dnForwarder" id="'.$mID.'" href="#">[ '.xl('Forward').' ]</a>
                        </p>';
          }
      return ($pdHTML == '' ? '<p class="alert"><br />'.xl('No Messages').'</p>' : $pdHTML); 
    } 
    
//-----------------------------------------------------------------------------
// HANDEL AJAX TO MARK MESSAGES AS READ
// Javascript will send a post
// ----------------------------------------------------------------------------         
if(isset($_POST['drR'])){
  if(is_numeric($_POST['drR'])){   
// --- todo : need to add a check to ensure the post is being sent from the correct location ??? 
    include_once("../../globals.php");

    if($_POST['drR'] > 0){  
// --- check if this user can remove this message
// --- need a better way of checking the current user, I don't like using $_SESSION for checks
      $rdrSQL = sqlStatement("SELECT count(dr.dr_id) c FROM `dated_reminders` dr JOIN `dated_reminders_link` drl ON dr.dr_id = drl.dr_id WHERE drl.to_id = ? AND dr.`dr_id` = ? LIMIT 0,1", array($_SESSION['authId'],$_POST['drR']));  
      $rdrRow=sqlFetchArray($rdrSQL);
    
// --- if this user can delete this message (ie if it was sent to this user)
      if($rdrRow['c'] == 1){  
// ----- update the data, set the message to proccesses
        sqlStatement("UPDATE `dated_reminders` SET  `message_processed` = 1, `processed_date` = NOW(), `dr_processed_by` = ? WHERE `dr_id` = ? ", array(intval($_SESSION['authId']),intval($_POST['drR'])));  
      } 
    }
  } 
// ----- get updated data
  $reminders = RemindersArray();
   
// ----- echo for ajax to use        
  echo getRemindersHTML($reminders);   
  
  exit;
}
// simple security check to make sure the script is being run from within the correct folder,
// this allows the calendar to take care of security    

  // check if $GLOBALS['webserver_root'] is set, if not set it to an arbitrary value to generate an error
  if(!isset($GLOBALS['webserver_root'])) $GLOBALS['webserver_root'] == 'error';                
  // the folder that the calendar should be in
  $calendar_check_folder = preg_replace ('/\//','\\',$GLOBALS['webserver_root'].$checkFolder);
  // get folder of "parent file"
  $backtrace = debug_backtrace(defined("DEBUG_BACKTRACE_IGNORE_ARGS")? DEBUG_BACKTRACE_IGNORE_ARGS: FALSE);
  $top_frame = array_pop($backtrace);
  $calendar_folder = preg_replace ('/index.php/','',$top_frame['file']);    
  $calendar_folder = preg_replace ('/\//','\\',$calendar_folder);
  // end get folder of "parent file"
  
  
  // allow for the override 
  if(!$checkForFolder) $calendar_check_folder = $calendar_folder;                              
             
  if($calendar_check_folder != $calendar_folder){ 
    echo 'This script should not be run on it\'s own'; 
    exit;
  }
// END simple security check to make sure the script is being run from within the calendar 
 
      // ---------------------------------------------------------------------------------------
      //
      //   IT IS ASSUMED THAT THE CALENDAR WOULD HAVE TAKEN CARE OF RELATIVE SECURITY CHECKS  
      //
      // -------------------------------- -------------------------------------------------------
      $reminders = RemindersArray();
      ?> 
      <style type="text/css"> 
         div.dr{     
           margin:0;
           font-size:0.6em;
         }  
         #dr_container a{
           font-size:0.6em;
         }    
         #dr_container{
           padding:5px 5px 8px 5px;
         }  
         #dr_container p{
           margin:6px 0 0 0;
         }      
         .patLink{ 
           font-weight: bolder;
           cursor:pointer; 
           text-decoration: none;  
         }       
         .patLink:hover{ 
           font-weight: bolder;
           cursor:pointer; 
           text-decoration: underline;
         }
      </style>
      <script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery-1.4.3.min.js"></script>   
      <script type="text/javascript">
         $(document).ready(function (){ 
            <?php
              if(!$hasAlerts){
                echo '$("#hideDR").html("<span>'.xl('Show Reminders').'</span>"); $(".drHide").hide();';
              }
            ?> 
            $("#hideDR").click(function(){
              if($(this).html() == "<span><?php echo xl('Hide Reminders') ?></span>"){  
                $(this).html("<span>S<?php echo xl('Show Reminders') ?></span>"); 
                $(".drHide").slideUp("slow");
              }
              else{  
                $(this).html("<span><?php echo xl('Hide Reminders') ?></span>");  
                $(".drHide").slideDown("slow");
              }
            }) 
           // run updater after 30 seconds
           var updater = setTimeout("updateme(0)", 1);
         }) 
           
           function openAddScreen(id){
             if(id == 0){
               dlgopen('dated_reminders_add.php', '_drAdd', 700, 500);
             }else{
               dlgopen('dated_reminders_add.php?mID='+id, '_drAdd', 700, 500);
             }
           }
           
           function updateme(id){  
           
            refreshInterval = <?php echo $updateDelay ?>;
             if(id > 0){
              $("#drTD").html('<p style="text-size:3em; margin-left:200px; color:black; font-weight:bold;">Processing...</p>'); 
             }    
             $.post("dated_reminders.php", { drR: id }, 
               function(data) {
                if(data == 'error'){     
                  alert("<?php echo xl('Error Removing Message') ?>");  
                }else{ 
                  // todo : add a 1 second delay to make sure the user knows something happened.
                  if(id > 0){
                    $("#drTD").html('<p style="text-size:3em; margin-left:200px; color:black; font-weight:bold;"><?php echo xl("Refreshing Messages") ?> ...</p>');
                  }
                  $("#drTD").html(data); 
                }   
              // run updater every refreshInterval seconds 
              var repeater = setTimeout("updateme(0)", refreshInterval); 
             });
           }   
           
           function goToPatient(pid){
            goPid(pid);
           }   
            
            function openLogScreen(){ 
               dlgopen('dated_reminders_log.php', '_drLog', 700, 500);
            }
      </script>
      
        <?php
          $isAdmin =acl_check('admin', 'calendar');  
          
            
          // Temporary for allowing all users to see this
          $isAdmin = true;             
          
           
          // initialize html string        
          $pdHTML = '<div id="dr_container"><table><tr><td valign="top">                         
                        <p><a id="hideDR" class="css_button_small" href="#"><span>'.xl('Hide Reminders').'</span></a><br /></p>
                        <div class="drHide">'.
                        ($isAdmin ? '<p><a onclick="openLogScreen()" class="css_button_small" href="#"><span>'.xl('View Log').'</span></a><br /></p>' : '')
                        .'<p><a onclick="openAddScreen(0)" class="css_button_small" href="#"><span>'.xl('Send A Dated Reminder').'</span></a></p></div> 
                        </td><td class="drHide" id="drTD">'; 
                        
          $pdHTML .= getRemindersHTML($reminders);
          $pdHTML .= '</td></tr></table></div>';
          // print output
          echo $pdHTML; 
        ?> 
