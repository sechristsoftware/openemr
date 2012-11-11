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

require_once("../../interface/globals.php");
require_once "$srcdir/rxnorm.inc.php";
require_once "$srcdir/formdata.inc.php";

// This is only pertinent for users of php versions less than 5.2
//  (ie. this wrapper is only loaded when php version is less than
//   5.2; otherwise the native php json functions are used)
require_once "$srcdir/jsonwrapper/jsonwrapper.php";

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

// Collect results
$arr = rxcui_resolve_rxnorm($rxcui,$term_type,$drug_name);

// Debugging
error_log(print_r($arr,TRUE),0);

// Return results
echo json_encode($arr);

?>
