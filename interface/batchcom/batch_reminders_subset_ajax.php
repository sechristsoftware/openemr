<?php
// +-----------------------------------------------------------------------------+
// Copyright (C) 2011 IntegralEMR LLC <kevin.y@integralemr.com>
//
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
//
// A copy of the GNU General Public License is included along with this program:
// openemr/interface/login/GnuGPL.html
// For more information write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//
// Author:   Kevin Yeh <kevin.y@integralemr.com>
//
// +------------------------------------------------------------------------------+
//SANITIZE ALL ESCAPES
$sanitize_all_escapes=true;

//STOP FAKE REGISTER GLOBALS
$fake_register_globals=false;

require_once(dirname(__FILE__)."/../../interface/globals.php");
require_once ($GLOBALS['srcdir'] . "/classes/postmaster.php");
require_once ($GLOBALS['srcdir'] . "/maviq_phone_api.php");
require_once($GLOBALS['srcdir'] . "/reminders.php");

if(!isset($_REQUEST['start']) || !isset($_REQUEST['batchSize']))
{
    header("HTTP/1.0 403 Forbidden");
    echo "Incomplete parameters";
    return;
}

$start=intval($_REQUEST['start']);
$batchSize=intval($_REQUEST['batchSize']);

if($start==0)
{
    $_SESSION['batchResults']=array();
}

$currentLogResults=update_reminders('','',$start,$batchSize);
$keys=array_keys($currentLogResults);
foreach($keys as $key)
{
/*    if(!isset($_SESSION['batchResults'][$key]))
    {
        $_SESSION['batchResults'][$key]=$currentLogResults[$key];
    }
    else 
    {
*/
{        $_SESSION['batchResults'][$key]+=$currentLogResults[$key];
    }
}

$update_rem_log=$_SESSION['batchResults'];
?>

    <span class="text"><?php echo htmlspecialchars(xl('The patient reminders have been updated'), ENT_NOQUOTES) . ":"?></span><br>
      <span class="text"><?php echo htmlspecialchars(xl('Total active actions'), ENT_NOQUOTES) . ": " . $update_rem_log['total_active_actions'];?></span><br>
      <span class="text"><?php echo htmlspecialchars(xl('Total active reminders before update'), ENT_NOQUOTES) . ": " . $update_rem_log['total_pre_active_reminders'];?></span><br>
      <span class="text"><?php echo htmlspecialchars(xl('Total unsent reminders before update'), ENT_NOQUOTES) . ": " . $update_rem_log['total_pre_unsent_reminders'];?></span><br>
      <span class="text"><?php echo htmlspecialchars(xl('Total active reminders after update'), ENT_NOQUOTES) . ": " . $update_rem_log['total_post_active_reminders'];?></span><br>
      <span class="text"><?php echo htmlspecialchars(xl('Total unsent reminders after update'), ENT_NOQUOTES) . ": " . $update_rem_log['total_post_unsent_reminders'];?></span><br>
      <span class="text"><?php echo htmlspecialchars(xl('Total new reminders'), ENT_NOQUOTES) . ": " . $update_rem_log['number_new_reminders'];?></span><br>
      <span class="text"><?php echo htmlspecialchars(xl('Total updated reminders'), ENT_NOQUOTES) . ": " . $update_rem_log['number_updated_reminders'];?></span><br>
      <span class="text"><?php echo htmlspecialchars(xl('Total inactivated reminders'), ENT_NOQUOTES) . ": " . $update_rem_log['number_inactivated_reminders'];?></span><br>
      <span class="text"><?php echo htmlspecialchars(xl('Total unchanged reminders'), ENT_NOQUOTES) . ": " . $update_rem_log['number_unchanged_reminders'];?></span><br>
