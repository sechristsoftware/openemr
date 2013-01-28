<?php
/**
 * Library and data structure to manage Code Types and code type lookups.
 *
 * The data structure is the $code_types array.
 * The $code_types array is built from the code_types sql table and provides
 * abstraction of diagnosis/billing code types.  This is desirable
 * because different countries or fields of practice use different methods for
 * coding diagnoses, procedures and supplies.  Fees will not be relevant where
 * medical care is socialized.
 * <pre>Attributes of the $code_types array are:
 *  active   - 1 if this code type is activated
 *  id       - the numeric identifier of this code type in the codes table
 *  claim    - 1 if this code type is used in claims
 *  fee      - 1 if fees are used, else 0
 *  mod      - the maximum length of a modifier, 0 if modifiers are not used
 *  just     - the code type used for justification, empty if none
 *  rel      - 1 if other billing codes may be "related" to this code type
 *  nofs     - 1 if this code type should NOT appear in the Fee Sheet
 *  diag     - 1 if this code type is for diagnosis
 *  proc     - 1 if this code type is a procedure/service
 *  label    - label used for code type
 *  external - 0 for storing codes in the code table
 *             1 for storing codes in external ICD10 Diagnosis tables
 *             2 for storing codes in external SNOMED (RF1) Diagnosis tables
 *             3 for storing codes in external SNOMED (RF2) Diagnosis tables
 *             4 for storing codes in external ICD9 Diagnosis tables
 *             5 for storing codes in external ICD9 Procedure/Service tables
 *             6 for storing codes in external ICD10 Procedure/Service tables
 *             7 for storing codes in external SNOMED Clinical Term tables
 *  term     - 1 if this code type is used as a clinical term
 *  </pre>
 *
 * Copyright (C) 2006-2010 Rod Roark <rod@sunsetsystems.com>
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

require_once(dirname(__FILE__)."/../library/csv_like_join.php");

$code_types = array();
$default_search_type = '';
$ctres = sqlStatement("SELECT * FROM code_types WHERE ct_active=1 ORDER BY ct_seq, ct_key");
while ($ctrow = sqlFetchArray($ctres)) {
  $code_types[$ctrow['ct_key']] = array(
    'active' => $ctrow['ct_active'  ],
    'id'   => $ctrow['ct_id'  ],
    'fee'  => $ctrow['ct_fee' ],
    'mod'  => $ctrow['ct_mod' ],
    'just' => $ctrow['ct_just'],
    'rel'  => $ctrow['ct_rel' ],
    'nofs' => $ctrow['ct_nofs'],
    'diag' => $ctrow['ct_diag'],
    'mask' => $ctrow['ct_mask'],
    'label'=> ( (empty($ctrow['ct_label'])) ? $ctrow['ct_key'] : $ctrow['ct_label'] ),
    'external'=> $ctrow['ct_external'],
    'claim' => $ctrow['ct_claim'],
    'proc' => $ctrow['ct_proc'],
    'term' => $ctrow['ct_term']
  );
  if ($default_search_type === '') $default_search_type = $ctrow['ct_key'];
}

/**
 * This array stores the external table options. See above for $code_types array
 * 'external' attribute  for explanation of the option listings.
 * @var array
 */
$cd_external_options = array(
  '0' => xl('No'),
  '4' => xl('ICD9 Diagnosis'),
  '5' => xl('ICD9 Procedure/Service'),
  '1' => xl('ICD10 Diagnosis'),
  '6' => xl('ICD10 Procedure/Service'),
  '2' => xl('SNOMED (RF1) Diagnosis'),
  '3' => xl('SNOMED (RF2) Diagnosis'),
  '7' => xl('SNOMED Clinical Term')
);

/**
 * Checks is fee are applicable to any of the code types.
 *
 * @return boolean
 */
function fees_are_used() {
 global $code_types;
 foreach ($code_types as $value) { if ($value['fee'] && $value['active']) return true; }
 return false;
}

/**
 * Checks is modifiers are applicable to any of the code types.
 * (If a code type is not set to show in the fee sheet, then is ignored)
 *
 * @param  boolean $fee_sheet Will ignore code types that are not shown in the fee sheet
 * @return boolean
 */
function modifiers_are_used($fee_sheet=false) {
 global $code_types;
 foreach ($code_types as $value) {
  if ($fee_sheet && !empty($value['nofs'])) continue;
  if ($value['mod'] && $value['active']) return true;
 }
 return false;
}

/**
 * Checks if justifiers are applicable to any of the code types.
 *
 * @return boolean
 */
function justifiers_are_used() {
 global $code_types;
 foreach ($code_types as $value) { if (!empty($value['just']) && $value['active']) return true; }
 return false;
}

/**
 * Checks is related codes are applicable to any of the code types.
 *
 * @return boolean
 */
function related_codes_are_used() {
 global $code_types;
 foreach ($code_types as $value) { if ($value['rel'] && $value['active']) return true; }
 return false;
}

/**
 * Convert a code type id (ct_id) to the key string (ct_key)
 *
 * @param  integer $id
 * @return string
 */
function convert_type_id_to_key($id) {
 global $code_types;
 foreach ($code_types as $key => $value) {
  if ($value['id'] == $id) return $key;
 } 
}

/**
 * Return listing of pertinent and active code types.
 *
 * Function will return listing (ct_key) of pertinent
 * active code types, such as diagnosis codes or procedure
 * codes in a chosen format. Supported returned formats include
 * as 1) an array and as 2) a comma-separated lists that has been
 * process by urlencode() in order to place into URL  address safely.
 *
 * @param  string       $category       category of code types('diagnosis', 'procedure' or 'clinical_term')
 * @param  string       $return_format  format or returned code types ('array' or 'csv')
 * @return string/array
 */
function collect_codetypes($category,$return_format="array") {
 global $code_types;

 $return = array();

 foreach ($code_types as $ct_key => $ct_arr) {
  if (!$ct_arr['active']) continue;

  if ($category == "diagnosis") {
   if ($ct_arr['diag']) {
    array_push($return,$ct_key);
   }
  }
  else if ($category == "procedure") {
   if ($ct_arr['proc']) {
    array_push($return,$ct_key);
   }
  }
  else if ($category == "clinical_term") {
   if ($ct_arr['term']) {
    array_push($return,$ct_key);
   }
  }
  else {
   //return nothing since no supported category was chosen
  }
 }

 if ($return_format == "csv") {
  //return it as a csv string
  return csv_like_join($return);
 }
 else { //$return_format == "array"
  //return the array
  return $return;
 }
}

/**
 * Main code set searching function.
 *
 * Function is able to search a variety of code sets. See the 'external' items in the comments at top
 * of this page for a listing of the code sets supported. Also note that Products (using PROD as code type)
 * is also supported.
 *
 * @param  string    $form_code_type  code set key (special keywords are PROD and --ALL--)
 * @param  string    $search_term     search term
 * @param  boolean   $count           if true, then will only return the number of entries
 * @param  boolean   $active          if true, then will only return active entries (not pertinent for PROD code sets)
 * @param  boolean   $return_only_one if true, then will only return one perfect matching item
 * @param  integer   $start           Query start limit (for pagination)
 * @param  integer   $number          Query number returned (for pagination)
 * @param  array     $filter_elements Array that contains elements to filter
 * @param  array     $limit           Number of results to return (NULL means return all); note this is ignored if set $start/number
 * @param  array     $mode            'default' mode searches code and description, 'code' mode only searches code, 'description' mode searches description (and separates words); note this is ignored if set $return_only_one to TRUE
 * @return recordset 
 */
function code_set_search($form_code_type,$search_term="",$count=false,$active=true,$return_only_one=false,$start=NULL,$number=NULL,$filter_elements=array(),$limit=NULL,$mode='default') {
  global $code_types;

  if ( !is_null($start) && !is_null($number) ) {
    // For pagination of results
    $limit_query = " LIMIT $start, $number ";
  }
  else if (!is_null($limit)) {
    $limit_query = " LIMIT $limit ";
  }
  else {
    // No pagination and no limit
    $limit_query = '';
  }

  if ($return_only_one) {
     // Only return one result (this is where only matching for exact code match)
     // Note this overrides the above limit settings
     $limit_query = " LIMIT 1 ";
  }

  if ($mode == 'description') {
    // Separate the description keywords into an array
    $description_keywords=preg_split("/ /",$search_term,-1,PREG_SPLIT_NO_EMPTY);
  }

  // build the filter_elements sql code
  $query_filter_elements="";
  if (!empty($filter_elements)) {
   foreach ($filter_elements as $key => $element) {
    $query_filter_elements .= " AND c." . add_escape_custom($key) . "=" . "'" . add_escape_custom($element)  . "' ";
   }
  }

  if ($form_code_type == 'PROD') { // Search for products/drugs
   // Note this does not support $active, $return_only_one, $filter_elements, $mode options
   $query = "SELECT dt.drug_id, dt.selector, d.name " .
            "FROM drug_templates AS dt, drugs AS d WHERE " .
            "( d.name LIKE ? OR " .
            "dt.selector LIKE ? ) " .
            "AND d.drug_id = dt.drug_id " .
            "ORDER BY d.name, dt.selector, dt.drug_id $limit_query";
   $res = sqlStatement($query, array("%".$search_term."%", "%".$search_term."%") );
  }

  else if ($form_code_type == '--ALL--') { // Search all codes from the default codes table
   // Note this will not search the external code sets
   // Note this does not support $return_only_one, $mode options
   $active_query = '';
   if ($active) {
    // Only filter for active codes
    $active_query=" AND c.active = 1 ";
   }
   $query = "SELECT c.id, c.code_text, c.code_text_short, c.code, c.code_type, c.modifier, c.units, c.fee, " .
            "c.superbill, c.related_code, c.taxrates, c.cyp_factor, c.active, c.reportable, c.financial_reporting, " .
            "ct.ct_key as code_type_name " .
            "FROM `codes` as c " .
            "LEFT OUTER JOIN `code_types` as ct " .
            "ON c.code_type = ct.ct_id " .
            "WHERE (c.code_text LIKE ? OR " .
            "c.code LIKE ?) AND ct.ct_external = '0' " .
            " $active_query " .
            " $query_filter_elements " .
            "ORDER BY code_type,code+0,code $limit_query";
   $res = sqlStatement($query, array("%".$search_term."%", "%".$search_term."%") );
  }

  else if ( !($code_types[$form_code_type]['external']) ) { // Search from default codes table
   $active_query = '';
   if ($active) {
    // Only filter for active codes
    $active_query=" AND c.active = 1 ";
   }
   $sql_bind_array = array();
   $query = "SELECT c.id, c.code_text, c.code_text_short, c.code, c.code_type, c.modifier, c.units, c.fee, " .
            "c.superbill, c.related_code, c.taxrates, c.cyp_factor, c.active, c.reportable, c.financial_reporting, " .
            "'" . add_escape_custom($form_code_type) . "' as code_type_name " .
            "FROM `codes` as c ";
   if ($return_only_one) {
    $query .= "WHERE c.code = ? ";
    array_push($sql_bind_array,$search_term);
   }
   else if ($mode == "code") {
    $query .= "WHERE (c.code LIKE ?) ";
    array_push($sql_bind_array,$search_term."%");
   }
   else if ($mode == "description") {
    if (is_array($description_keywords)) {
     // Multiple description keywords
     $query .= "WHERE ( 1=1 ";
     foreach($description_keywords as $keyword) {
       $query .=" AND c.code_text LIKE ? ";
       array_push($sql_bind_array,"%".$keyword."%");
      }
     $query .= ") ";
    }
    else {
     // Only one description keyword
     $query .= "WHERE (c.code_text LIKE ?) ";
     array_push($sql_bind_array,"%".$description_keywords."%");
    }
   }
   else { // $mode == "default"
    $query .= "WHERE (c.code_text LIKE ? OR c.code LIKE ?) ";
    array_push($sql_bind_array,"%".$search_term."%", "%".$search_term."%");
   }
   $query .= "AND c.code_type = ? $active_query $query_filter_elements " .
             "ORDER BY c.code+0,c.code $limit_query";
   array_push($sql_bind_array,$code_types[$form_code_type]['id']);
   $res = sqlStatement($query,$sql_bind_array);
  }

  else if ($code_types[$form_code_type]['external'] == 1 ) { // Search from ICD10 diagnosis codeset tables
   $active_query = '';
   if ($active) {
    // Only filter for active codes
    // If there is no entry in codes sql table, then default to active
    //  (this is reason for including NULL below)
    $active_query=" AND (c.active = 1 || c.active IS NULL) ";
   }
   // Ensure the icd10_dx_order_code sql table exists
   $check_table = sqlQuery("SHOW TABLES LIKE 'icd10_dx_order_code'");
   if ( !(empty($check_table)) ) {
    $sql_bind_array = array();
    $query = "SELECT ref.formatted_dx_code as code, ref.long_desc as code_text, " .
             "c.id, c.code_type, c.modifier, c.units, c.fee, " .
             "c.superbill, c.related_code, c.taxrates, c.cyp_factor, c.active, c.reportable, c.financial_reporting, " .
             "'" . add_escape_custom($form_code_type) . "' as code_type_name " .
             "FROM `icd10_dx_order_code` as ref " .
             "LEFT OUTER JOIN `codes` as c " .
             "ON ref.formatted_dx_code = c.code AND c.code_type = ? ";
    array_push($sql_bind_array,$code_types[$form_code_type]['id']);
    if ($return_only_one) {
     $query .= "WHERE ref.formatted_dx_code = ? ";
     array_push($sql_bind_array,$search_term);
    }
    else if ($mode == "code") {
     $query .= "WHERE (ref.formatted_dx_code LIKE ?) ";
     array_push($sql_bind_array,$search_term."%");
    }
    else if ($mode == "description") {
     if (is_array($description_keywords)) {
      // Multiple description keywords
      $query .= "WHERE ( 1=1 ";
      foreach($description_keywords as $keyword) {
        $query .=" AND ref.long_desc LIKE ? ";
        array_push($sql_bind_array,"%".$keyword."%");
       }
      $query .= ") ";
     }
     else {
      // Only one description keyword
      $query .= "WHERE (ref.long_desc LIKE ?) ";
      array_push($sql_bind_array,"%".$description_keywords."%");
     }
    }
    else { // $mode == "default"
     $query .= "WHERE (ref.long_desc LIKE ? OR ref.formatted_dx_code LIKE ?) ";
     array_push($sql_bind_array,"%".$search_term."%","%".$search_term."%");
    }
    $query .= "AND ref.valid_for_coding = '1' AND ref.active = '1' $active_query $query_filter_elements ";
    $query .= "ORDER BY ref.formatted_dx_code+0, ref.formatted_dx_code $limit_query";
    $res = sqlStatement($query,$sql_bind_array);
   }
  }

  else if ($code_types[$form_code_type]['external'] == 2 || $code_types[$form_code_type]['external'] == 7) {
   // Search from SNOMED (RF1) diagnosis codeset tables OR Search from SNOMED (RF1) clinical terms codeset tables
   if ($code_types[$form_code_type]['external'] == 2) {
     // Search from SNOMED (RF1) diagnosis codeset tables
     $diagnosis_sql_specific = " ref.FullySpecifiedName LIKE '%(disorder)' ";
   }
   else {
     // Search from SNOMED (RF1) clinical terms codeset tables
     $diagnosis_sql_specific = " 1=1 ";
   }
   if ($active) {
    // Only filter for active codes
    // If there is no entry in codes sql table, then default to active
    //  (this is reason for including NULL below)
    $active_query=" AND (c.active = 1 || c.active IS NULL) ";
   }
   // Ensure the sct_concepts sql table exists
   $check_table = sqlQuery("SHOW TABLES LIKE 'sct_concepts'");
   if ( !(empty($check_table)) ) {
    $sql_bind_array = array();
    $query = "SELECT ref.ConceptId as code, ref.FullySpecifiedName as code_text, " .
             "c.id, c.code_type, c.modifier, c.units, c.fee, " .
             "c.superbill, c.related_code, c.taxrates, c.cyp_factor, c.active, c.reportable, c.financial_reporting, " .
             "'" . add_escape_custom($form_code_type) . "' as code_type_name " .
             "FROM `sct_concepts` as ref " .
             "LEFT OUTER JOIN `codes` as c " .
             "ON ref.ConceptId = c.code AND c.code_type = ? ";
    array_push($sql_bind_array,$code_types[$form_code_type]['id']);
    if ($return_only_one) {
     $query .= "WHERE (ref.ConceptId = ? ";
     array_push($sql_bind_array,$search_term);
    }
    else if ($mode == "code") {
     $query .= "WHERE (ref.ConceptId LIKE ? ";
     array_push($sql_bind_array,$search_term."%");
    }
    else if ($mode == "description") {
     if (is_array($description_keywords)) {
      // Multiple description keywords
      $query .= "WHERE (( 1=1 ";
      foreach($description_keywords as $keyword) {
        $query .=" AND ref.FullySpecifiedName LIKE ? ";
        array_push($sql_bind_array,"%".$keyword."%");
       }
      $query .= ") ";
     }
     else {
      // Only one description keyword
      $query .= "WHERE (ref.FullySpecifiedName LIKE ? ";
      array_push($sql_bind_array,"%".$description_keywords."%");
     }
    }
    else { // $mode == "default"
     $query .= "WHERE ((ref.FullySpecifiedName LIKE ? OR ref.ConceptId LIKE ?) ";
     array_push($sql_bind_array,"%".$search_term."%","%".$search_term."%");
    }
    $query .= "AND $diagnosis_sql_specific ) $active_query $query_filter_elements ";
    $query .= "AND ref.ConceptStatus = 0 " .
              "ORDER BY ref.ConceptId $limit_query";
    $res = sqlStatement($query,$sql_bind_array);
   }
  }

  else if ($code_types[$form_code_type]['external'] == 3 ) { // Search from SNOMED (RF2) diagnosis codeset tables
   //placeholder
  }

  else if ($code_types[$form_code_type]['external'] == 4 ) { // Search from ICD9 diagnosis codeset tables
   if ($active) {
    // Only filter for active codes
    // If there is no entry in codes sql table, then default to active
    //  (this is reason for including NULL below)
    $active_query=" AND (c.active = 1 || c.active IS NULL) ";
   }
   // Ensure the icd9_dx_code sql table exists
   $check_table = sqlQuery("SHOW TABLES LIKE 'icd9_dx_code'");
   if ( !(empty($check_table)) ) {
    $sql_bind_array = array();
    $query = "SELECT ref.formatted_dx_code as code, ref.long_desc as code_text, " .
             "c.id, c.code_type, c.modifier, c.units, c.fee, " .
             "c.superbill, c.related_code, c.taxrates, c.cyp_factor, c.active, c.reportable, c.financial_reporting, " .
             "'" . add_escape_custom($form_code_type) . "' as code_type_name " .
             "FROM `icd9_dx_code` as ref " .
             "LEFT OUTER JOIN `codes` as c " .
             "ON ref.formatted_dx_code = c.code AND c.code_type = ? ";
    array_push($sql_bind_array,$code_types[$form_code_type]['id']);
    if ($return_only_one) {
     $query .= "WHERE ref.formatted_dx_code = ? ";
     array_push($sql_bind_array,$search_term);
    }
    else if ($mode == "code") {
     $query .= "WHERE (ref.formatted_dx_code LIKE ?) ";
     array_push($sql_bind_array,$search_term."%");
    }
    else if ($mode == "description") {
     if (is_array($description_keywords)) {
      // Multiple description keywords
      $query .= "WHERE ( 1=1 ";
      foreach($description_keywords as $keyword) {
        $query .=" AND ref.long_desc LIKE ? ";
        array_push($sql_bind_array,"%".$keyword."%");
       }
      $query .= ") ";
     }
     else {
      // Only one description keyword
      $query .= "WHERE (ref.long_desc LIKE ?) ";
      array_push($sql_bind_array,"%".$description_keywords."%");
     }
    }
    else { // $mode == "default"
     $query .= "WHERE (ref.long_desc LIKE ? OR ref.formatted_dx_code LIKE ?) ";
     array_push($sql_bind_array,"%".$search_term."%","%".$search_term."%");
    }
    $query .= "AND ref.active = '1' $active_query $query_filter_elements ";
    $query .= "ORDER BY ref.formatted_dx_code+0, ref.formatted_dx_code $limit_query";
    $res = sqlStatement($query,$sql_bind_array);
   }
  }

  else if ($code_types[$form_code_type]['external'] == 5 ) { // Search from ICD9 Procedure/Service codeset tables
   if ($active) {
    // Only filter for active codes
    // If there is no entry in codes sql table, then default to active
    //  (this is reason for including NULL below)
    $active_query=" AND (c.active = 1 || c.active IS NULL) ";
   }
   // Ensure the icd9_sg_code sql table exists
   $check_table = sqlQuery("SHOW TABLES LIKE 'icd9_sg_code'");
   if ( !(empty($check_table)) ) {
    $sql_bind_array = array();
    $query = "SELECT ref.formatted_sg_code as code, ref.long_desc as code_text, " .
             "c.id, c.code_type, c.modifier, c.units, c.fee, " .
             "c.superbill, c.related_code, c.taxrates, c.cyp_factor, c.active, c.reportable, c.financial_reporting, " .
             "'" . add_escape_custom($form_code_type) . "' as code_type_name " .
             "FROM `icd9_sg_code` as ref " .
             "LEFT OUTER JOIN `codes` as c " .
             "ON ref.formatted_sg_code = c.code AND c.code_type = ? ";
    array_push($sql_bind_array,$code_types[$form_code_type]['id']);
    if ($return_only_one) {
     $query .= "WHERE ref.formatted_sg_code = ? ";
     array_push($sql_bind_array,$search_term);
    }
    else if ($mode == "code") {
     $query .= "WHERE (ref.formatted_sg_code LIKE ?) ";
     array_push($sql_bind_array,$search_term."%");
    }
    else if ($mode == "description") {
     if (is_array($description_keywords)) {
      // Multiple description keywords
      $query .= "WHERE ( 1=1 ";
      foreach($description_keywords as $keyword) {
        $query .=" AND ref.long_desc LIKE ? ";
        array_push($sql_bind_array,"%".$keyword."%");
       }
      $query .= ") ";
     }
     else {
      // Only one description keyword
      $query .= "WHERE (ref.long_desc LIKE ?) ";
      array_push($sql_bind_array,"%".$description_keywords."%");
     }
    }
    else { // $mode == "default"
     $query .= "WHERE (ref.long_desc LIKE ? OR ref.formatted_sg_code LIKE ?) ";
     array_push($sql_bind_array,"%".$search_term."%","%".$search_term."%");
    }
    $query .= "AND ref.active = '1' $active_query $query_filter_elements ";
    $query .= "ORDER BY ref.formatted_sg_code+0, ref.formatted_sg_code $limit_query";
    $res = sqlStatement($query,$sql_bind_array);
   }
  }

  else if ($code_types[$form_code_type]['external'] == 6 ) { // Search from ICD10 Procedure/Service codeset tables
   $active_query = '';
   if ($active) {
    // Only filter for active codes
    // If there is no entry in codes sql table, then default to active
    //  (this is reason for including NULL below)
    $active_query=" AND (c.active = 1 || c.active IS NULL) ";
   }
   // Ensure the icd10_dx_order_code sql table exists
   $check_table = sqlQuery("SHOW TABLES LIKE 'icd10_pcs_order_code'");
   if ( !(empty($check_table)) ) {
    $sql_bind_array = array();
    $query = "SELECT ref.pcs_code as code, ref.long_desc as code_text, " .
             "c.id, c.code_type, c.modifier, c.units, c.fee, " .
             "c.superbill, c.related_code, c.taxrates, c.cyp_factor, c.active, c.reportable, c.financial_reporting, " .
             "'" . add_escape_custom($form_code_type) . "' as code_type_name " .
             "FROM `icd10_pcs_order_code` as ref " .
             "LEFT OUTER JOIN `codes` as c " .
             "ON ref.pcs_code = c.code AND c.code_type = ? ";
    array_push($sql_bind_array,$code_types[$form_code_type]['id']);
    if ($return_only_one) {
     $query .= "WHERE ref.pcs_code = ? ";
     array_push($sql_bind_array,$search_term);
    }
    else if ($mode == "code") {
     $query .= "WHERE (ref.pcs_code LIKE ?) ";
     array_push($sql_bind_array,$search_term."%");
    }
    else if ($mode == "description") {
     if (is_array($description_keywords)) {
      // Multiple description keywords
      $query .= "WHERE ( 1=1 ";
      foreach($description_keywords as $keyword) {
        $query .=" AND ref.long_desc LIKE ? ";
        array_push($sql_bind_array,"%".$keyword."%");
       }
      $query .= ") ";
     }
     else {
      // Only one description keyword
      $query .= "WHERE (ref.long_desc LIKE ?) ";
      array_push($sql_bind_array,"%".$description_keywords."%");
     }
    }
    else { // $mode == "default"
     $query .= "WHERE (ref.long_desc LIKE ? OR ref.pcs_code LIKE ?) ";
     array_push($sql_bind_array,"%".$search_term."%","%".$search_term."%");
    }
    $query .= "AND ref.valid_for_coding = '1' AND ref.active = '1' $active_query $query_filter_elements ";
    $query .= "ORDER BY ref.pcs_code+0, ref.pcs_code $limit_query";
    $res = sqlStatement($query,$sql_bind_array);
   }
  }

  else {
   //using an external code that is not yet supported, so skip.
  }
  if (isset($res)) {
   if ($count) {
    // just return the count
    return sqlNumRows($res);
   }
   else {
    // return the data
    return $res;
   }
  }
}

/**
 * Lookup Code Descriptions for one or more billing codes.
 *
 * Function is able to lookup code descriptions from a variety of code sets. See the 'external'
 * items in the comments at top of this page for a listing of the code sets supported.
 *
 * @param  string $codes  Is of the form "type:code;type:code; etc.".
 * @return string         Is of the form "description;description; etc.".
 */
function lookup_code_descriptions($codes) {
  global $code_types;
  $code_text = '';
  if (!empty($codes)) {
    $relcodes = explode(';', $codes);
    foreach ($relcodes as $codestring) {
      if ($codestring === '') continue;
      list($codetype, $code) = explode(':', $codestring);
      if ( !($code_types[$codetype]['external']) ) { // Collect from default codes table
        $wheretype = "";
        $sqlArray = array();
        if (empty($code)) {
          $code = $codetype;
        } else {
          $wheretype = "code_type = ? AND ";
          array_push($sqlArray,$code_types[$codetype]['id']);
        }
        $sql = "SELECT code_text FROM codes WHERE " .
          "$wheretype code = ? ORDER BY id LIMIT 1";
        array_push($sqlArray,$code);
        $crow = sqlQuery($sql,$sqlArray);
        if (!empty($crow['code_text'])) {
          if ($code_text) $code_text .= '; ';
          $code_text .= $crow['code_text'];
        }
      }
      else if ($code_types[$codetype]['external'] == 1) { // Collect from ICD10 Diagnosis codeset tables
        // Ensure the icd10_dx_order_code sql table exists
        $check_table = sqlQuery("SHOW TABLES LIKE 'icd10_dx_order_code'");
        if ( !(empty($check_table)) ) {
          if ( !(empty($code)) ) {
            // Will grab from previous inactive revisions if unable to find in current revision
            $sql = "SELECT `long_desc` FROM `icd10_dx_order_code` " .
                   "WHERE `formatted_dx_code` = ? ORDER BY `revision` DESC LIMIT 1";
            $crow = sqlQuery($sql, array($code) );
            if (!empty($crow['long_desc'])) {
              if ($code_text) $code_text .= '; ';
              $code_text .= $crow['long_desc'];
            }
          }
        }
      }
      else if ($code_types[$codetype]['external'] == 2 || $code_types[$codetype]['external'] == 7) {
        // Collect from SNOMED (RF1) Diagnosis codeset tables OR Search from SNOMED (RF1) clinical terms codeset tables 
        // Ensure the sct_concepts sql table exists
        $check_table = sqlQuery("SHOW TABLES LIKE 'sct_concepts'");
        if ( !(empty($check_table)) ) {
          if ( !(empty($code)) ) {
            $sql = "SELECT `FullySpecifiedName` FROM `sct_concepts` " .
                   "WHERE `ConceptId` = ? AND `ConceptStatus` = 0 LIMIT 1";
            $crow = sqlQuery($sql, array($code) );
            if (!empty($crow['FullySpecifiedName'])) {
              if ($code_text) $code_text .= '; ';
              $code_text .= $crow['FullySpecifiedName'];
            }
          }
        }
      }
      else if ($code_types[$codetype]['external'] == 3) { // Collect from SNOMED (RF2) Diagnosis codeset tables
        //placeholder
      }
      else if ($code_types[$codetype]['external'] == 4) { // Collect from ICD9 Diagnosis codeset tables
        // Ensure the icd9_dx_code sql table exists
        $check_table = sqlQuery("SHOW TABLES LIKE 'icd9_dx_code'");
        if ( !(empty($check_table)) ) {
          if ( !(empty($code)) ) {
            // Will grab from previous inactive revisions if unable to find in current revision
            $sql = "SELECT `long_desc` FROM `icd9_dx_code` " .
                   "WHERE `formatted_dx_code` = ? ORDER BY `revision` DESC LIMIT 1";
            $crow = sqlQuery($sql, array($code) );
            if (!empty($crow['long_desc'])) {
              if ($code_text) $code_text .= '; ';
              $code_text .= $crow['long_desc'];
            }
          }
        }
      }
      else if ($code_types[$codetype]['external'] == 5) { // Collect from ICD9 Procedure/Service codeset tables
        // Ensure the icd9_dx_code sql table exists
        $check_table = sqlQuery("SHOW TABLES LIKE 'icd9_sg_code'");
        if ( !(empty($check_table)) ) {
          if ( !(empty($code)) ) {
            // Will grab from previous inactive revisions if unable to find in current revision
            $sql = "SELECT `long_desc` FROM `icd9_sg_code` " .
                   "WHERE `formatted_sg_code` = ? ORDER BY `revision` DESC LIMIT 1";
            $crow = sqlQuery($sql, array($code) );
            if (!empty($crow['long_desc'])) {
              if ($code_text) $code_text .= '; ';
              $code_text .= $crow['long_desc'];
            }
          }
        }
      }
      else if ($code_types[$codetype]['external'] == 6) { // Collect from ICD10 PRocedure/Service codeset tables
        // Ensure the icd10_dx_order_code sql table exists
        $check_table = sqlQuery("SHOW TABLES LIKE 'icd10_pcs_order_code'");
        if ( !(empty($check_table)) ) {
          if ( !(empty($code)) ) {
            // Will grab from previous inactive revisions if unable to find in current revision
            $sql = "SELECT `long_desc` FROM `icd10_pcs_order_code` " .
                   "WHERE `pcs_code` = ? ORDER BY `revision` DESC LIMIT 1";
            $crow = sqlQuery($sql, array($code) );
            if (!empty($crow['long_desc'])) {
              if ($code_text) $code_text .= '; ';
              $code_text .= $crow['long_desc'];
            }
          }
        }
      }

      else {
        //using an external code that is not yet supported, so skip. 
      }
    }
  }
  return $code_text;
}

/**
 * Sequential code set searching function (algorithm contributed by yehster)
 *
 * Function is basically a wrapper of the code_set_search() function to support
 * an optimized searching model. Model searches codes first; then if no hits, it
 * will then search the descriptions (which are separated by each word in the
 * code_set_search() function).
 *
 * @param  string    $form_code_type  code set key (special keywords are PROD and --ALL--)
 * @param  string    $search_term     search term
 * @param  array     $limit           Number of results to return (NULL means return all)
 * @return recordset
 */
function sequential_code_set_search($form_code_type,$search_term,$limit=NULL) {
  // Search the code first
  $res_code = code_set_search($form_code_type,$search_term,false,true,false,NULL,NULL,array(),$limit,'code');
  if(sqlNumRows($res_code)>0) {
   return $res_code;        
  }
  else {
   // no codes found, so search the descriptions;
   $res_desc = code_set_search($form_code_type,$search_term,false,true,false,NULL,NULL,array(),$limit,'description');
   if(sqlNumRows($res_desc)>0) {
    return $res_desc;        
   }        
  }
}
?>
