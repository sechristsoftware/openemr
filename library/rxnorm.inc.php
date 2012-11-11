<?php
/**
 * This library is for functions that work with the rxnorm database.
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

require_once(dirname(__FILE__) . "/formdata.inc.php");

/**
 * Searches drug names from the rxnorm database.
 *
 * This function will search the rxnorm database for drug
 * names (the IN, MIN and BN term types) and will return
 * the results as an array with the following elements:<pre>
 *   drug_id       -> rxcui number
 *   name          -> drug name
 *   term_type     -> term type
 *   drug_database -> This is always 'rxnorm'
 * </pre>
 *
 * @param   string  $term   Drug Name Search Term
 * @param   string  $limit  Number of maximum entries to return
 * @return  array           See above comments for details
 */
function search_drug_names_rxnorm($term,$limit="") {
  //Create the limit string
  if (!empty($limit)) {
    $limit = " limit " . add_escape_custom($limit);
  }

  //Search rxnorm tables
  $sql = "SELECT `RXCUI` as `drug_id`, `STR` as `name`, `TTY` as `term_type`, 'rxnorm' as drug_database from `RXNCONSO` " .
         "WHERE `SAB` = 'RXNORM' " .
         "AND (`TTY` = 'IN' OR `TTY` = 'MIN' OR `TTY` = 'BN') " .
         "AND (`STR` LIKE ? OR `STR` LIKE ?) " .
         "ORDER BY `TTY`, `STR` $limit";
  $rez = sqlStatement($sql, array($term."%","% / ".$term."%") );
  $arr = array();
  for($iter=0; $row=sqlFetchArray($rez); $iter++) {
    $arr[$iter]=$row;
  }

  //Return results as an array
  return $arr;
}

/**
 * Returns full drug names (including form and strength from
 * and rxcui id (of term type IN, MIN or BN).
 *
 * This function will return the results as an array with the
 * following elements:<pre>
 *   rxcui             -> rxcui number
 *   full_name         -> full drug name (including form and strength)
 *   term_type         -> term type
 *   initial_term_type -> initial term type utilized to select this drug
 * </pre>
 *
 * @param   int    $rxcui      rxcui id (of term type IN,MIN, or BN)
 * @param   string $term_type  Rxnorm term type (IN, MIN, or BN)
 * @return  string $drug_name  Drug name
 * @return  array              See above comments for details
 */
function rxcui_resolve_rxnorm($rxcui,$term_type,$drug_name) {

  // Figure out the term_type to search for
  if ($term_type == "BN") {
    // Use brand name to get form and dosage amounts
    $term_type_search = "SBD";
    $relationship_search = "ingredient_of";
  }
  else if ($term_type == "MIN") {
    // Use multiple ingredients listing to get form and dosage amounts
    $term_type_search = "SCD";
    $relationship_search = "ingredients_of";
  }
  else if ($term_type == "IN") {
    // use ingredient listing to get form and dosage amounts
    // (note need to traverse from SCDF to SCD to get everything,
    //  which is why this method has it's own query below)
    $term_type_search = "SCDF";
    $relationship_search = "ingredient_of";
    $next_term_type_search ="SCD";
    $next_relationship_search = "inverse_isa";
  }
  else {
    // invalid term_type, so exit function
    return;
  }

  // Collect drug information from rxnorm
  $sql_bind = array();
  if ($term_type == "IN") {
    // Collect RXCUIs of the forms only (which are then used in query below to get complete rxnorm information)
    $sql_rxcui = "SELECT conso.RXCUI as rxcui " .
                 "FROM `RXNCONSO` as conso " .
                 "JOIN `RXNREL` as rel ON conso.RXCUI = rel.RXCUI1 " .
                 "WHERE rel.SAB = 'RXNORM' AND conso.SAB = 'RXNORM' AND conso.STR NOT LIKE '% / %' " .
                 "AND rel.RXCUI2 = ? AND conso.TTY = ? AND rel.RELA = ?";
    $rez_rxcui = sqlStatement($sql_rxcui,array($rxcui,$term_type_search,$relationship_search));
    $arr_rxcui = array();
    for($iter=0; $row=sqlFetchArray($rez_rxcui); $iter++) {
      $arr_rxcui[$iter]=$row['rxcui'];
    }
    //Collect information from rxnorm
    $sql = "SELECT conso.RXCUI as rxcui, conso.STR as full_name, conso.TTY as term_type, '" . add_escape_custom($term_type) . "' as initial_term_type " .
           "FROM `RXNCONSO` as conso " .
           "JOIN `RXNREL` as rel ON conso.RXCUI = rel.RXCUI1 " .
           "WHERE rel.SAB = 'RXNORM' AND conso.SAB = 'RXNORM' " .
           "AND rel.RXCUI2 IN (" . add_escape_custom(implode(',',$arr_rxcui)) . ") AND conso.TTY = ? AND rel.RELA = ?";
    array_push($sql_bind,$next_term_type_search,$next_relationship_search);
  }
  else {
    // Collect information from rxnorm for BN(brand name) and
    //  MIN(multiple ingredients) term types.
    $sql = "SELECT conso.RXCUI as rxcui, conso.STR as full_name, conso.TTY as term_type, '" . add_escape_custom($term_type) . "' as initial_term_type " .
           "FROM `RXNCONSO` as conso " .
           "JOIN `RXNREL` as rel ON conso.RXCUI = rel.RXCUI1 " .
           "WHERE rel.SAB = 'RXNORM' AND conso.SAB = 'RXNORM' " .
           "AND rel.RXCUI2 = ? AND conso.TTY = ? AND rel.RELA = ?";
    array_push($sql_bind,$rxcui,$term_type_search,$relationship_search);
    if ($term_type == "BN") {
      # If chose a brand name, then only select that brand.
      $sql .= " AND conso.STR LIKE ? ";
      array_push($sql_bind,"%[".$drug_name."]%");
    }
  }
  $rez = sqlStatement($sql,$sql_bind);
  for($iter=0; $row=sqlFetchArray($rez); $iter++) {
    $arr[$iter]=$row;
  }

  // Return the results
  return $arr;
}

?>
