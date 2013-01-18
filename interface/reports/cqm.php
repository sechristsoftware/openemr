<?php
 // Copyright (C) 2010 Brady Miller <brady@sparmy.com>
 //
 // This program is free software; you can redistribute it and/or
 // modify it under the terms of the GNU General Public License
 // as published by the Free Software Foundation; either version 2
 // of the License, or (at your option) any later version.

//SANITIZE ALL ESCAPES
$sanitize_all_escapes=true;
//


//STOP FAKE REGISTER GLOBALS
$fake_register_globals=false;
//

require_once("../globals.php");
require_once("../../library/patient.inc");
require_once("$srcdir/formatting.inc.php");
require_once "$srcdir/options.inc.php";
require_once "$srcdir/formdata.inc.php";
require_once "$srcdir/clinical_rules.php";
include_once("../emr4.php");




// Collect report type parameter (standard, amc, or cqm)
$type_report = (isset($_GET['type'])) ? trim($_GET['type']) : "standard";

// Collect form parameters (set defaults if empty)
if ($type_report == "amc") {
  $begin_date = (isset($_POST['form_begin_date'])) ? trim($_POST['form_begin_date']) : "";
  $labs_manual = (isset($_POST['labs_manual_entry'])) ? trim($_POST['labs_manual_entry']) : "0";
}
$target_date = (isset($_POST['form_target_date'])) ? trim($_POST['form_target_date']) : date('Y-m-d H:i:s');
$rule_filter = (isset($_POST['form_rule_filter'])) ? trim($_POST['form_rule_filter']) : "";
$plan_filter = (isset($_POST['form_plan_filter'])) ? trim($_POST['form_plan_filter']) : "";
$organize_method = (empty($plan_filter)) ? "default" : "plans";
$provider  = trim($_POST['form_provider']);




//RUN AN AUDIT REPORT SHOWING THE DATA AND ANY ERRORS BEHIND THE REPORT

if ($provider!='' && $begin_date!='' && $target_date!=''){

echo "<a href=#report>Report</a><p>Provider ID: " . $provider . "<p> ";

$dbhost = 'localhost';
$dbuser = 'openemr';
$dbpass = 'sierra17';
$mydb=$mydbis;
$connx = mysql_connect($dbhost, $dbuser, $dbpass) or die                      ('Error connecting to mysql');

$connection=$connx;

$db_selected = mysql_select_db("$mydb",$connx);


 
 
 



//$sql= "delete from soaptemplatez where idnumber=" . $_SESSION['idis'] . "";

$sql ="select * from patient_data order by id desc";
//$sql="SELECT (select count(id) from lists where form_vitals.pid=form_encounter.pid) as `Lists`,(select count(id) from form_vitals where form_vitals.pid=form_encounter.pid) as `Vitals`,patient_data.fname,patient_data.lname,form_vitals.BPS,form_encounter.date as `Encounter`,form_encounter.provider_id from form_encounter join lists on lists.pid=form_encounter.id join patient_data on patient_data.pid=form_encounter.pid  join form_vitals on patient_data.pid=form_vitals.pid where provider_id=" . $provider . " order by form_encounter.id desc";// where encounter=" . "$encounter" . " and pid=" . "$thisid";
//$sql="SELECT (select count(id) from lists where lists.pid=form_encounter.pid) as `Lists`,(select count(id) from form_vitals where form_vitals.pid=form_encounter.pid) as `Vitals`,patient_data.fname,patient_data.lname,form_vitals.BPS,form_encounter.date as `Encounter`,form_encounter.provider_id from form_encounter join lists on lists.pid=form_encounter.id join patient_data on patient_data.pid=form_encounter.pid  join form_vitals on patient_data.pid=form_vitals.pid where provider_id=" . $provider . " order by form_encounter.id desc";// where encounter=" . "$encounter" . " and pid=" . "$thisid";
//$sql="SELECT (select count(id) from lists) as `Lists`),(select count(id) from form_vitals) as `Vitals`,patient_data.fname,patient_data.lname,form_vitals.BPS,form_encounter.date as `Encounter`,form_encounter.provider_id from form_encounter join lists on lists.pid=form_encounter.id join patient_data on patient_data.pid=form_encounter.pid  join form_vitals on patient_data.pid=form_vitals.pid where provider_id=" . $provider . " order by form_encounter.id desc";// where encounter=" . "$encounter" . " and pid=" . "$thisid";
$sql="select patient_data.fname,patient_data.lname,patient_data.DOB,patient_data.sex,patient_data.language,patient_data.race,patient_data.ethnicity,form_vitals.BPS,form_vitals.BPD,form_vitals.Weight,form_vitals.Height,form_encounter.date as `Encounter`,form_encounter.provider_id,form_encounter.pid from form_encounter  join patient_data on patient_data.pid=form_encounter.pid  join form_vitals on patient_data.pid=form_vitals.pid where form_encounter.date>='" . $begin_date . "'" . " and form_encounter.date<='" . $target_date . "'" . " and provider_id=" . $provider . " order by form_encounter.pid desc";// where encounter=" . "$encounter" . " and pid=" . "$thisid";
//  where pid=" . "$thisid" . " order by `id` desc";

echo "<p><table border=1>";

$resultx = mysql_query($sql,$connx);

if (!$resultx)
 die (mysql_error());
  {
  //print ('<p>1 Could not delete: ' . mysql_error());
  }
$c=0;




   while ($zrow = mysql_fetch_assoc($resultx)) {
   
    $alldates[$zrow['Encounter']]=($alldates[$zrow['Encounter']]-0)+1;
	

	
			
            print "<tr>";
			if($c==-1){

            while(list($var, $val) = each($zrow)) {
                //print "<B>$var</B>: $val<br />";
				print "<td bgcolor=silver> " . strtoupper($var) . "";
            }
			print "<tr>";
			$c=1;
			};
			
            while(list($var, $val) = each($zrow)) {
                print "<td bgcolor=silver><B>$var</B>: $val<br />";
				//print "<td bgcolor=silver> $val";
            }
    
            print "<tr />";


//DOB: 1919-03-08
//sex: Female
//language: English
//race: white
//ethnicity: 

if($zrow['ethnicity']==''){

			print "<tr><td bgcolor=red colspan=1>No Ethnicity ";
			//$noallergy=$noallergy . $zrow['fname'] . " " . $zrow['lname'] . ", ";

};
if($zrow['race']==''){

			print "<tr><td bgcolor=red colspan=1>No Race " ;
			//$noallergy=$noallergy . $zrow['fname'] . " " . $zrow['lname'] . ", ";

};			

if($zrow['sex']==''){

			print "<tr><td bgcolor=red colspan=1>No sex " ;
			//$noallergy=$noallergy . $zrow['fname'] . " " . $zrow['lname'] . ", ";

};
			
			


			$sql="select Date,Type,Title from lists where pid=" . $zrow['pid'] . " order by Date";
			$resultxz = mysql_query($sql,$connx);
			
			$meds=0;
			$dxs=0;
			$allergy=0;
			
if (!$resultxz)
 die (mysql_error());
  {
  
		while ($qzrow = mysql_fetch_assoc($resultxz)) {
		print "<tr>";
            while(list($var, $val) = each($qzrow)) {
                print "<td><B>$var</B>: $val<br />";
				//print "<td> $val";
				if (stripos($val,"medication")>-1){
				$meds=$meds+1;
				};
				if (stripos($val,"problem")>-1){
				$dxs=$dxs+1;
				};
				
				if (stripos($val,"aller")>-1){
				//$allergy=$allergy+1;
				};				
            }
		};
  
  }			
			
			
		$patientId[$zrow['pid']]=($patientId[$zrow['pid']]-0)+1;

		if($meds==0){
			print "<tr><td bgcolor=red colspan=1>No medications";
			$nomeds=$nomeds . $zrow['fname'] . " " . $zrow['lname'] . ", ";
		};	
		if($meds!=0){$allmeds=$allmeds+1;};
		if($dxs!=0){$alldxs=$alldxs+1;};
		if($allergy!=0){$allallergy=$allallergy+1;};

		if($allergy==0){
			//print "<tr><td bgcolor=red colspan=1>No allergy list";
			//$noallergy=$noallergy . $zrow['fname'] . " " . $zrow['lname'] . ", ";
		};	

		
		if($dxs==0){
			print "<tr><td bgcolor=red colspan=1>No problem list";
			$noprobs=$noprobs . $zrow['fname'] . " " . $zrow['lname'] . ", ";
		};	
			
			
			$sql="select tobacco from history_data where pid=" . $zrow['pid'] . "";
			$resultxz = mysql_query($sql,$connx);
$t=false;
			if (!$resultxz)
 die (mysql_error());
  {
  
		while ($qzrow = mysql_fetch_assoc($resultxz)) {
		print "<tr>";
            while(list($var, $val) = each($qzrow)) {
                print "<td><B>$var</B>: $val<br />";
				$t=true;
				//print "<td> $val";
				$tobacco[$val]=($tobacco[$val]-0)+1;
				if($val=='|0||'){
				//$notobacco=$notobacco . $zrow['fname'] . " " . $zrow['lname'] . ", ";
				};
            }
		};
  
  }			
		if($t==false){
		print "<tr><td bgcolor=red colspan=1>No Tobacco Reminder";
		$notobacco=$notobacco . $zrow['fname'] . " " . $zrow['lname'] . ", ";
		};	
			

  



			$sql="select * from lists_touch where pid=" . $zrow['pid'] . "";
			$resultxz = mysql_query($sql,$connx);
$t=false;
			if (!$resultxz)
 die (mysql_error());
  {
  
		while ($qzrow = mysql_fetch_assoc($resultxz)) {
		print "<tr>";
            while(list($var, $val) = each($qzrow)) {
                print "<td><B>$var</B>: $val<br />";
				$t=true;
				//print "<td> $val";
				//$tobacco[$val]=($tobacco[$val]-0)+1;
				if($val=='|0||'){
				//$notobacco=$notobacco . $zrow['fname'] . " " . $zrow['lname'] . ", ";
				};
				
				if (stripos($val,"allergy")>-1){
				$allergy=$allergy+1;
				};
				
            }
		};
  
  }			
		if($t==false){
		//print "<tr><td bgcolor=red colspan=1>No Tobacco Reminder";
		//$notobacco=$notobacco . $zrow['fname'] . " " . $zrow['lname'] . ", ";
		};	
			
		if($allergy==0){
			print "<tr><td bgcolor=red colspan=1>No allergy list";
			$noallergy=$noallergy . $zrow['fname'] . " " . $zrow['lname'] . ", ";
		};	
  
            ++$rowcount;
			
        }
  


  
  
  
  print "</table>";
  
  print "<p>No of encounters: " . $rowcount;
  
echo "<p>Tobacco Clinical Reminder<br><Table border=1><tr><td><td>  ";
   while (list($key,$value) = each($tobacco)) {
if($key!=''){echo "<tr><td>$key <td> $value<br>"; $tobac=$tobac+($value-0);};
}
  print "</table><p>Summary<p><table border=1><tr><td valign=right>No Tobacco Reminder: <td valign=right>" . $notobacco; //. "<P>Tobacco Answered: " . $tobac;
  //print "<tr><td valign=right>Tobacco Reminder Completed:<td valign=right> " . $tobac . "/" . $rowcount . "<td valign=right>" . number_format(100*($tobac/$rowcount)) . "%";
  //print "<tr><td valign=right>With Medications: <td valign=right>" . $allmeds . "/" . $rowcount . "  <td valign=right>" . number_format(($allmeds/$rowcount)*100) . "%";
 // print "<tr><td valign=right>No Medications:<td valign=right> " . $nomeds;
  //print "<tr><td valign=right>With Problem List:<td valign=right> " . $alldxs  . "/" . $rowcount . " <td valign=right>" . number_format(($alldxs/$rowcount)*100) . "%";
  print "<tr><td valign=right>No Medical Problems List:<td valign=right> " . $noprobs;
  print "<tr><td valign=right>No Allergy List:<td valign=right> " . $noallergy;
  print "</table>";
  
  while (list($key,$value) = each($patientId)) {
  $totalpats=$totalpats+1;
  
  };
  
   while (list($key,$value) = each($alldates)) {
	if($key!=''){echo "<p>$key = $value<br>";};
   }
  
Print "<a name=report></a>  <table border=1>
<tr><td bgcolor=silver>Title	 <td bgcolor=silver>Total Patients	 <td bgcolor=silver>Denominator	 <td bgcolor=silver>Numerator	 <td bgcolor=silver>Performance Percentage
<tr><td>Use CPOE for medication orders directly entered by any licensed healthcare professional who can enter orders into the medical record per state, local and professional guidelines. ( AMC:170.304(a) )	<td>" . $rowcount . "	<td>" . $totalpats . "	<td>0	<td>0%
<tr><td>Generate and transmit permissible prescriptions electronically. ( AMC:170.304(b) )	<td>" . $rowcount . "	<td>" . $totalpats . "	<td>0	<td>0%
<tr><td>Incorporate clinical lab-test results into certified EHR technology as structured data. ( AMC:170.302(h) )	<td>" . $rowcount . "	<td>" . $totalpats . "	<td>0	<td>0%
<tr><td>Maintain active medication allergy list. ( AMC:170.302(e) )	<td>" . $rowcount . "	<td>" . $totalpats . "	<td>" . $allallergy . "	<td>" . number_format(($allallergy/$rowcount)*100) . "%
<tr><td>Maintain active medication list. ( AMC:170.302(d) )	<td>" . $rowcount . "	<td>" . $totalpats . "	<td>" . $allmeds . "	<td>" . number_format(($allmeds/$rowcount)*100) . "%
<tr><td>The EP, eligible hospital or CAH who receives a patient from another setting of care or provider of care or believes an encounter is relevant should perform medication reconciliation. ( AMC:170.302(j) )	<td>" . $rowcount . "	<td>" . $totalpats . "	<td>0	<td>0%
<tr><td>Use certified EHR technology to identify patient-specific education resources and provide those resources to the patient if appropriate. ( AMC:170.302(m) )	<td>" . $rowcount . "	<td>" . $totalpats . "	<td>0	<td>0%
<tr><td>Maintain an up-to-date problem list of current and active diagnoses. ( AMC:170.302(c) )	<td>" . $rowcount . "	<td>"  . $totalpats . "	<td>" . $alldxs  . "	<td>" . number_format(($alldxs/$rowcount)*100) . "%
<tr><td>Provide patients with an electronic copy of their health information (including diagnostic test results, problem list, medication lists, medication allergies), upon request. ( AMC:170.304(f) )	<td>" . $rowcount . "	<td>" . $totalpats . "	<td>0	<td>0%
<tr><td>Provide clinical summaries for patients for each office visit. ( AMC:170.304(h) )	<td>" . $rowcount . "	<td>" . $totalpats . "	<td>0	<td>0%
<tr><td>Record demographics. ( AMC:170.304(c) )	<td>" . $rowcount . "	<td>" . $totalpats . "	<td>0	<td>0%
<tr><td>Record smoking status for patients 13 years old or older. ( AMC:170.302(g) )	<td>" . $rowcount . "	<td>" . $totalpats . "	<td>" . $tobac . "	<td>" . number_format(100*($tobac/$rowcount)) . "%
<tr><td>Record and chart changes in vital signs. ( AMC:170.302(f) )	<td>" . $rowcount . "	<td>" . $totalpats . "	<td>0	<td>0%
<tr><td>Send reminders to patients per patient preference for preventive/follow up care. ( AMC:170.304(d) )	<td>" . $rowcount . "	<td>" . $totalpats . "	<td>0	<td>0%
<tr><td>The EP, eligible hospital or CAH who transitions their patient to another setting of care or provider of care or refers their patient to another provider of care should provide summary of care record for each transition of care or referral. ( AMC:170.304(i) )	<td>" . $rowcount . "	<td>" . $totalpats . "	<td>0	<td>0%
<tr><td>Provide patients with timely electronic access to their health information (including lab results, problem list, medication lists, medication allergies) within four business days of the information being available to the EP. ( AMC:170.304(g) )	<td>" . $rowcount . "	<td>" . $totalpats . "	<td>0	<td>0%
</table>";  
  
  
  die;
  

  
};  
  
//END -  RUN AN AUDIT REPORT SHOWING THE DATA AND ANY ERRORS BEHIND THE REPORT
  
  
  
  
  
  

?>

<html>

<head>
<?php html_header_show();?>

<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">

<?php if ($type_report == "standard") { ?>
  <title><?php echo htmlspecialchars( xl('Standard Measures'), ENT_NOQUOTES); ?></title>
<?php } ?>
<?php if ($type_report == "cqm") { ?>
  <title><?php echo htmlspecialchars( xl('Clinical Quality Measures (CQM)'), ENT_NOQUOTES); ?></title>
<?php } ?>
<?php if ($type_report == "amc") { ?>
  <title><?php echo htmlspecialchars( xl('Automated Measure Calculations (AMC)'), ENT_NOQUOTES); ?></title>
<?php } ?>

<script type="text/javascript" src="../../library/overlib_mini.js"></script>
<script type="text/javascript" src="../../library/textformat.js"></script>
<script type="text/javascript" src="../../library/dialog.js"></script>
<script type="text/javascript" src="../../library/js/jquery.1.3.2.js"></script>

<script LANGUAGE="JavaScript">

 var mypcc = '<?php echo $GLOBALS['phone_country_code'] ?>';

 function refreshme() {
    // location.reload();
    top.restoreSession();
    document.forms[0].submit();
 }

 function GenXml(sNested) {
	  top.restoreSession();
	  var sLoc = '../../custom/export_registry_xml.php?&target_date=' + theform.form_target_date.value + '&nested=' + sNested;
	  dlgopen(sLoc, '_blank', 600, 500);
	  return false;
}

</script>

<style type="text/css">

/* specifically include & exclude from printing */
@media print {
    #report_parameters {
        visibility: hidden;
        display: none;
    }
    #report_parameters_daterange {
        visibility: visible;
        display: inline;
    }
    #report_results table {
       margin-top: 0px;
    }
}

/* specifically exclude some from the screen */
@media screen {
    #report_parameters_daterange {
        visibility: hidden;
        display: none;
    }
}

</style>
</head>

<body class="body_top">

<!-- Required for the popup date selectors -->
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>

<span class='title'><?php echo htmlspecialchars( xl('Report'), ENT_NOQUOTES); ?> - 

<?php if ($type_report == "standard") { ?>
  <?php echo htmlspecialchars( xl('Standard Measures'), ENT_NOQUOTES); ?></span>
<?php } ?>
<?php if ($type_report == "cqm") { ?>
  <?php echo htmlspecialchars( xl('Clinical Quality Measures (CQM)'), ENT_NOQUOTES); ?></span>
<?php } ?>
<?php if ($type_report == "amc") { ?>
  <?php echo htmlspecialchars( xl('Automated Measure Calculations (AMC)'), ENT_NOQUOTES); ?></span>
<?php } ?>


<form method='post' name='theform' id='theform' action='cqm.php?type=<?php echo htmlspecialchars($type_report,ENT_QUOTES) ;?>' onsubmit='return top.restoreSession()'>

<div id="report_parameters">

<table>
 <tr>
  <td width='470px'>
	<div style='float:left'>

	<table class='text'>

		<?php if ($type_report == "amc") { ?>
                   <tr>
                      <td class='label'>
                         <?php echo htmlspecialchars( xl('Begin Date'), ENT_NOQUOTES); ?>:
                      </td>
                      <td>
                         <input type='text' name='form_begin_date' id="form_begin_date" size='20' value='<?php echo htmlspecialchars( $begin_date, ENT_QUOTES); ?>'
                            onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title='<?php echo htmlspecialchars( xl('yyyy-mm-dd hh:mm:ss'), ENT_QUOTES); ?>'>
                         <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
                            id='img_begin_date' border='0' alt='[?]' style='cursor:pointer'
                            title='<?php echo htmlspecialchars( xl('Click here to choose a date'), ENT_QUOTES); ?>'>
                      </td>
                   </tr>
		<?php } ?>

                <tr>
                        <td class='label'>
                           <?php if ($type_report == "amc") { ?>
                              <?php echo htmlspecialchars( xl('End Date'), ENT_NOQUOTES); ?>:
                           <?php } else { ?>
                              <?php echo htmlspecialchars( xl('Target Date'), ENT_NOQUOTES); ?>:
                           <?php } ?>
                        </td>
                        <td>
                           <input type='text' name='form_target_date' id="form_target_date" size='20' value='<?php echo htmlspecialchars( $target_date, ENT_QUOTES); ?>'
                                onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title='<?php echo htmlspecialchars( xl('yyyy-mm-dd hh:mm:ss'), ENT_QUOTES); ?>'>
                           <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
                                id='img_target_date' border='0' alt='[?]' style='cursor:pointer'
                                title='<?php echo htmlspecialchars( xl('Click here to choose a date'), ENT_QUOTES); ?>'>
                        </td>
                </tr>

                <?php if ($type_report == "cqm") { ?>
                    <input type='hidden' name='form_rule_filter' value='cqm'>
                <?php } ?>
                <?php if ($type_report == "amc") { ?>
                    <input type='hidden' name='form_rule_filter' value='amc'>
                <?php } ?>
                <?php if ($type_report == "standard") { ?>
                    <tr>
                        <td class='label'>
                            <?php echo htmlspecialchars( xl('Rule Set'), ENT_NOQUOTES); ?>:
                        </td>
                        <td>
                            <select name='form_rule_filter'>
                            <option value='passive_alert' <?php if ($rule_filter == "passive_alert") echo "selected"; ?>>
                            <?php echo htmlspecialchars( xl('Passive Alert Rules'), ENT_NOQUOTES); ?></option>
                            <option value='active_alert' <?php if ($rule_filter == "active_alert") echo "selected"; ?>>
                            <?php echo htmlspecialchars( xl('Active Alert Rules'), ENT_NOQUOTES); ?></option>
                            <option value='patient_reminder' <?php if ($rule_filter == "patient_reminder") echo "selected"; ?>>
                            <?php echo htmlspecialchars( xl('Patient Reminder Rules'), ENT_NOQUOTES); ?></option>
                            </select>
                        </td>
                    </tr>
                <?php } ?>

                <?php if ($type_report == "amc") { ?>
                    <input type='hidden' name='form_plan_filter' value=''>
                <?php } else { ?>
                    <tr>
                        <td class='label'>
                           <?php echo htmlspecialchars( xl('Plan Set'), ENT_NOQUOTES); ?>:
                        </td>
                        <td>
                                 <select name='form_plan_filter'>
                                 <option value=''>-- <?php echo htmlspecialchars( xl('Ignore'), ENT_NOQUOTES); ?> --</option>
                                 <?php if ($type_report == "cqm") { ?>
                                   <option value='cqm' <?php if ($plan_filter == "cqm") echo "selected"; ?>>
                                   <?php echo htmlspecialchars( xl('Official Clinical Quality Measures (CQM) Measure Groups'), ENT_NOQUOTES); ?></option>
                                 <?php } ?>
                                 <?php if ($type_report == "standard") { ?>
                                   <option value='normal' <?php if ($plan_filter == "normal") echo "selected"; ?>>
                                   <?php echo htmlspecialchars( xl('Active Plans'), ENT_NOQUOTES); ?></option>
                                 <?php } ?>
                        </td>
                    </tr>
                <?php } ?>

                <tr>      
			<td class='label'>
			   <?php echo htmlspecialchars( xl('Provider'), ENT_NOQUOTES); ?>:
			</td>
			<td>
				<?php

				 // Build a drop-down list of providers.
				 //

				 $query = "SELECT id, lname, fname FROM users WHERE ".
				  "authorized = 1  ORDER BY lname, fname"; //(CHEMED) facility filter

				 $ures = sqlStatement($query);

				 echo "   <select name='form_provider'>\n";
				 echo "    <option value=''>-- " . htmlspecialchars( xl('All (Cumulative)'), ENT_NOQUOTES) . " --\n";

                                 echo "    <option value='collate_outer'";
                                 if ($_POST['form_provider'] == 'collate_outer') echo " selected";
                                 echo ">-- " . htmlspecialchars( xl('All (Collated Format A)'), ENT_NOQUOTES) . " --\n";

                                 echo "    <option value='collate_inner'";
                                 if ($_POST['form_provider'] == 'collate_inner') echo " selected";
                                 echo ">-- " . htmlspecialchars( xl('All (Collated Format B)'), ENT_NOQUOTES) . " --\n";

				 while ($urow = sqlFetchArray($ures)) {
				  $provid = $urow['id'];
				  echo "    <option value='".htmlspecialchars( $provid, ENT_QUOTES)."'";
				  if ($provid == $_POST['form_provider']) echo " selected";
				  echo ">" . htmlspecialchars( $urow['lname'] . ", " . $urow['fname'], ENT_NOQUOTES) . "\n";
				 }

				 echo "   </select>\n";

				?>
                        </td>
		</tr>

                <?php if ($type_report == "amc") { ?>
                  <tr>
                        <td>
                               <?php echo htmlspecialchars( xl('Number labs'), ENT_NOQUOTES); ?>:<br>
                               (<?php echo htmlspecialchars( xl('Non-electronic'), ENT_NOQUOTES); ?>)
                        </td>
                        <td>
                               <input type="text" name="labs_manual_entry" value="<?php echo htmlspecialchars($labs_manual,ENT_QUOTES); ?>">
                        </td>
                  </tr>
                <?php } ?>

	</table>

	</div>

  </td>
  <td align='left' valign='middle' height="100%">
	<table style='border-left:1px solid; width:100%; height:100%' >
		<tr>
			<td>
				<div style='margin-left:15px'>
					<a href='#' class='css_button' onclick='$("#form_refresh").attr("value","true"); top.restoreSession(); $("#theform").submit();'>
					<span>
						<?php echo htmlspecialchars( xl('Submit'), ENT_NOQUOTES); ?>
					</span>
					</a>
					<?php if ($type_report == "cqm") { ?>
						<a href='#' class='css_button' onclick='return GenXml("false")'>
							<span>
								<?php echo htmlspecialchars( xl('Generate PQRI report (Method A)'), ENT_NOQUOTES); ?>
							</span>
						</a>
                                        	<a href='#' class='css_button' onclick='return GenXml("true")'>
                                                	<span>
                                                        	<?php echo htmlspecialchars( xl('Generate PQRI report (Method E)'), ENT_NOQUOTES); ?>
                                                	</span>
                                        	</a>
					<?php } ?>
                                        <?php if ($_POST['form_refresh']) { ?>
					<a href='#' class='css_button' onclick='window.print()'>
						<span>
							<?php echo htmlspecialchars( xl('Print'), ENT_NOQUOTES); ?>
						</span>
					</a>
					<?php } ?>
				</div>
			</td>
		</tr>
	</table>
  </td>
 </tr>
</table>

</div>  <!-- end of search parameters -->

<br>

<?php
 if ($_POST['form_refresh']) {
?>


<div id="report_results">
<table>

 <thead>
  <th>
   <?php echo htmlspecialchars( xl('Title'), ENT_NOQUOTES); ?>
  </th>

  <th>
   <?php echo htmlspecialchars( xl('Total Patients'), ENT_NOQUOTES); ?>
  </th>

  <th>
   <?php if ($type_report == "amc") { ?>
    <?php echo htmlspecialchars( xl('Denominator'), ENT_NOQUOTES); ?></a>
   <?php } else { ?>
    <?php echo htmlspecialchars( xl('Applicable Patients') .' (' . xl('Denominator') . ')', ENT_NOQUOTES); ?></a>
   <?php } ?>
  </th>

  <?php if ($type_report != "amc") { ?>
   <th>
    <?php echo htmlspecialchars( xl('Excluded Patients'), ENT_NOQUOTES); ?></a>
   </th>
  <?php } ?>

  <th>
   <?php if ($type_report == "amc") { ?>
    <?php echo htmlspecialchars( xl('Numerator'), ENT_NOQUOTES); ?></a>
   <?php } else { ?>
    <?php echo htmlspecialchars( xl('Passed Patients') . ' (' . xl('Numerator') . ')', ENT_NOQUOTES); ?></a>
   <?php } ?>
  </th>

  <th>
   <?php echo htmlspecialchars( xl('Performance Percentage'), ENT_NOQUOTES); ?></a>
  </th>

 </thead>
 <tbody>  <!-- added for better print-ability -->
<?php

  if ($type_report == "amc") {
  
  
  
    // For AMC:
    //   need to make $target_date an array with two elements ('dateBegin' and 'dateTarget')
    //   need to to send a manual data entry option (number of labs)
    $array_date = array();
    $array_date['dateBegin'] = $begin_date;
    $array_date['dateTarget'] = $target_date;
    $options = array('labs_manual'=>$labs_manual);
    $dataSheet = test_rules_clinic($provider,$rule_filter,$array_date,"report",'',$plan_filter,$organize_method,$options);
  }
  else {
  
  //echo $provider . " " . $rule_filter . " " . $target_date . " " . "report" . " " . '' . " " . $plan_filter . " " . $organize_method;
  //die;
  
  $dataSheet = test_rules_clinic($provider,$rule_filter,$target_date,"report",'',$plan_filter,$organize_method);
  }

  $firstProviderFlag = TRUE;
  $firstPlanFlag = TRUE;
  $existProvider = FALSE;
  foreach ($dataSheet as $row) {

?>

 <tr bgcolor='<?php echo $bgcolor ?>'>

  <?php
   if (isset($row['is_main']) || isset($row['is_sub'])) {
     echo "<td class='detail'>";
     if (isset($row['is_main'])) {
       echo "<b>".generate_display_field(array('data_type'=>'1','list_id'=>'clinical_rules'),$row['id'])."</b>";
       if (!empty($row['cqm_pqri_code']) || !empty($row['cqm_nqf_code']) || !empty($row['amc_code'])) {
         echo " (";
         if (!empty($row['cqm_pqri_code'])) {
         echo " " . htmlspecialchars( xl('PQRI') . ":" . $row['cqm_pqri_code'], ENT_NOQUOTES) . " ";
         }
         if (!empty($row['cqm_nqf_code'])) {
         echo " " . htmlspecialchars( xl('NQF') . ":" . $row['cqm_nqf_code'], ENT_NOQUOTES) . " ";
         }
         if (!empty($row['amc_code'])) {
         echo " " . htmlspecialchars( xl('AMC') . ":" . $row['amc_code'], ENT_NOQUOTES) . " ";
         }
         echo ")";
       }

       if ( !(empty($row['concatenated_label'])) ) {
           echo ", " . htmlspecialchars( xl( $row['concatenated_label'] ), ENT_NOQUOTES) . " ";
       }
       
     }
     else { // isset($row['is_sub'])
       echo generate_display_field(array('data_type'=>'1','list_id'=>'rule_action_category'),$row['action_category']);
       echo ": " . generate_display_field(array('data_type'=>'1','list_id'=>'rule_action'),$row['action_item']);
     }
     echo "</td>";
     echo "<td align='center'>" . $row['total_patients'] . "</td>";
     echo "<td align='center'>" . $row['pass_filter'] . "</td>";
     if ($type_report != "amc") {
       echo "<td align='center'>" . $row['excluded'] . "</td>";
     }
     echo "<td align='center'>" . $row['pass_target'] . "</td>";
     echo "<td align='center'>" . $row['percentage'] . "</td>";
   }
   else if (isset($row['is_provider'])) {
     // Display the provider information
     if (!$firstProviderFlag && $_POST['form_provider'] == 'collate_outer') {
       echo "<tr><td>&nbsp</td></tr>";
     }
     echo "<td class='detail' align='center'><b>";
     echo htmlspecialchars( xl("Provider").": " . $row['prov_lname'] . "," . $row['prov_fname'], ENT_NOQUOTES);
     if (!empty($row['npi']) || !empty($row['federaltaxid'])) {
       echo " (";
       if (!empty($row['npi'])) {
        echo " " . htmlspecialchars( xl('NPI') . ":" . $row['npi'], ENT_NOQUOTES) . " ";
       }
       if (!empty($row['federaltaxid'])) {
        echo " " . htmlspecialchars( xl('TID') . ":" . $row['federaltaxid'], ENT_NOQUOTES) . " ";
       }
       echo ")";
     }
     echo "</b></td>";
     $firstProviderFlag = FALSE;
     $existProvider = TRUE;
   }
   else { // isset($row['is_plan'])
     if (!$firstPlanFlag && $_POST['form_provider'] != 'collate_outer') {
       echo "<tr><td>&nbsp</td></tr>";
     }
     echo "<td class='detail' align='center'><b>";
     echo htmlspecialchars( xl("Plan"), ENT_NOQUOTES) . ": ";
     echo generate_display_field(array('data_type'=>'1','list_id'=>'clinical_plans'),$row['id']);
     if (!empty($row['cqm_measure_group'])) {
       echo " (". htmlspecialchars( xl('Measure Group Code') . ": " . $row['cqm_measure_group'], ENT_NOQUOTES) . ")";
     }
     echo "</b></td>";
     $firstPlanFlag = FALSE;
   }
  ?>
 </tr>

<?php
  }
?>
</tbody>
</table>
</div>  <!-- end of search results -->
<?php } else { ?>
<div class='text'>
 	<?php echo htmlspecialchars( xl('Please input search criteria above, and click Submit to view results.'), ENT_NOQUOTES); ?>
</div>
<?php } ?>

<input type='hidden' name='form_refresh' id='form_refresh' value=''/>

</form>

</body>

<!-- stuff for the popup calendar -->
<style type="text/css">@import url(../../library/dynarch_calendar.css);</style>
<script type="text/javascript" src="../../library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="../../library/dynarch_calendar_setup.js"></script>
<script language="Javascript">
 <?php if ($type_report == "amc") { ?>
  Calendar.setup({inputField:"form_begin_date", ifFormat:"%Y-%m-%d %H:%M:%S", button:"img_begin_date", showsTime:'true'});
 <?php } ?>
 Calendar.setup({inputField:"form_target_date", ifFormat:"%Y-%m-%d %H:%M:%S", button:"img_target_date", showsTime:'true'});
</script>

</html>

<?php


	?>
