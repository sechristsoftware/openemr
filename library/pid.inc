<?php
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

require_once("$srcdir/../interface/globals.php");
require_once("$srcdir/log.inc");

// Function called to set the global session variable for patient id (pid) number.
function setpid($new_pid) {
  global $pid, $encounter;

  // Ensure $new_pid is 0 or is an existing patient pid
  //  (this will prevent sql-injection)
  if ($new_pid != "0") {
    $result = sqlQuery("SELECT pid FROM patient_data WHERE pid=?",array($new_pid));
    if ($result['pid']) {
      $new_pid = $result['pid'];
    }
    else {
      error_log("Critical OpenEMR Error: Attempt to set pid that does not exist.",0);
      error_log("Requested pid: ".$new_pid,0);
      error_log("Returned pid: 0",0);
      $new_pid = "0";
    }
  }

  // Be careful not to clear the encounter unless the pid is really changing.
  if (!isset($_SESSION['pid']) || $pid != $new_pid || $pid != $_SESSION['pid']) {
    $_SESSION['encounter'] = $encounter = 0;
  }

  $_SESSION['pid'] = $new_pid;
  $pid = $new_pid;

  newEvent("view", $_SESSION["authUser"], $_SESSION["authProvider"], 1, $pid);
}
?>
