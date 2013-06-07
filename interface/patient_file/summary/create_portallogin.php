<?php
/**
 *
 * Script to create/modify patient portal credentials.
 *
 * Copyright (C) 2011 Z&H Consultancy Services Private Limited <sam@zhservices.com>
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
 * @author  Eldho Chacko <eldho@zhservices.com>
 * @author  Jacob T Paul <jacob@zhservices.com>
 * @author  Paul Simon   <paul@zhservices.com>
 * @author  Brady Miller <brady@sparmy.com>
 * @author  Brady Miller <brady@sparmy.com>
 * @link    http://www.open-emr.org
 */


//SANITIZE ALL ESCAPES
$sanitize_all_escapes=true;
//

//STOP FAKE REGISTER GLOBALS
$fake_register_globals=false;
//
 require_once("../../globals.php");
 require_once("$srcdir/sql.inc");
 require_once("$srcdir/formdata.inc.php");
 require_once("$srcdir/classes/postmaster.php");
 require_once("$srcdir/authentication/rsa.php");
 require_once("$srcdir/authentication/common_operations.php");

// Collect portalsite parameter (either off for offsite or on for onsite); only allow off or on
$portalsite = isset($_GET['portalsite']) ? $_GET['portalsite'] : $portalsite = "off";
if ($portalsite != "off" && $portalsite != "on") $portalsite = "off";

 $row = sqlQuery("SELECT pd.*,pao.portal_username,pao.portal_pwd,pao.portal_pwd_status FROM patient_data AS pd LEFT OUTER JOIN patient_access_" . add_escape_custom($portalsite) . "site AS pao ON pd.pid=pao.pid WHERE pd.pid=?",array($pid));
 
function generatePassword($length=6, $strength=1) {
	$consonants = 'bdghjmnpqrstvzacefiklowxy';
	$numbers = '0234561789';
	$specials = '@#$%';
	
 
	$password = '';
	$alt = time() % 2;
	for ($i = 0; $i < $length/3; $i++) {
		if ($alt == 1) {
			$password .= $consonants[(rand() % strlen($consonants))].$numbers[(rand() % strlen($numbers))].$specials[(rand() % strlen($specials))];
			$alt = 0;
		} else {
			$password .= $numbers[(rand() % strlen($numbers))].$specials[(rand() % strlen($specials))].$consonants[(rand() % strlen($consonants))];
			$alt = 1;
		}
	}
	return $password;
}

function validEmail($email){
    if(eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)) {
    return true;
    }
    return false;
}

function messageCreate($uname,$pass,$site){
    $message = htmlspecialchars( xl("Patient Portal Web Address"),ENT_NOQUOTES) . ":<br>";
    if ($site == "on") {
        $message .= "<a href='" . htmlspecialchars($GLOBALS['portal_onsite_address'],ENT_QUOTES) . "'>" .
                    htmlspecialchars($GLOBALS['portal_onsite_address'],ENT_NOQUOTES) . "</a><br><br>";
    } // $site == "off"
    else {
	$offsite_portal_patient_link = $GLOBALS['portal_offsite_address_patient_link'] ?  htmlspecialchars($GLOBALS['portal_offsite_address_patient_link'],ENT_QUOTES) : htmlspecialchars("https://mydocsportal.com",ENT_QUOTES);
        $message .= "<a href='" . $offsite_portal_patient_link . "'>" .
                    $offsite_portal_patient_link . "</a><br><br>";
	$message .= htmlspecialchars(xl("Provider Id"),ENT_NOQUOTES) . ": " .
		    htmlspecialchars($GLOBALS['portal_offsite_providerid'],ENT_NOQUOTES) . "<br><br>";		    
    }
    
        $message .= htmlspecialchars(xl("User Name"),ENT_NOQUOTES) . ": " .
                    htmlspecialchars($uname,ENT_NOQUOTES) . "<br><br>" .
                    htmlspecialchars(xl("Password"),ENT_NOQUOTES) . ": " .
                    htmlspecialchars($pass,ENT_NOQUOTES) . "<br><br>";
    return $message;
}

function emailLogin($patient_id,$message){
    $patientData = sqlQuery("SELECT * FROM `patient_data` WHERE `pid`=?", array($patient_id) );
    if ( $patientData['hipaa_allowemail'] != "YES" || empty($patientData['email']) || empty($GLOBALS['patient_reminder_sender_email']) ) {
        return false;
    }
    if (!(validEmail($patientData['email']))) {
        return false;
    }
    if (!(validEmail($GLOBALS['patient_reminder_sender_email']))) {
        return false;
    }

    $mail = new MyMailer();
    $pt_name=$patientData['fname'].' '.$patientData['lname'];
    $pt_email=$patientData['email'];
    $email_subject=xl('Access Your Patient Portal');
    $email_sender=$GLOBALS['patient_reminder_sender_email'];
    $mail->AddReplyTo($email_sender, $email_sender);
    $mail->SetFrom($email_sender, $email_sender);
    $mail->AddAddress($pt_email, $pt_name);
    $mail->Subject = $email_subject;
    $mail->MsgHTML("<html><body><div class='wrapper'>".$message."</div></body></html>");
    $mail->IsHTML(true);
    $mail->AltBody = $message;
				    
    if ($mail->Send()) {
        return true;
    } else {
        $email_status = $mail->ErrorInfo;
        error_log("EMAIL ERROR: ".$email_status,0);
        return false;
    }
}

function displayLogin($patient_id,$message,$emailFlag){
    $patientData = sqlQuery("SELECT * FROM `patient_data` WHERE `pid`=?", array($patient_id) );
    if ($emailFlag) {
        $message = "<br><br>" . 
                   htmlspecialchars(xl("Email was sent to following address"),ENT_NOQUOTES) . ": " .
                   htmlspecialchars($patientData['email'],ENT_NOQUOTES) . "<br><br>" .
                   $message;
    }
    echo "<html><body onload='window.print();'>" . $message . "</body></html>";
}

if(isset($_REQUEST['form_save']) && $_REQUEST['form_save']=='SUBMIT'){

    if (isset($_REQUEST['pk']) && !empty($_REQUEST['pk'])) {
        // RSA is supported, so need to decrypt the password
        $pubKey=$_REQUEST['pk'];
        $rsa=new rsa_key_manager();
        $rsa->load_from_db($pubKey);
        $clear_pass=$rsa->decrypt($_REQUEST['rsa_pwd']);
    }
    else {
        // RSA is not supported, so passing the free text password
        $clear_pass=$_REQUEST['pwd'];
    }
    
    $res = sqlStatement("SELECT * FROM patient_access_" . add_escape_custom($portalsite) . "site WHERE pid=?",array($pid));
    $query_parameters=array($_REQUEST['uname']);
    $salt_clause="";
    if($portalsite=='on')
    {
        // For onsite portal create a hash and salt.
        //  (Also need to create a client side salt and mimick it in the hash creation)
        // 1. Create and mimick the client side hash
        $new_salt_client = password_salt(true);
        $client_hash = password_hash($clear_pass,$new_salt_client);
        // 2. Create the server side hash
        $new_salt = password_salt();
        $main_hash = password_hash($client_hash,$new_salt);

        $salt_clause = ",portal_salt=?,portal_salt_client_side=? ";
        array_push($query_parameters,$main_hash,$new_salt,$new_salt_client);
    }
    else
    {
        // For offsite portal still create and SHA1 hashed password
        // When offsite portal is updated to handle salts, then both portals can use the same execution path.
        array_push($query_parameters,SHA1($clear_pass));
    }
    array_push($query_parameters,$pid);
    if(sqlNumRows($res)){
    sqlStatement("UPDATE patient_access_" . add_escape_custom($portalsite) . "site SET portal_username=?,portal_pwd=?,portal_pwd_status=0 " . $salt_clause . " WHERE pid=?",$query_parameters);
    }
    else{
    sqlStatement("INSERT INTO patient_access_" . add_escape_custom($portalsite) . "site SET portal_username=?,portal_pwd=?,portal_pwd_status=0" . $salt_clause . " ,pid=?",$query_parameters);
    }
   
    // Create the message
    $message = messageCreate($_REQUEST['uname'],$clear_pass,$portalsite);
    // Email and display/print the message
    if ( emailLogin($pid,$message) ) {
        // email was sent
        displayLogin($pid,$message,true);
    }
    else {
        // email wasn't sent
        displayLogin($pid,$message,false);
    }
    exit;
} ?>

<html>
<head>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">

<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery-1.6.4.min.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/crypt/jsbn.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/crypt/rsa.js"></script>
<script type="text/javascript">
function encryptPass(){
    // If RSA is available, then will use it to encrypt. Otherwise, will send the password as free text.
    var rsa_ajax='<?php echo $webroot;?>/library/ajax/rsa_request.php';
    $.post(rsa_ajax,{},
        function(data)
        {
            var method = data.method;
            if (method == "rsa") {
                // If RSA is available, then send the password encrypted
                var key = RSA.getPublicKey(data.key);
                document.getElementById('form_save').value='SUBMIT';
                document.getElementById('rsa_pwd').value=RSA.encrypt(document.getElementById('pwd').value,key);
                document.getElementById('pwd').value='';
                document.getElementById('pk').value=data.key;
            }
            else {
                // RSA is not available, so send password as free text
                document.getElementById('form_save').value='SUBMIT';
                document.getElementById('pk').value='';
            }
            top.restoreSession();
            document.forms[0].submit();
        }
    , "json"
    );    
}
</script>
</head>
<body class="body_top">
    <form name="portallogin" action="" method="POST">
    <table align="center" style="margin-top:10px">
        <tr class="text">
            <th colspan="5" align="center"><?php echo htmlspecialchars(xl("Generate Username And Password For")." ".$row['fname'],ENT_QUOTES);?></th>
        </tr>
	<?php
		if($portalsite == 'off'){
	?>
        <tr class="text">
            <td><?php echo htmlspecialchars(xl('Provider Id').':',ENT_QUOTES);?></td>
            <td><span><?php echo htmlspecialchars($GLOBALS['portal_offsite_providerid'],ENT_QUOTES);?></span></td>
        </tr>			
	<?php		
		}
	?>
        <tr class="text">
            <td><?php echo htmlspecialchars(xl('User Name').':',ENT_QUOTES);?></td>
            <td><input type="text" name="uname" value="<?php if($row['portal_username']) echo htmlspecialchars($row['portal_username'],ENT_QUOTES); else echo htmlspecialchars($row['fname'].$row['id'],ENT_QUOTES);?>" size="10" readonly></td>
        </tr>
        <tr class="text">
            <td><?php echo htmlspecialchars(xl('Password').':',ENT_QUOTES);?></td>
            <?php
            $pwd = generatePassword();
            ?>
            <td><input type="text" name="pwd" id="pwd" value="<?php echo htmlspecialchars($pwd,ENT_QUOTES);?>" size="10"/>
                <input type="hidden" name="rsa_pwd" id="rsa_pwd"/>
                <input type="hidden" name="pk" id="pk"/>
            </td>
            <td><a href="#" class="css_button" onclick="top.restoreSession(); javascript:document.location.reload()"><span><?php echo htmlspecialchars(xl('Change'),ENT_QUOTES);?></span></a></td>
        </tr>
        <tr class="text">
            <td><input type="hidden" name="form_save" id="form_save"></td>
            <td colspan="5" align="center">
                <a href="#" class="css_button" onclick="return encryptPass()"><span><?php echo htmlspecialchars(xl('Save'),ENT_QUOTES);?></span></a>
                <input type="hidden" name="form_cancel" id="form_cancel">
                <a href="#" class="css_button" onclick="top.restoreSession(); parent.$.fn.fancybox.close();"><span><?php echo htmlspecialchars(xl('Cancel'),ENT_QUOTES);?></span></a>
            </td>
        </tr>
    </table>
    </form>
</body>
