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

$alertmsg = '';

/*	Inserting new facility user id	*/
if (isset($_POST["mode"]) && $_POST["mode"] == "facility_user_id" && $_POST["newmode"] != "admin_facility_user") {
	
	$insert_id=sqlInsert("INSERT INTO facility_provider_ids SET pid = ?, facility_id = ?, user_id=?", array(trim($_POST['pid']), trim($_POST['facility_id']), trim($_POST['user_id'])) );
		}

/*	Editing existing facility user id  */
if ($_POST["mode"] == "facility_user_id" && $_POST["newmode"] == "admin_facility_user")
{
	sqlStatement("UPDATE facility_user_ids SET pid = ?, facility_id = ?, user_id=? WHERE id=?", array(trim($_POST['pid']), trim($_POST['facility_id']), trim($_POST['user_id']), trim($_POST['mid'])) );
		}

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
<script type="text/javascript">

$(document).ready(function(){

    // fancy box
    enable_modals();
    
    // special size for
	$(".iframe_small").fancybox( {
		'overlayOpacity' : 0.0,
		'showCloseButton' : true,
		'frameHeight' : 300,
		'frameWidth' : 500
	});
	
	$(function(){
		// add drag and drop functionality to fancybox
		$("#fancy_outer").easydrag();
	});
});

</script>

</head>
<body class="body_top">

<div>
    <div>
       <table>
	  <tr >
		<td><b><?php echo xlt('Facility User IDs'); ?></b></td>
		<td><a href="facility_user_add.php" class="iframe_small css_button" onclick="top.restoreSession()"><span><?php echo xlt('Add New Facility User ID'); ?></span></a>
		</td>
		<td><a href="usergroup_admin.php" class="css_button" onclick="top.restoreSession()"><span><?php echo xlt('Back to Users'); ?></span></a>
		</td>
	 </tr>
	</table>
    </div>
	
	<div style="width:400px;">
		<div>

			<table cellpadding="1" cellspacing="0" class="showborder">
				<tbody><tr height="22" class="showborder_head">
					<th width="180px"><b><?php echo xlt('Username'); ?></b></th>
					<th width="270px"><b><?php echo xlt('Full Name'); ?></b></th>
					<th width="190px"><b><span class="bold"><?php echo xlt('Facility'); ?></span></b></th>
					<th width="100px"><b><span class="bold"><?php echo xlt('User ID'); ?></span></b></th>
				</tr>
					<?php
						$query = "SELECT *, u.id as uid, fp.id as fpid, f.id as fid FROM users as u ";
						$query .= "INNER JOIN facility_user_ids as fp ON u.id = fp.pid ";
						$query .= "INNER JOIN facility as f ON fp.facility_id = f.id ";
						$query .= "WHERE username != '' ";
						$query .= "ORDER BY username";
						$res = sqlStatement($query);
							for ($iter = 0;$row = sqlFetchArray($res);$iter++)
							  $result4[$iter] = $row;
							foreach ($result4 as $iter) {
					?>
				<tr height="20"  class="text" style="border-bottom: 1px dashed;">
				   <td class="text"><b><a href="facility_user_admin.php?id=<?php echo $iter{fpid};?>" class="iframe_small" onclick="top.restoreSession()"><span><?php echo text($iter{username});?></span></a></b>&nbsp;</td>
				   <td><span class="text"><?php echo text($iter{fname}. " " .$iter{lname});?></span>&nbsp;</td>
				   <td><span class="text"><?php echo text($iter{name});?>&nbsp;</td>
				   <td><span class="text"><?php echo text($iter{user_id});?>&nbsp;</td>
				</tr>
				<?php
				}
				?>
				</tbody>
			</table>
		</div>
    </div>
</div>
</body>
</html>