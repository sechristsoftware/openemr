<?php
/**
 * This file used to edit the content of appt reminder
 *
 // Copyright (C) 2013 OMP <sgaddis@jse.net>
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;.
 *
 * @package OpenEMR
 * @author  Sherwin Gaddis <sgaddis@jse.net>
 * @author  Nathan Srinivas <seenu4043@gmail.com>
 * @link    http://www.open-emr.org
 */

//SANITIZE ALL ESCAPES
$sanitize_all_escapes=true;
//

//STOP FAKE REGISTER GLOBALS
$fake_register_globals=false;

       //Shows the registration form 
   if ($Username == null || $Password == null){
     echo "Please register software <br>";
	 include("register.php");
	 exit;
   }

require_once("../../interface/globals.php");
require_once("$srcdir/htmlspecialchars.inc.php"); // Security system
require_once("$srcdir/formdata.inc.php"); 
require_once("fetchLogin.php");

if ($_POST['form_save']) {

sqlStatement("UPDATE reminder_content SET appt_message = '" . add_escape_custom($_POST['appt_message']) . "',confirm_message = '". add_escape_custom($_POST['confirm_message']) . "',cancel_message = '" . add_escape_custom($_POST['cancel_message']) . "' where id='1'"); 
}  

$irow = sqlQuery("SELECT * FROM reminder_content WHERE id ='1'");

?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title><?php echo xlt("OpenEMR Appt Reminder"); ?></title>
<?php html_header_show();?>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<link rel='stylesheet' href='../../library/css/jquery-ui-1.10.3.custom.css' type='text/css'/>
<script type="text/javascript" src="../../library/js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="../../library/js/jquery-ui-1.10.3.custom.min.js"></script>
<script>
$(function() {
$( "#tabs" ).tabs();
});
</script>
<style type='text/css'>
.title{
font-size: 12px;
font-family: sans-serif;
}
.note
{
font-size: 11pt;
font-weight: normal;
width: 700px;
color: blue;
}
table.gridtable {
	font-family: verdana,arial,sans-serif;
	font-size:10pt;
	color:#333333;
	border-width: 1px;
	border-color: #666666;
	border-collapse: collapse;
}
table.gridtable th {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #666666;
	background-color: #dedede;
}
table.gridtable td {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #666666;
	background-color: #ffffff;
}
</style>
</head>
<body class="body_top">
<?php
       //Shows the registration form 
   if ($Username == null || $Password == null){
     echo "Please register software <br>";
	 include("register.php");
	 exit;
   }
?>

<form name='Appt_reminder' action='reminder_ajax.php' method='post'>
<div id="tabs">
<ul>
<li><a href="#tabs-1"><b><?php echo xlt("Appointment Message"); ?></b></a></li>
<li><a href="#tabs-2"><b><?php echo xlt("Appointment Confirmation Message"); ?></b></a></li>
<li><a href="#tabs-3"><b><?php echo xlt("Appointment Cancellation Message"); ?></b></a></li>
</ul>
<div id="tabs-1">
<span class="title"><?php echo xla('Put Your Appt Reminder Contents Here');?></span>
<textarea name='appt_message' rows='6' cols='40' wrap='virtual' style='width:100%'><?php echo text($irow['appt_message']); ?></textarea>
</div>
<div id="tabs-2">
<span class="title"><?php echo xla('Put Your Appt Confirmation Contents Here');?></span>
<textarea name='confirm_message' rows='6' cols='40' wrap='virtual' style='width:100%'><?php echo text($irow['confirm_message']); ?></textarea>
<br/><br/>
</div>
<div id="tabs-3">
<span class="title"><?php echo xla('Put Your Appt Cancellation Contents Here');?></span>
<textarea name='cancel_message' rows='6' cols='40' wrap='virtual' style='width:100%'><?php echo text($irow['cancel_message']); ?></textarea>
<br/><br/>
</div>
</div>
<br/><br/> 
<span class="note"><?php echo xlt("Note :: Please use below Keywords to mention the custom values on your messages.Use 1 for appointment confirmation,Use 2 for appointment cancellation"); ?></span><br/><br/>
<table class="gridtable">
<tr>
	<th><?php echo xlt("Usage"); ?></th><th><?php echo xlt("Keywords"); ?></th>
</tr>
<tr>
<td><?php echo xlt("Patient Name"); ?></td>
<td>{PATIENT_NAME}</td>
</tr>

<tr>
<td><?php echo xlt("Appt Date & Time"); ?></td>
<td>{APPT_DATETIME}</td>
</tr>
<tr>
<td><?php echo xlt("Appt Provider Name"); ?></td>
<td>{APPT_PROVIDER}</td>
</tr>
<tr>
<td><?php echo xlt("Appt Practice Name"); ?></td>
<td>{APPT_FACILITY}</td>
</tr>
<tr>
<td><?php echo xlt("Appt Practice Phone Number"); ?></td>
<td>{APPT_FACILITY_PHONE}</td>
</tr>
</table>
<br/>
<br/>
<center>
<input type='submit' name='form_save' value='<?php echo xla('Save'); ?>' />
</center>
</form>
</body>
</html>