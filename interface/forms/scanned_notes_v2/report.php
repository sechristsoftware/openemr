<?php
/**
 * Version 2 of the Scanned Encounter Notes form report function.
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

include_once("../../globals.php");
include_once($GLOBALS["srcdir"] . "/api.inc");


function scanned_notes_v2_report($pid, $useless_encounter, $cols, $id) {
 global $webserver_root, $web_root, $encounter;

 // In the case of a patient report, the passed encounter is vital.
 $thisenc = $useless_encounter ? $useless_encounter : $encounter;

 $count = 0;

 $data = sqlQuery("SELECT * " .
  "FROM form_scanned_notes_v2 WHERE " .
  "id = ? AND activity = '1'", array($id) );

 if ($data) {
  if ($data['notes']) {
   echo "  <span class='bold'>" . xlt("Comments") . ": </span><span class='text'>";
   echo nl2br(text($data['notes'])) . "</span><br />\n";
  }
  if (!empty($data['doc_id'])) {
   echo "   <img src='".$web_root."/controller.php?document&retrieve&patient_id=".attr($pid). "&document_id=".attr($data['doc_id'])."' />\n";
  }
 }
}
?>
