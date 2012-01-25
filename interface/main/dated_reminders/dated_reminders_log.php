<?php            
//  ------------------------------------------------------------------------ //
//                OpenEMR Electronic Medical Records System                  //
//                 Copyright (c) 2005-2010 tajemo.co.za                      //
//                     <http://www.tajemo.co.za/>                            //
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
// Purpose of this file: Used for displaying log of dated reminders          //
// --------------------------------------------------------------------------//

  $fake_register_globals=false;
  $sanitize_all_escapes=true;

  require_once("../../globals.php");
  require_once("$srcdir/htmlspecialchars.inc.php");
  require_once("$srcdir/acl.inc");
  
  
  $isAdmin =acl_check('admin', 'users');  
  
  // Temporary for allowing all users to see this
  $isAdmin = true;
?>
<?php
  /*
    -------------------  HANDLE POST ---------------------
  */
  if($_GET){ 
    echo '<table border="1" width="100%" cellpadding="5px" id="logTable">
            <thead>
              <tr>
                <th>'.xlt('ID').'</th>
                <th>'.xlt('Sent Date').'</th>
                <th>'.xlt('From').'</th>
                <th>'.xlt('To').'</th>
                <th>'.xlt('Patient').'</th>
                <th>'.xlt('Message').'</th>
                <th>'.xlt('Due Date').'</th>
                <th>'.xlt('Processed Date').'</th>
                <th>'.xlt('Processed By').'</th>
              </tr>
            </thead>
            <tbody>';
    $remindersArray = RemindersArray();
    foreach($remindersArray as $RA){ 
      echo '<tr class="heading">
              <td>',text($RA['messageID']),'</td>
              <td>',text($RA['sDate']),'</td>
              <td>',text($RA['fromName']),'</td>
              <td>',text($RA['ToName']),'</td>
              <td>',text($RA['PatientName']),'</td>     
              <td>',text($RA['message']),'</td>    
              <td>',text($RA['dDate']),'</td>    
              <td>',text($RA['pDate']),'</td>      
              <td>',text($RA['processedByName']),'</td>
            </tr>';
    }
    echo '</tbody></table>'; 
    
    die;
  }
?> 
<html>
  <head>  
    <script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery.js"></script>  
    <script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery-calendar.js"></script>   
    <script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery.grouprows.js"></script>     
    <script src="<?php echo $GLOBALS['webroot'] ?>/library/js/grouprows.js"></script> 
    <script language="JavaScript">   
      $(document).ready(function (){  
        $('#sentTo_all').click(function(){ 
          $('.sentTo').attr('checked',"checked");
        })    
        $('#sentBy_all').click(function(){ 
          $('.sentBy').attr('checked',"checked");
        })
        $("#submitForm").click(function(){ 
          $.get("dated_reminders_log.php?"+$("#logForm").serialize(), 
               function(data) {
                  $("#resultsDiv").html(data);  
                	return false;
               }
             )   
          return false;
        })
      }) 
    </script> 
  </head>
  <body> 
<!-- Required for the popup date selectors -->
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>


<?php 
  if($isAdmin){
    $allUsers = array();
    $uSQL = sqlStatement('SELECT id, fname,	mname, lname  FROM  `users` WHERE  `active` = 1 AND id != ?',array(intval($_SESSION['authId'])));
    for($i=0; $uRow=sqlFetchArray($uSQL); $i++){ $allUsers[] = $uRow; } 
?>     
    <form method="get" id="logForm">         
      <h1><?php echo xlt('Dated Message Log') ?></h1>  
      <h2><?php echo xlt('filters') ?> :</h2>
      <blockquote><?php echo xlt('Date The Message Was Sent') ?><br />
<!----------------------------------------------------------------------------------------------------------------------------------------------------->  
      <?php echo xlt('Start Date') ?> : <input id="sd" type="text" name="sd" value="" onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title='<?php echo xla('yyyy-mm-dd'); ?>' />   &nbsp;&nbsp;&nbsp;  
<!----------------------------------------------------------------------------------------------------------------------------------------------------->   
      <?php echo xlt('End Date') ?> : <input id="ed" type="text" name="ed" value="" onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title='<?php echo xla('yyyy-mm-dd'); ?>' />   <br /><br />
<!----------------------------------------------------------------------------------------------------------------------------------------------------->   
      </blockquote>
      <p style="line-height:1.8em;">       
        <?php echo xlt('Sent By') ?> :                                     
        <input type="checkbox" id="sentBy_all"><label for="sentBy_all"><?php echo xlt('Select All') ?></label><br />
        <input class="sentBy" type="checkbox" name="sentBy_me" value="<?php echo attr(intval($_SESSION['authId'])) ?>" id="sentBy_me"><label for="sentBy_me"><?php echo xlt('Me') ?></label>&nbsp;&nbsp;&nbsp;&nbsp;   
        <?php //  
            $i = 2;   
            foreach($allUsers as $user){
              echo '<input class="sentBy" type="checkbox" name="sentBy_',$i,'" id="sentBy_',$i,'" value="',attr($user['id']),'"><label for="sentBy_',$i,'">',text($user['fname'].' '.$user['mname'].' '.$user['lname']),'</label>&nbsp;&nbsp;&nbsp;&nbsp; ';
              // line break for every 4 users
              if($i % 4 == 0) echo "<br />";  
              $i++; 
            }
        ?>    
      </p>         
<!----------------------------------------------------------------------------------------------------------------------------------------------------->     
      <p style="line-height:1.8em;">  
      <?php echo xlt('Sent To') ?> :     
        <input type="checkbox" id="sentTo_all"><label for="sentTo_all"><?php echo xlt('Select All') ?></label><br />
        <input class="sentTo" type="checkbox" name="sentTo_me" value="<?php echo attr(intval($_SESSION['authId'])) ?>" id="sentTo_me"><label for="sentTo_me"><?php echo xlt('Me') ?></label>&nbsp;&nbsp;&nbsp;&nbsp;   
        <?php //  
            $i = 2;   
            foreach($allUsers as $user){
              echo '<input class="sentTo" type="checkbox" name="sentTo_',$i,'" id="sentTo_',$i,'" value="',attr($user['id']),'"><label for="sentTo_',$i,'">',text($user['fname'].' '.$user['mname'].' '.$user['lname']),'</label>&nbsp;&nbsp;&nbsp;&nbsp; ';
              // line break for every 4 users
              if($i % 4 == 0) echo "<br />";  
              $i++; 
            }
        ?>  
      </p>    
<!-----------------------------------------------------------------------------------------------------------------------------------------------------> 
      <input type="checkbox" name="processed" id="processed"><label for="processed"><?php echo xlt('Processed') ?></label>      
<!-----------------------------------------------------------------------------------------------------------------------------------------------------> 
      <input type="checkbox" name="pending" id="pending"><label for="pending"><?php echo xlt('Pending') ?></label>          
<!----------------------------------------------------------------------------------------------------------------------------------------------------->  
      <br /><br />  
      <button value="Refresh" id="submitForm"><?php echo xlt('Refresh') ?></button>
    </form>
    
    <div id="resultsDiv"></div> 
<?php      
  }else{
    echo xlt('Permisions Error').'.';
  }
?>   
  </body> 
<!-- stuff for the popup calendar -->
<style type="text/css">@import url(<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar.css);</style>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dynarch_calendar_setup.js"></script>
<script language="Javascript"> 
  Calendar.setup({inputField:"sd", ifFormat:"%Y-%m-%d", button:"img_begin_date", showsTime:'false'});  
  Calendar.setup({inputField:"ed", ifFormat:"%Y-%m-%d", button:"img_begin_date", showsTime:'false'}); 
</script>
</html>

<?php  
  
  function RemindersArray(){ 
        
        // set blank array for data to be parsed to sql 
        $input = array(); 
        // set blank string for the query
        $where = '';        
        $sentBy = array();       
        $sentTo = array();     
        
// ----- HANDLE SENT BY AND SEND TO FILTER
// ----- CREATES ARRAYS OF EACH TO BE HANDLED LATER  
          foreach($_GET as $key=>$val){
          // check for matches, make sure they are integers
            if(preg_match('/^sentBy/',$key) and is_numeric($val)){
              $sentBy[] = intval($val);
              unset($_GET[$key]);
            } 
            if(preg_match('/^sentTo/',$key) and is_numeric($val)){
              $sentTo[] = intval($val);
              unset($_GET[$key]);
            }  
          }  
//------------------------------------------
// ----- HANDLE SENT BY FILTER 
          if(!empty($sentBy)){
            $sbCount = 0;  
            foreach($sentBy as $sb){
              $where .= ($sbCount == 0 ? '(' : ' OR ').'dr.dr_from_ID = ? ';
              $sbCount++;
              $input[] = $sb;
            }
            $where .= ')';
          }   
//------------------------------------------   
// ----- HANDLE SENT TO FILTER 
          if(!empty($sentTo)){
            $where = ($where == '' ? '' : $where.' AND ');
            $stCount = 0;  
            foreach($sentTo as $st){
              $where .= ($stCount == 0 ? '(' : ' OR ').'drl.to_id = ? ';
              $stCount++;
              $input[] = $st;
            }
            $where .= ')';
          }        
//------------------------------------------   
// ----- HANDLE PROCCESSED/PENDING FILTER ONLY RUN THIS IF BOTH ARE NOT SET        
          if(isset($_GET['processed']) and !isset($_GET['pending'])){ 
            $where = ($where == '' ? 'dr.message_processed = 1' : $where.' AND dr.message_processed = 1'); 
          } 
          elseif(!isset($_GET['processed']) and isset($_GET['pending'])){ 
            $where = ($where == '' ? 'dr.message_processed = 0' : $where.' AND dr.message_processed = 0'); 
          }   
//------------------------------------------   
// ----- HANDLE DATE RANGE FILTERS
          if(isset($_GET['sd']) and $_GET['sd'] != ''){  
            $where = ($where == '' ? 'dr.dr_message_sent_date >= ?' : $where.' AND dr.dr_message_sent_date >= ?'); 
            $input[] = $_GET['sd'].' 00:00:00';
          }  
          if(isset($_GET['ed']) and $_GET['ed'] != ''){  
            $where = ($where == '' ? 'dr.dr_message_sent_date <= ?' : $where.' AND dr.dr_message_sent_date <= ?'); 
            $input[] = $_GET['ed'].' 24:00:00';
          }      
//------------------------------------------  
          
           
//-------- add the "WHERE" the string if string is not blank, avoid sql errors for blannk WHERE statements 
          $where = ($where == '' ? '' : 'WHERE '.$where);  
            
// ----- define a blank reminders array
        $reminders = array();         
        
// ----- sql statement for getting uncompleted reminders (sorts by date, then by priority)  
          $drSQL = sqlStatement(       
                            "SELECT 
                                    dr.pid, dr.dr_id, dr.dr_message_text, dr.dr_message_due_date dDate, dr.dr_message_sent_date sDate,dr.processed_date processedDate, dr.dr_processed_by,
                                    u.fname ffname, u.mname fmname, u.lname flname,
                                    tu.fname tfname, tu.mname tmname, tu.lname tlname 
                            FROM `dated_reminders` dr       
                            JOIN `dated_reminders_link` drl ON dr.dr_id = drl.dr_id        
                            JOIN `users` u ON dr.dr_from_ID = u.id  
                            JOIN `users` tu ON drl.to_id = tu.id        
                            $where"   
                            ,$input);                       
        
// --------- loop through the results
        for($i=0; $drRow=sqlFetchArray($drSQL); $i++){  
// --------- need to run patient query seperately to allow for messages not linked to a patient  
          $pSQL = sqlStatement("SELECT pd.title ptitle, pd.fname pfname, pd.mname pmname, pd.lname plname FROM `patient_data` pd WHERE pd.id = ?",array($drRow['pid']));  
          $pRow = sqlFetchArray($pSQL);   
           
          $prSQL = sqlStatement("SELECT u.fname pfname, u.mname pmname, u.lname plname FROM `users` u WHERE u.id = ?",array($drRow['dr_processed_by']));  
          $prRow = sqlFetchArray($prSQL );
          
// --------- fill the $reminders array 
      		$reminders[$i]['messageID'] = $drRow['dr_id'];
          $reminders[$i]['PatientID'] = $drRow['pid'];
          
          $reminders[$i]['pDate'] = ($drRow['processedDate'] == '0000-00-00 00:00:00' ? 'N/A' : $drRow['processedDate']);              
      		$reminders[$i]['sDate'] = $drRow['sDate'];                    
      		$reminders[$i]['dDate'] = $drRow['dDate'];               
          
// -------------------------------------  if there was a patient linked, set the name, else set it to blank          
          $reminders[$i]['PatientName'] = (empty($pRow) ? 'N/A' : $pRow['ptitle'].' '.$pRow['pfname'].' '.$pRow['pmname'].' '.$pRow['plname']);  
// -------------------------------------
          
      		$reminders[$i]['message'] = $drRow['dr_message_text'];      
      		$reminders[$i]['fromName'] = $drRow['ffname'].' '.$drRow['fmname'].' '.$drRow['flname'];        
          $reminders[$i]['fromName'] =  ucwords(strtolower($reminders[$i]['fromName']));     
          $reminders[$i]['ToName'] = $drRow['tfname'].' '.$drRow['tmname'].' '.$drRow['tlname'];  
          $reminders[$i]['processedByName'] = (empty($prRow) ? 'N/A' : $prRow['ptitle'].' '.$prRow['pfname'].' '.$prRow['pmname'].' '.$prRow['plname']);  
      	} 
// --------- END OF loop through the results

        return $reminders;
     }      
?> 