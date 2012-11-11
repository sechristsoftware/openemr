<?php
/**
 * This file use is used specifically to look up drug information
 * from the rxnorm database.
 *
 * Copyright (C) 2012 Brady Miller <brady@sparmy.com>
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
 * @author  Brady Miller <brady@sparmy.com>
 * @link    http://www.open-emr.org
 */

$fake_register_globals=false;
$sanitize_all_escapes=true;

include_once("../../interface/globals.php");

// Collect the rxcui and tty and drug_name
//  rxcui is the rxnorm id number
//  tty is the term type (IN, MIN or BN)
//  drug_name is the drug name
$rxcui = trim($_GET['rxcui']);
if (empty($rxcui)) return;
$term_type = trim($_GET['term_type']);
if (empty($term_type)) return;
$drug_name = trim($_GET['drug_name']);
if (empty($drug_name)) return;

// Figure out the term_type to search for
if ($term_type == "BN") {
  // Use brand name to get form and dosage amounts
  $term_type_search = "SBD";
}
else if ($term_type == "MIN") {
  // Use multiple ingredients listing to get form and dosage amounts
  $term_type_search = "SCD";
}
else if ($term_type == "IN") {
  // use ingredient listing to get form and dosage amounts
  // (note need to traverse from SCDF to SCD to get everything,
  //  which is why this method has it's own query below)
  $term_type_search = "SCDF";
}
else {
  // invalid term_type, so exit script
  return;
}

// Collect drug information from rxnorm
$sql_bind = array();
if ($term_type == "IN") {
  // Collect information from rxnorm for IN(ingredient name)
  //  term type.
  $sql = "SELECT conso.*, rel.* " .
         "FROM `RXNREL` as rel, `RXNCONSO` as conso " .
         "WHERE conso.RXCUI = rel.RXCUI1 " .
         "AND rel.SAB = 'RXNORM' AND conso.SAB = 'RXNORM' AND rel.RELA = 'ingredient_of' " .
         "AND rel.RXCUI2 = ? AND conso.TTY = ?";
  array_push($sql_bind,$rxcui,$term_type_search);
}
else {
  // Collect information from rxnorm for BN(brand name) and
  //  MIN(multiple ingredients) term types.
  $sql = "SELECT conso.*, rel.* " .
         "FROM `RXNREL` as rel, `RXNCONSO` as conso " .
         "WHERE conso.RXCUI = rel.RXCUI1 " .
         "AND rel.SAB = 'RXNORM' AND conso.SAB = 'RXNORM' AND rel.RELA = 'ingredient_of' " .
         "AND rel.RXCUI2 = ? AND conso.TTY = ?";
  array_push($sql_bind,$rxcui,$term_type_search);
  if ($term_type = "BN") {
    # If chose a brand name, then only select that brand.
    $sql .= " AND conso.STR LIKE ? ";
    array_push($sql_bind,"%[".$drug_name."]%");
  }
}
$rez = sqlStatement($sql,$sql_bind);

error_log(sqlNumRows($rez),0);

// Return the results
if (sqlNumRows($rez) > 0) {
  $arr = array();
  for($iter=0; $row=sqlFetchArray($rez); $iter++)
    $arr[$iter]=$row;

  // Debugging
  error_log(print_r($arr,TRUE),0);

  echo json_encode($arr);
}
?>
