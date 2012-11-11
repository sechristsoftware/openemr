<?php
/**
 * This file use is used specifically to look up drug names when
 * writing a prescription.
 *
 * See the file templates/prescriptions/general_edit.html
 * for additional information.
 *
 * Copyright (C) 2008 Jason Morrill <jason@italktech.net>
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
 * @author  Jason Morrill <jason@italktech.net>
 * @author  Brady Miller <brady@sparmy.com>
 * @link    http://www.open-emr.org
 */

$fake_register_globals=false;
$sanitize_all_escapes=true;

include_once("../../interface/globals.php");
include_once("{$GLOBALS['srcdir']}/sql.inc");
include_once("{$GLOBALS['srcdir']}/formdata.inc.php");

// Collect the search term
$term = trim($_GET['term']);
if (empty($term)) return;

// Collect the limit, if applicable
$limit = trim($_GET['limit']);
if (!empty($limit)) {
  $limit = " limit " . add_escape_custom($limit);
}
else {
  $limit = "";
}  

// Search
//  If rxnnorm tables exist, then search those.
//  If rxnorm tables do not exist, then search the drugs table.
//  @TODO In future, will need to likely integrate the rxnorm search
//    with the drugs table search.
$rxnorm_flag = false;
$check_table = sqlQuery("SHOW TABLES LIKE 'RXNCONSO'");
if ( !(empty($check_table)) ) {
  $rxnorm_flag = true;
}

if ($rxnorm_flag) {
  //Search rxnorm tables
  $sql = "SELECT `RXCUI` as `drug_id`, `STR` as `name`, 'rxnorm' as drug_database from `RXNCONSO` " .
         "WHERE `SAB` = 'RXNORM' " .
         "AND `STR` like ? " .
         "ORDER BY `STR` $limit";
  $rez = sqlStatement($sql, array($term."%") );
}
else {
  //Search drugs table
  $sql = "select drug_id, name, 'drugs' as drug_database from drugs where ".
            " name like ?".
            " order by name $limit";
  $rez = sqlStatement($sql, array($term."%") );
}

// Return the results
if (sqlNumRows($rez) > 0) {
  $arr = array();
  while ($row = sqlFetchArray($rez)) {
    $arr[] = array("name"=>$row['name'],"drug_id"=>$row['drug_id'],"drug_database"=>$row['drug_database']);
  }
  echo json_encode($arr);
}

?>
