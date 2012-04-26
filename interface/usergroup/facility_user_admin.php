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


if (isset($_GET["id"])) {
	$my_id = $_GET["id"];
}

if (isset($_POST["id"])) {
	$my_id = $_POST["id"];
}
if ($_POST["mode"] == "facility_user_id")
{
  
  echo '
<script type="text/javascript">
<!--
parent.$.fn.fancybox.close();
//-->
</script>

	';
  

}
?>

<html>
<head>

<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<link rel="stylesheet" type="text/css" href="../../library/js/fancybox/jquery.fancybox-1.2.6.css" media="screen" />
<script type="text/javascript" src="../../library/dialog.js"></script>
<script type="text/javascript" src="../../library/js/jquery.1.3.2.js"></script>
<script type="text/javascript" src="../../library/js/common.js"></script>
<script type="text/javascript" src="../../library/js/fancybox/jquery.fancybox-1.2.6.js"></script>
<script language="JavaScript">

function submitform() {
	top.restoreSession();
	var flag=0;
	function trimAll(sString)
	{
		while (sString.substring(0,1) == ' ')
		{
			sString = sString.substring(1, sString.length);
		}
		while (sString.substring(sString.length-1, sString.length) == ' ')
		{
			sString = sString.substring(0,sString.length-1);
		}
		return sString;
	}
	if(flag == 0){
		document.forms[0].submit();
		parent.$.fn.fancybox.close(); 
	}
	
	
	
}

$(document).ready(function(){
    $("#cancel").click(function() {
		  parent.$.fn.fancybox.close();
	 });

});
</script>

</head>
<body class="body_top" style="width:450px;height:200px !important;">

<table>
    <tr>
        <td>
        <span class="title"><?php echo xlt('Edit Facility User ID'); ?></span>&nbsp;&nbsp;&nbsp;</td><td>
        <a class="css_button large_button" name='form_save' id='form_save' onclick='submitform()' href='#' >
            <span class='css_button_span large_button_span'><?php echo xlt('Save');?></span>
        </a>
        <a class="css_button large_button" id='cancel' href='#'>
            <span class='css_button_span large_button_span'><?php echo xlt('Cancel');?></span>
        </a>
     </td>
  </tr>
</table>

<form name='medicare' method='post' action="facility_user.php" target="_parent">
    <input type=hidden name=mode value="facility_user_id">
    <input type=hidden name=newmode value="admin_facility_user">	<!--	Diffrentiate Admin and add post backs -->
    <input type=hidden name=mid value="<?php echo attr($my_id);?>">
    <?php $iter = sqlQuery("select * from facility_user_ids where id='$my_id'"); ?>

<table border=0 cellpadding=0 cellspacing=0>
<tr>
	<td>
		<span class=text><?php echo xlt('User'); ?>: </span>
	</td>
	<td>
		<select name=pid style="width:150px;" >
			<?php
			$fres = sqlStatement("select * from users WHERE authorized = 1 AND active = 1 order by username");
			if ($fres) {
			for ($iter3 = 0; $frow = sqlFetchArray($fres); $iter3++)
				$result[$iter3] = $frow;
			foreach($result as $iter3) {
			?>
			<option value="<?php echo attr($iter3{id}); ?>" <?php if ($iter{pid} == $iter3{id}) echo "selected"; ?>><?php echo text($iter3{username}); ?></option>
			<?php
			}
			}
			?>
		</select>
	</td>
</tr>

<tr>
	<td>
		<span class=text><?php echo xlt('Facility'); ?>: </span>
	</td>
	<td>
		<select name=facility_id style="width:150px;" >
			<?php
			$fres = sqlStatement("select * from facility where service_location != 0 order by name");
			if ($fres) {
			for ($iter2 = 0; $frow = sqlFetchArray($fres); $iter2++)
				$result[$iter2] = $frow;
			foreach($result as $iter2) {
			?>
			<option value="<?php echo attr($iter2{id}); ?>" <?php if ($iter{facility_id} == $iter2{id}) echo "selected"; ?>><?php echo text($iter2{name}); ?></option>
			<?php
			}
			}
			?>
		</select>
	</td>
</tr>

<tr>
	<td style="width:180px;">
		<span class=text><?php echo xlt('User ID'); ?>: </span>
	</td>
	<td style="width:270px;">
		<input type=entry name=user_id  style="width:150px;" value="<?php echo text($iter{user_id}); ?>">
	</td>
</tr>

</table>
</form>
</body>
</html>

