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
?>

 <?php $send_rem_log = send_reminders(); ?>

    <br><span class="text"><?php echo htmlspecialchars(xl('The patient reminders have been sent'), ENT_NOQUOTES) . ":"?></span><br>
      <span class="text"><?php echo htmlspecialchars(xl('Total unsent reminders before sending process'), ENT_NOQUOTES) . ": " . $send_rem_log['total_pre_unsent_reminders'];?></span><br>
      <span class="text"><?php echo htmlspecialchars(xl('Total unsent reminders after sending process'), ENT_NOQUOTES) . ": " . $send_rem_log['total_post_unsent_reminders'];?></span><br>
      <span class="text"><?php echo htmlspecialchars(xl('Total successful reminders sent via email'), ENT_NOQUOTES) . ": " . $send_rem_log['number_success_emails'];?></span><br>
      <span class="text"><?php echo htmlspecialchars(xl('Total failed reminders sent via email'), ENT_NOQUOTES) . ": " . $send_rem_log['number_failed_emails'];?></span><br>
      <span class="text"><?php echo htmlspecialchars(xl('Total successful reminders sent via phone'), ENT_NOQUOTES) . ": " . $send_rem_log['number_success_calls'];?></span><br>
      <span class="text"><?php echo htmlspecialchars(xl('Total failed reminders sent via phone'), ENT_NOQUOTES) . ": " . $send_rem_log['number_unchanged_reminders'];?></span><br>

    <br><span class="text"><?php echo htmlspecialchars(xl('(Email delivery is immediate, while automated VOIP is sent to the service provider for further processing.)'), ENT_NOQUOTES)?></span><br>
