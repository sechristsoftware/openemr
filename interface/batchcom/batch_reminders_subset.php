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
<?php
$patientCount="SELECT count(`pid`) as count FROM `patient_data`";
$qry=sqlQuery($patientCount);
$numPatients=$qry['count'];
?>

<script src="../../library/js/jquery-1.6.4.min.js" type="text/javascript"></script>
<script type="text/javascript">
var total=<?php echo $numPatients;?>;
var batchStart=0;
var batchSize=10;

function nextBatch()
{

    if(batchStart<total)
    {
        processBatch(batchStart,batchSize);
        batchStart+=batchSize;
    }
    else
    {
        $("#progress").html("Finished:"+total+"<br>All done!");
                
    }
}
function finishBatch(data)
{
    numFinished = (batchStart > total) ? total : batchStart;
    $("#progress").html("Finished:"+numFinished);
    $("#counts").html(data);
    nextBatch();
    if(batchStart>=total)
    {
    $.post("batch_reminders_send_ajax.php",
    {
    },
    function(data)
    {
        $("#sendStatus").html(data);
    }
    );
        
    }

}

function processBatch(start,size)
{
    $.post("batch_reminders_subset_ajax.php",
    {
        start: start,
        batchSize: size
    },
    finishBatch
    );
}

window.onload=nextBatch;

window.onbeforeunload=function()
{
    // if there are still ajax calls running, confirm before closing window.
    if($.active)
        {
            return false;
        }
}
</script>
<html>
<head>
<?php html_header_show();?>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<link rel="stylesheet" href="batchcom.css" type="text/css">
</head>
<body class="body_top">
<span class="title"><?php echo htmlspecialchars(xl('Patient Reminder Batch Job'), ENT_NOQUOTES)?></span>

<div id="summary">Total Patients:<?php echo $numPatients;?></div>
<div id="progress"></div>
<div id="counts"></div>
<div id="sendStatus"></div>
</body>
</html>

