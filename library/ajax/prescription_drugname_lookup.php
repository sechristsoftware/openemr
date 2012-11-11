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
include_once("{$GLOBALS['srcdir']}/rxnorm.inc.php");
include_once("{$GLOBALS['srcdir']}/formdata.inc.php");

// This is only pertinent for users of php versions less than 5.2
//  (ie. this wrapper is only loaded when php version is less than
//   5.2; otherwise the native php json functions are used)
require_once "$srcdir/jsonwrapper/jsonwrapper.php";

// Collect the search term
$term = trim($_GET['term']);
if (empty($term)) return;

// Collect the limit, if applicable
$limit = trim($_GET['limit']);

// Collect the search method
$method = trim($_GET['method']);

// Search
//  If rxnnorm tables exist, then search those.
//  If rxnorm tables do not exist, then search the drugs table.
//  @TODO In future, will need to likely integrate the rxnorm search with drugs table
//    with the drugs table search.
$rxnorm_flag = false;
$check_table = sqlQuery("SHOW TABLES LIKE 'RXNCONSO'");
if ( $method == "rxnorm" ) {
  //Search Rxnorm Database
  $arr = search_drug_names_rxnorm($term,$limit);
}
else { // $method == "drugs"
  //Search drugs table
  if (!empty($limit)) {
    $limit = " limit " . add_escape_custom($limit);
  }
  else {
    $limit = "";
  }
  $sql = "select drug_id, name, 'drugs' as drug_database from drugs where ".
            " name like ?".
            " order by name $limit";
  $rez = sqlStatement($sql, array($term."%") );
  $arr = array();
  for($iter=0; $row=sqlFetchArray($rez); $iter++) {
    $arr[$iter]=$row;
  }
}

// Return the results
echo json_encode($arr);

?>
