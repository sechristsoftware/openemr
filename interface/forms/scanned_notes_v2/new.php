<?php
/**
 * Version 2 of the Scanned Encounter Notes form.
 *
 * Copyright (C) 2006-2010 Rod Roark <rod@sunsetsystems.com>
 * Copyright (C) 2013      Brady Miller <brady@sparmy.com>
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
 * @author  Rod Roark <rod@sunsetsystems.com>
 * @author  Brady Miller <brady@sparmy.com>
 * @link    http://www.open-emr.org
 */

$fake_register_globals=false;
$sanitize_all_escapes=true;

include_once("../../globals.php");
include_once("$srcdir/api.inc");
include_once("$srcdir/forms.inc");
include_once("$srcdir/acl.inc");
require_once("$srcdir/documents.php");

$row = array();

if (! $encounter) { // comes from globals.php
 die(xlt("Internal error: we do not seem to be in an encounter!"));
}

$formid = $_GET['id'];

// If Save was clicked, save the info.
//
if ($_POST['bn_save']) {

 // If updating an existing form...
 //
 if ($formid) {

  //TODO
  // If the document has been changed, then remove the old document and
  // add the new document. Can use the name to see if it has been changes.

  $query = "UPDATE form_scanned_notes_v2 SET " .
   "notes = ? " .
   "WHERE id = ?";
  sqlStatement($query, array($_POST['form_notes'],$formid) );
 }

 // If adding a new form...
 else {
  // add the form and get the formid
  $formid = sqlInsert("INSERT INTO form_scanned_notes_v2 (notes) VALUES (?)", array($_POST['form_notes']) );
  // if file was attached, then add the file as a documents
  if ($_FILES['form_image']['size']) {
   // Collect the category id for 'Scanned Encounter Notes', if exists.
   $category_id = document_category_to_id("Scanned Encounter Notes");
   if (!$category_id) {
     $category_id = 1;
   }
   // Save the document
   $document_info = addNewDocument($encounter."_".$formid."_".$_FILES['form_image']['name'],
                                 $_FILES['form_image']['type'],
                                 $_FILES['form_image']['tmp_name'],
                                 $_FILES['form_image']['error'],
                                 $_FILES['form_image']['size'],
                                 $_SESSION['authUserID'],
                                 $pid,
                                 $category_id,
                                 'scanned_encounter_notes/'.$pid,
                                 2);
   if ($document_info) {
    // Record the saved document id in the form_scanned_notes_v2 form entry
    sqlStatement("UPDATE form_scanned_notes_v2 SET doc_id=? WHERE id=?", array($document_info['doc_id'],$formid) );
   }
  }
  // Add the form to the form tracker
  addForm($encounter, "Scanned Notes", $formid, "scanned_notes_v2", $pid, $userauthorized);
 }
}

if ($formid) {
 $row = sqlQuery("SELECT * FROM form_scanned_notes_v2 WHERE " .
  "id = ? AND activity = '1'", array($formid));
 $formrow = sqlQuery("SELECT id FROM forms WHERE " .
  "form_id = ? AND formdir = 'scanned_notes_v2'", array($formid) );
}
?>
<html>
<head>
<?php html_header_show();?>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<style type="text/css">
 .dehead    { color:#000000; font-family:sans-serif; font-size:10pt; font-weight:bold }
 .detail    { color:#000000; font-family:sans-serif; font-size:10pt; font-weight:normal }
</style>
<script type="text/javascript" src="../../../library/dialog.js"></script>

<script language='JavaScript'>

 function newEvt() {
  dlgopen('../../main/calendar/add_edit_event.php?patientid=<?php echo attr($pid) ?>',
   '_blank', 550, 270);
  return false;
 }

 // Process click on Delete button.

 // TODO, when do this, may need to also remove the document associated with this form

 function deleteme() {
  dlgopen('../../patient_file/deleter.php?formid=<?php echo attr($formrow['id']) ?>', '_blank', 500, 450);
  return false;
 }

 // Called by the deleteme.php window on a successful delete.
 function imdeleted() {
  top.restoreSession();
  location = '<?php echo $GLOBALS['form_exit_url']; ?>';
 }

</script>

</head>

<body class="body_top">

<form method="post" enctype="multipart/form-data"
 action="<?php echo $rootdir ?>/forms/scanned_notes_v2/new.php?id=<?php echo attr($formid) ?>"
 onsubmit="return top.restoreSession()">

<center>

<p>
<table border='1' width='95%'>

 <tr bgcolor='#dddddd' class='dehead'>
  <td colspan='2' align='center'><?php echo xlt('Scanned Encounter Notes'); ?></td>
 </tr>

 <tr>
  <td width='5%'  class='dehead' nowrap>&nbsp;<?php echo xlt('Comments'); ?>&nbsp;</td>
  <td width='95%' class='detail' nowrap>
   <textarea name='form_notes' rows='4' style='width:100%'><?php echo attr($row['notes']) ?></textarea>
  </td>
 </tr>

 <tr>
  <td class='dehead' nowrap>&nbsp;<?php echo xlt('Document'); ?>&nbsp;</td>
  <td class='detail' nowrap>
<?php
if ($formid && !empty($row['doc_id'])) {
 echo "   <img src='".$web_root."/controller.php?document&retrieve&patient_id=".attr($pid). "&document_id=".attr($row['doc_id'])."' />\n";
}
?>
   <p>&nbsp;
   <?php echo xlt('Upload this file:') ?>
   <input type="hidden" name="MAX_FILE_SIZE" value="12000000" />
   <input name="form_image" type="file" />
   <br />&nbsp;</p>
  </td>
 </tr>

</table>

<p>
<input type='submit' name='bn_save' value='<?php echo xla('Save'); ?>' />
&nbsp;
<input type='button' value='<?php echo xla('Add Appointment'); ?>' onclick='newEvt()' />
&nbsp;
<input type='button' value='<?php echo xla('Back'); ?>' onclick="top.restoreSession();location='<?php echo $GLOBALS['form_exit_url']; ?>'" />
<?php if ($formrow['id'] && acl_check('admin', 'super')) { ?>
&nbsp;
<input type='button' value='<?php echo xla('Delete'); ?>' onclick='deleteme()' style='color:red' />
<?php } ?>
</p>

</center>

</form>
</body>
</html>
