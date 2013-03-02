<?php
/**
 * Copyright (C) 2009 Rod Roark <rod@sunsetsystems.com>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * These functions should be used to globally validate and prepare
 * data for sql database insertion.
 *
 */

/**(Note this function is deprecated and no longer used for new scripts)
 * Main function that will manage POST, GET, and REQUEST variables 
 * @param string $name name of the variable requested.
 * @param string $type 'P', 'G' for post or get data, otherwise uses request.
 * @param bool $istrim whether to use trim() on the data.
 * @return string variable requested, or empty string
 */
function formData($name, $type='P', $isTrim=false) {
  if ($type == 'P')
    $s = isset($_POST[$name]) ? $_POST[$name] : '';
  else if ($type == 'G')
    $s = isset($_GET[$name]) ? $_GET[$name] : '';
  else
    $s = isset($_REQUEST[$name]) ? $_REQUEST[$name] : '';
  
  return formDataCore($s,$isTrim);
}

//(Note this function is deprecated and no longer used for new scripts)
// Core function that will be called by formData.
// Note it can also be called directly if preparing
// normal variables (not GET,POST, or REQUEST)
function formDataCore($s, $isTrim=false) {
      //trim if selected
      if ($isTrim) {$s = trim($s);}
      //strip escapes
      $s = strip_escape_custom($s);
      //add escapes for safe database insertion
      $s = add_escape_custom($s);
      return $s;
}

//(Note this function is deprecated and no longer used for new scripts)
// Will remove escapes if needed (ie magic quotes turned on) from string
// Called by above formDataCore() function to prepare for database insertion.
// Can also be called directly if simply need to remove escaped characters
//  from a string before processing.
function strip_escape_custom($s) {
      //strip slashes if magic quotes turned on
      if (get_magic_quotes_gpc()) {$s = stripslashes($s);}
      return $s;
}

// Will add escapes as needed onto a string
// Called by above formDataCore() function to prepare for database insertion.
// Can also be called directly if need to escape an already process string (ie.
//  escapes were already removed, then processed, and now want to insert into
//  database)
function add_escape_custom($s) {
      //prepare for safe mysql insertion
      $s = mysql_real_escape_string($s);
      return $s;
}

// Will escape integers within the LIMIT ?, ? part of a sql query.
// Note that there is a maximum value to these numbers, which is why
// should only use for the LIMIT ? , ? part of the sql query and why
// this is centralized to a function (in case need to upgrade this
// function to support larger numbers in the future).
function escape_limit($s) {
      //prepare for safe mysql insertion
      $s = (int)$s;
      return $s;
}

// Will escape sort order string.
// Done by whitelisting only certain keywords.
// If the keyword is illegal, then will default to ASC.
function escape_sort_order($s) {
      $ok = array("asc","desc");
      $key = array_search(strtolower($s),$ok);
      return $ok[$key];
}

// Will escape sort order string.
// Done by whitelisting only certain identifiers($whitelist is an array of acceptable identifiers).
// If the keyword is illegal, then will default to the first item in $whitelist array.
function escape_identifier($s,$whitelist) {
      $ok = $whitelist;
      $key = array_search($s,$ok);
      return $ok[$key];
}

// This function is only being kept to support
// previous functionality. If you want to trim
// variables, this should be done using above
// functions.
function formTrim($s) {
  return formDataCore($s,true);
}
?>
