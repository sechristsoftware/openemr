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
require_once("../globals.php");
require_once("$srcdir/sql.inc");
require_once("$srcdir/formdata.inc.php");

$alertmsg = '';

/*		Inserting new provider number					*/
if (isset($_POST["mode"]) && $_POST["mode"] == "provider_number" && $_POST["newmode"] != "admin_medicare") {
	
	$insert_id=sqlInsert("INSERT INTO mc_provider_numbers SET " .
        "pid = '"         			. trim(formData('pid'        )) . "', " .
		"facility_id = '"   		. trim(formData('facility_id'  		 )) . "', " .
		"facility = '"   			. trim(formData('facility'  		 )) . "', " .
		"provider_number = '"   	. trim(formData('provider_number'  )) . "'");
		
	sqlStatement("UPDATE mc_provider_numbers, facility SET mc_provider_numbers.facility = facility.name WHERE facility.id = '" . trim(formData('facility_id')) . "' AND mc_provider_numbers.provider_number = '" . trim(formData('provider_number')) . "'");
		}

/*		Editing existing provider number					*/
if ($_POST["mode"] == "provider_number" && $_POST["newmode"] == "admin_medicare")
{
	sqlStatement("UPDATE mc_provider_numbers set 
		pid='"         				. trim(formData('pid'  		 )) . "' ,
		facility_id='"   			. trim(formData('facility_id'  		 )) . "' ,
		facility='"   				. trim(formData('facility'  		 )) . "' ,
		provider_number='"   		. trim(formData('provider_number'  )) . "'
		where id='" 				. trim(formData('mid')) . "'" );

		sqlStatement("UPDATE mc_provider_numbers, facility SET mc_provider_numbers.facility = facility.name WHERE facility.id = '" . trim(formData('facility_id')) . "' AND mc_provider_numbers.id = '" . trim(formData('mid')) . "'");
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
		<td><b><?php xl('Medicare Provider Numbers','e'); ?></b></td>
		<td><a href="mc_provider_add.php" class="iframe_small css_button"><span><?php xl('Add New Number','e'); ?></span></a>
		</td>
		<td><a href="usergroup_admin.php" class="css_button"><span><?php xl('Back to Users','e'); ?></span></a>
		</td>
	 </tr>
	</table>
    </div>
	
	<div style="width:400px;">
		<div>

			<table cellpadding="1" cellspacing="0" class="showborder">
				<tbody><tr height="22" class="showborder_head">
					<th width="180px"><b><?php xl('Username','e'); ?></b></th>
					<th width="270px"><b><?php xl('Real Name','e'); ?></b></th>
					<th width="190px"><b><span class="bold"><?php xl('Facility','e'); ?></span></b></th>
					<th width="100px"><b><span class="bold"><?php xl('Provider#','e'); ?></span></b></th>
				</tr>
					<?php
						$query = "SELECT * FROM users as u ";
						$query .= "INNER JOIN mc_provider_numbers as m ON u.id = m.pid ";
						$query .= "WHERE username != '' ";
						$query .= "ORDER BY username";
						$res = sqlStatement($query);
							for ($iter = 0;$row = sqlFetchArray($res);$iter++)
							  $result4[$iter] = $row;
							foreach ($result4 as $iter) {
					?>
				<tr height="20"  class="text" style="border-bottom: 1px dashed;">
				   <td class="text"><b><a href="mc_provider_admin.php?id=<?php echo $iter{id};?>" class="iframe_small" onclick="top.restoreSession()"><span><?php echo htmlspecialchars($iter{username});?></span></a></b>&nbsp;</td>
				   <td><span class="text"><?php echo htmlspecialchars($iter{fname}. " " .$iter{lname});?></span>&nbsp;</td>
				   <td><span class="text"><?php echo htmlspecialchars($iter{facility});?>&nbsp;</td>
				   <td><span class="text"><?php echo htmlspecialchars($iter{provider_number});?>&nbsp;</td>
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