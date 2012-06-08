<?php

// Copyright (C) 2005-2006 Rod Roark <rod@sunsetsystems.com>
//
// Windows compatibility mods 2009 Bill Cernansky [mi-squared.com]
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// Updated by Medical Information Integration, LLC to support download
//  and multi OS use - tony@mi-squared..com 12-2009

//////////////////////////////////////////////////////////////////////
// This is a template for printing patient statements and collection
// letters.  You must customize it to suit your practice.  If your
// needs are simple then you do not need programming experience to do
// this - just read the comments and make appropriate substitutions.
// All you really need to do is replace the [strings in brackets].
//////////////////////////////////////////////////////////////////////

// The location/name of a temporary file to hold printable statements.
//

$STMT_TEMP_FILE = $GLOBALS['temporary_files_dir'] . "/openemr_statements.txt";
$STMT_TEMP_FILE_PDF = $GLOBALS['temporary_files_dir'] . "/openemr_statements.pdf";

$STMT_PRINT_CMD = $GLOBALS['print_command']; 

// This function builds a printable statement or collection letter from
// an associative array having the following keys:
//
//  today   = statement date yyyy-mm-dd
//  pid     = patient ID
//  patient = patient name
//  amount  = total amount due
//  to      = array of addressee name/address lines
//  lines   = array of lines, each with the following keys:
//    dos     = date of service yyyy-mm-dd
//    desc    = description
//    amount  = charge less adjustments
//    paid    = amount paid
//    notice  = 1 for first notice, 2 for second, etc.
//    detail  = associative array of details
//
// Each detail array is keyed on a string beginning with a date in
// yyyy-mm-dd format, or blanks in the case of the original charge
// items.  Its values are associative arrays like this:
//
//  pmt - payment amount as a positive number, only for payments
//  src - check number or other source, only for payments
//  chg - invoice line item amount amount, only for charges or
//        adjustments (adjustments may be zero)
//  rsn - adjustment reason, only for adjustments
//
// The returned value is a string that can be sent to a printer.
// This example is plain text, but if you are a hotshot programmer
// then you could make a PDF or PostScript or whatever peels your
// banana.  These strings are sent in succession, so append a form
// feed if that is appropriate.
//

// ALB If the resulting statement is printed out using PDF format in actual size (don't shrink to fit), once folded at the bottom and halfway down the middle, it fits a 2-window #10 envelope perfectly, with both addresses showing.

//ALB The statement uses Guardian's name from the demographics for addressing the letter, if present. That way, if the patient is a minor, it's addressed to the parent, but still has the correct patient identified on the statement.


function create_statement($stmt) {
 if (! $stmt['pid']) return ""; // get out if no data

 // These are your clinics return address, contact etc.  Edit them.
 // TBD: read this from the facility table
 
 // Facility (service location)
  $atres = sqlStatement("select f.name,f.street,f.city,f.state,f.postal_code,f.phone,f.fax from facility f " .
    " left join users u on f.id=u.facility_id " .
    " left join  billing b on b.provider_id=u.id and b.pid = '".$stmt['pid']."' " .
    " where  service_location=1");
  $row = sqlFetchArray($atres);
 
 // Facility (service location)
 
 $clinic_name = "{$row['name']}";
 $clinic_addr = "{$row['street']}";
 $clinic_csz = "{$row['city']}, {$row['state']} {$row['postal_code']}";
 $clinic_phone = "{$row['phone']}";
 $clinic_fax = "{$row['fax']}";

 
 // Billing location
 $remit_name = $clinic_name;
 $remit_addr = $clinic_addr;
 $remit_csz = $clinic_csz;
 
 // Contacts
  $atres = sqlStatement("select f.attn,f.phone from facility f " .
    " left join users u on f.id=u.facility_id " .
    " left join  billing b on b.provider_id=u.id and b.pid = '".$stmt['pid']."'  " .
    " where billing_location=1");
  $row = sqlFetchArray($atres);
 $billing_contact = "{$row['attn']}";
 $billing_phone = "{$row['phone']}";

 // Text only labels
 
 $label_addressee = xl('ADDRESSEE');
 $label_remitto = xl('REMIT TO');
 $label_ptname = xl('Patient');
 $label_chartnum = xl('Account Number');
 $label_statdate = xl('Statement Date');

// If negative balance, we owe the patient, so change labels
 if ($stmt['amount']>=0) {
   $stmtamount = $stmt['amount'];
   $label_paydate = xl('Balance due within 2 weeks');
   $label_totaldue = xl('Balance Due');
   $label_due = xl('Remaining Balance');
   $label_payby = xl('If paying by');
   $label_cards = xl('VISA, MC, or Discover');  
   $label_cardnum = xl('Card Number');
   $label_expiry = xl('Exp Date');
   $label_seccode = xl('Security Code');
   $label_sign = xl('Signature');
   $label_prompt = xl('We appreciate prompt payment of balances due.');
 } else {
   $stmtamount = -$stmt['amount'];
   $label_paydate = xl('Refund check enclosed below');
   $label_totaldue = xl('Refund Due');
   $label_due = xl('Remaining Balance');
   $label_payby = xl('');
   $label_cards = xl('');  
   $label_cardnum = xl('');
   $label_expiry = xl('');
   $label_seccode = xl('');
   $label_sign = xl('');
   $label_remitto = xl('REFUND FROM');
   $label_prompt = xl('Refund check enclosed. We appreciate your business.');
 }

 $label_keep = xl('Keep the above portion for your records.');
 $label_retpay = xl('Please return this portion with your payment. DO NOT SEND CASH.');
 $label_pgbrk = xl('STATEMENT SUMMARY');
 $label_visit = xl('Visit Date');
 $label_desc = xl('Code and Description');
 $label_qty = xl('Qty');
 $label_amt = xl('Amount');
 $label_ptname = xl('Patient');
 $label_today = xl('Statement Date');
 $label_thanks = xl('Thank you for choosing');
 $label_call = xl('Please call if any of the above information is incorrect.');
 $label_dept = xl('Billing Department');

//Get statement number, so we can change the wording on it.
 $stmtno = 0;
 foreach ($stmt['lines'] as $line) {
   $stmtnonext = $line['notice'];
   if ($stmtnonext > $stmtno) {
 	$stmtno = $stmtnonext;
   }
 }

 if ($stmtno < 2 || $stmt['amount']<0) {
   $stmtnotice1 = '';
   $stmtnotice2 = '';
   $stmtnotice3 = '';
   $stmtnotice4 = '';
 } else if ($stmtno == 2) {
   $stmtnotice1 = '';
   $stmtnotice2 = 'SECOND NOTICE';
   $stmtnotice3 = '';
   $stmtnotice4 = '';
 } else if ($stmtno == 3) {
   $stmtnotice1 = 'THIRD NOTICE';
   $stmtnotice2 = 'PLEASE CALL US TODAY TO';
   $stmtnotice3 = 'SETTLE YOUR BALANCE OR TO';
   $stmtnotice4 = 'ARRANGE A PAYMENT PLAN';
 } else {
   $stmtnotice1 = 'FINAL NOTICE';
   $stmtnotice2 = 'YOUR ACCOUNT WILL BE TURNED';
   $stmtnotice3 = 'OVER TO A COLLECTION AGENCY';
   $stmtnotice4 = 'IF NOT PAID OFF IN 2 WEEKS';
 }

 // This is the text for the top part of the page, up to but not
 // including the detail lines.  Some examples of variable fields are:
 //  %s    = string with no minimum width
 //  %9s   = right-justified string of 9 characters padded with spaces
 //  %-25s = left-justified string of 25 characters padded with spaces
 // Note that "\n" is a line feed (new line) character.
 // reformatted to handle i8n by tony


$out .= "\n";
$out .= sprintf("%-45s\n",$clinic_name);
$out .= sprintf("%-45s %s\n",$clinic_addr,$stmtnotice1);
$out .= sprintf("%-45s %s\n",$clinic_csz,$stmtnotice2);
$out .= sprintf("%-45s %s\n",'Phone: '.$clinic_phone,$stmtnotice3);
$out .= sprintf("%-45s %s\n",'Fax: '.$clinic_fax,$stmtnotice4);
$out .= "\n";
$out .= sprintf("_______________________________________________________________________\n");

$out .= "\n";

//Need to get guardian's name for children for letter address
$guard = sqlStatement("select p.guardiansname from patient_data p ". 
    "where p.pid = '".$stmt['pid']."' ");
$guardrow = sqlFetchArray($guard);
if ($guardrow['guardiansname']!='') {
 $legal_addressee = "{$guardrow['guardiansname']}";
} else {
$legal_addressee = $stmt['to'][0];
}

$out .= sprintf("%-36s | %s: %-s\n",$label_addressee,$label_ptname,substr($stmt['patient'],0,20));
$out .= sprintf("%-36s | %s: %-s\n",null,$label_statdate,$stmt['today']);
$out .= sprintf("%-36s | %s: %-s\n",$legal_addressee,$label_chartnum,$stmt['pid']);
$out .= sprintf("%-36s | %s: %-s\n",$stmt['to'][1],$label_totaldue,'$'.$stmtamount);
$out .= sprintf("%-36s | %s\n",$stmt['to'][2],$label_paydate);

$out .= sprintf("_______________________________________________________________________\n");
$out .= "\n";
$out .= sprintf("__________________________ %s __________________________\n",$label_pgbrk);
$out .= "\n";
$out .= sprintf("%-12s %-42s %-7s %s\n",$label_visit,$label_desc,$label_qty,$label_amt);
$out .= "\n";
 
 // This must be set to the number of lines generated above.
 //
 $count = 19;

 // This generates the detail lines.  Again, note that the values must
 // be specified in the order used.
 //
 foreach ($stmt['lines'] as $line) {
  $description = $line['desc'];
  $proccode = substr($description, 10, 5);

//This will put in the description of the CPT code.

  $getproc = sqlStatement("select c.code,c.code_text from codes c where c.code='".$proccode."'"); 
  $procrow = sqlFetchArray($getproc);
  $procdesc = "{$procrow['code_text']}";
  if($procdesc !='') {
    $description = $proccode.": ".$procdesc;
  } else {
    $description = $proccode;
  }
  $dos = $line['dos'];
  ksort($line['detail']);

  foreach ($line['detail'] as $dkey => $ddata) {
   $ddate = substr($dkey, 0, 10);
   if (preg_match('/^(\d\d\d\d)(\d\d)(\d\d)\s*$/', $ddate, $matches)) {
    $ddate = $matches[1] . '-' . $matches[2] . '-' . $matches[3];
   }
   $amount = '';
   $units = '';

   if ($ddata['pmt']) {
    $amount = sprintf("%.2f", 0 - $ddata['pmt']);
    $desc = xl(' Paid') .' '. $ddate .': '. $ddata['src'].' '. $ddata['insurance_company'];
   } else if ($ddata['rsn']) {
    if ($ddata['chg']) {
     $amount = sprintf("%.2f", $ddata['chg']);
     $desc = xl(' Adj') .' '.  $ddate .': ' . $ddata['rsn'].' '. $ddata['insurance_company'];
    } else {
     $desc = xl(' Note') .' '. $ddate .': '. $ddata['rsn'].' '. $ddata['insurance_company'];
    }
   } else if ($ddata['chg'] < 0) {
    $amount = sprintf("%.2f", $ddata['chg']);
    $desc = xl(' Patient Payment');
   } else {
    $amount = sprintf("%.2f", $ddata['chg']);
    $desc = $description;
    //Getting the number of units
    $getunits = sqlStatement("select b.units from billing b where   	b.code = '".$proccode."' and b.fee = '".$amount."' and b.pid 	= '".$stmt['pid']."' ");
    $unitsrow = sqlFetchArray($getunits);
    $unitnumber = $unitsrow['units'];
    if ($unitnumber>0) {
	$units = $unitnumber;
    }
   }

//Trimming line description if too long.
   if(strlen($desc)>40) {
    $desc = substr($desc,0,37)."...";
   } 

//Deductible is misspelled in the system. Hardcoding change a>i
//   $pos = strpos($desc, 'eductab', 1);
//   if($pos!=false) {


   $out .= sprintf("%-10s | %-40s | %3s | %8s\n", $dos, $desc, $units,$amount);
   $dos = '';
   ++$count;
  }
 }

 // This generates blank lines until we are at line 39.
 //
 while ($count++ < 39) $out .= sprintf("%-10s | %-40s | %-3s | %8s\n", null, null, null, null);

 $out .= "\n";

 // This is the bottom portion of the page.
 
 $out .= sprintf("%60s: %8s\n",$label_due,$stmt['amount']);
 $out .= sprintf("_______________________________________________________________________\n");
 $out .= "\n";

if ($stmt['amount']<0) {
 $out .= $label_prompt;
 $out .= "\014"; // this is a form feed
 return $out;
}

$out .= sprintf("%55s\n",$label_keep);
$out .= sprintf("-----------------------------------------------------------------------\n");
$out .= sprintf("%67s\n",$label_retpay);
$out .= "\n";

$out .= sprintf("%s: %-29s %-s\n",$label_ptname,substr($stmt['patient'],0,20),$label_remitto);
$out .= sprintf("%s: %-22s %-s\n",$label_statdate,$stmt['today'],$remit_name);
$out .= sprintf("%s: %-22s %-s\n",$label_chartnum,$stmt['pid'],$remit_addr);
$out .= sprintf("%s: %-25s %-s\n",$label_totaldue,'$'.$stmtamount,$remit_csz);
$out .= "\n";
$out .= sprintf("Please make checks payable to: %-s.\n",$remit_name);
$out .= sprintf("%-s, please fill out below:\n",$label_payby.' '.$label_cards);
$out .= "\n";
$out .= sprintf("%s:_____________________________________ %s:____/____\n",$label_cardnum,$label_expiry);
$out .= "\n";
$out .= sprintf("%s:__________ %s:_________________________________\n",$label_seccode,$label_sign);
$out .= "\n";
$out .= sprintf("%-s\n",$label_prompt);

$out .= "\014"; // this is a form feed
 
 return $out;
}
?>
