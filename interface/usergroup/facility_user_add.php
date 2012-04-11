<?php
// +-----------------------------------------------------------------------------+
// Copyright (C) 2012 NP Clinics <info@npclinics.com.au>
//
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
//
// A copy of the GNU General Public License is included along with this program:
// openemr/interface/login/GnuGPL.html
// For more information write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//
// Author:   Scott Wakefield <scott@npclinics.com.au>
//
// +------------------------------------------------------------------------------+

//SANITIZE ALL ESCAPES
$sanitize_all_escapes=true;


//STOP FAKE REGISTER GLOBALS
$fake_register_globals=false;


require_once("../globals.php");
require_once("$srcdir/sql.inc");
require_once("$srcdir/formdata.inc.php");
require_once("$srcdir/options.inc.php");

$alertmsg = '';

?>
<html>
<head>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<link rel="stylesheet" type="text/css" href="<?php echo $GLOBALS['webroot'] ?>/library/js/fancybox/jquery.fancybox-1.2.6.css" media="screen" />
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dialog.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery.1.3.2.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/common.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/fancybox/jquery.fancybox-1.2.6.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery-ui.js"></script>
<?php
// Old Browser comp trigger on js

if (isset($_POST["mode"]) && $_POST["mode"] == "facility_user_id") {
  	echo '
<script type="text/javascript">
<!--
parent.$.fn.fancybox.close();
//-->
</script>

	';
}
?>
<script type="text/javascript">
function submitform() {

        top.restoreSession();
        document.forms[0].submit();
	  
}

$(document).ready(function(){
    $("#cancel").click(function() {
		  parent.$.fn.fancybox.close();
	 });
});

</script>

</head>
<body class="body_top">
<table>
	<tr>
		<td>
			<span class="title"><?php echo xlt('Add Facility User ID'); ?></span>&nbsp;&nbsp;&nbsp;</td>
			<td colspan=5 align=center style="padding-left:2px;">
				<a onclick="submitform();" class="css_button large_button" name='form_save' id='form_save' href='#'>
					<span class='css_button_span large_button_span'><?php echo xlt('Save');?></span>
				</a>
				<a class="css_button large_button" id='cancel' href='#' >
					<span class='css_button_span large_button_span'><?php echo xlt('Cancel');?></span>
				</a>
		</td>
	</tr>
</table>
<br>

<form name='medicare' method='post' action="facility_user.php" target='_parent'>
<input type=hidden name=mode value="facility_user_id">
<table border=0 cellpadding=0 cellspacing=0 style="width:450px;">
	<tr>
		<td>
			<span class="text"><?php echo xlt('User'); ?>: </span>
		</td>
		<td>
			<select style="width:150px;" name=pid>
				<?php
				$fres = sqlStatement("select * from users WHERE authorized = 1 AND active = 1 order by username");
				if ($fres) {
				  for ($iter = 0;$frow = sqlFetchArray($fres);$iter++)
					$result[$iter] = $frow;
				  foreach($result as $iter) {
				?>
				<option value="<?php echo attr($iter{id});?>"><?php echo text($iter{username});?></option>
				<?php
				}
				}
				?>
			</select>
		</td>
	</tr>
	
	<tr>
		<td>
			<span class="text"><?php echo xlt('Facility'); ?>: </span>
		</td>
		<td>
			<select style="width:150px;" name=facility_id>
				<?php
				$fres = sqlStatement("select * from facility where service_location != 0 order by name");
				if ($fres) {
				  for ($iter1 = 0;$frow = sqlFetchArray($fres);$iter1++)
					$result[$iter1] = $frow;
				  foreach($result as $iter1) {
				?>
				<option value="<?php echo attr($iter1{id});?>"><?php echo text($iter1{name});?></option>
				<?php
				}
				}
				?>
			</select>
		</td>
	</tr>
	
	<tr>
		<td style="width:150px;">
			<span class="text"><?php echo xlt('User ID'); ?>: </span>
		</td>
		<td  style="width:150px;">
			<input type=entry name=user_id style="width:150px;">
		</td>
	</tr>

</table>
</form>


<script language="JavaScript">
<?php
  if ($alertmsg = trim($alertmsg)) {
    echo "alert(" . addslashes('$alertmsg') . ");\n";
  }
?>
</script>
</body>
</html>
