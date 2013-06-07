<?php
/**
 *
 * Script to change user password.
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
 * @author  Brady Miller <brady@sparmy.com>
 * @author  Kevin Yeh <kevin.y@integralemr.com>
 * @link    http://www.open-emr.org
 */

include_once("../globals.php");
include_once("$srcdir/sql.inc");
include_once("$srcdir/auth.inc");
include_once("$srcdir/sha1.js");
?>
<html>
<head>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<script src="checkpwd_validation.js" type="text/javascript"></script>
<script src="<?php echo $webroot;?>/library/js/jquery-1.9.1.min.js" type="text/javascript"></script>
<script src="<?php echo $webroot;?>/library/js/crypt/jsbn.js"></script>
<script src="<?php echo $webroot;?>/library/js/crypt/rsa.js"></script>

<script language='JavaScript'>
//Validating password and display message if password field is empty - starts
var webroot='<?php echo $webroot?>';
function update_password(user)
{
    top.restoreSession();

    // This method will encrypt or hash the password (prefer encryption if rsa is available).
    // (Does not need to support older hash formats since user is already logged in and has
    //  been migrated to the new salt method)
    var rsa_ajax='<?php echo $webroot;?>/library/ajax/rsa_request.php';
    $.post(rsa_ajax,{user: user},
        function(data)
        {
            var method = data.method;
            if (method == "rsa") {
                // If rsa is supported (this is the standard method)
                var key = RSA.getPublicKey(data.key);
                var encryptedPass=RSA.encrypt($("input[name='curPass']").val(), key);
                var encryptedNewPass=RSA.encrypt($("input[name='newPass']").val(),key)
                var encryptedNewPass2=RSA.encrypt($("input[name='newPass2']").val(),key)
                $("input[type='password']").val("");
            }
            else if (method == "sha1") {
                // If rsa is not supported (this is the backup method)

                // First need to do validation steps, since unable to do on server.
                var currentpass=trim(document.user_form.curPass.value);
                var password1=trim(document.user_form.newPass.value);
                var password2=trim(document.user_form.newPass2.value);
                document.getElementById("display_msg").innerHTML="";
                if (currentpass == "") {
                    alert("<?php echo xls('Please enter the password'); ?>");
                    document.user_form.curPass.focus();
                    return false;
                }
                if (password1 == "") {
                    alert("<?php echo xls('Please enter the password'); ?>");
                    document.user_form.newPass.focus();
                    return false;
                }
                if (password2 == "") {
                    alert("<?php echo xls('Please enter the password'); ?>");
                    document.user_form.newPass2.focus();
                    return false;
                }
                if (password1 != password2) {
                    alert("<?php echo xls('Error: passwords don\'t match. Please check your typing.'); ?>");
                    document.user_form.newPass.value="";
                    document.user_form.newPass2.value="";
                    document.user_form.newPass.focus();
                    return false;
                }
                //Checking for the strong password if the 'secure password' feature is enabled
                if(document.user_form.secure_pwd.value == 1) { 
                    var pwdresult = passwordvalidate(password1);
                    if  (pwdresult == 0){
                        alert("<?php echo xls('The password must be at least eight characters, and should'); echo '\n'; echo xls('contain at least three of the four following items:'); echo '\n'; echo xls('A number'); echo '\n'; echo xls('A lowercase letter'); echo '\n'; echo xls('An uppercase letter'); echo '\n'; echo xls('A special character');echo '('; echo xls('not a letter or number'); echo ').'; echo '\n'; echo xls('For example:'); echo ' healthCare@09'; ?>");
                        document.user_form.newPass.value="";
                        document.user_form.newPass2.value="";
                        document.user_form.newPass.focus();
                        return false;
                   }
                }

                // passed validation, so can now send via client sha1 method
                var salt = data.salt;
                var encryptedPass='$SHA1$' + SHA1(salt + $("input[name='curPass']").val());
                var encryptedNewPass='$SHA1$' + SHA1(salt + $("input[name='newPass']").val());
                var encryptedNewPass2='$SHA1$' + SHA1(salt + $("input[name='newPass2']").val());
                $("input[type='password']").val("");
                public_key = '';
            }
            else {
                alert("<?php echo xls("Server Configuration Error"); ?>");
                return false;
            }

            $.post("user_info_ajax.php",
                {
                    pk:         public_key,
                    curPass:    encryptedPass,
                    newPass:    encryptedNewPass,
                    newPass2:   encryptedNewPass2
                },
                function(data)
                {
                    $("#display_msg").html(data);
                }
            );
        }
    , "json"
    );

    return false;
}

</script>
</head>
<body class="body_top">

<span class="title"><?php echo xlt('Password Change'); ?></span>
<br><br>

<?php

$ip=$_SERVER['REMOTE_ADDR'];
$res = sqlStatement("select fname,lname,username from users where id=?",array($_SESSION["authId"])); 
$row = sqlFetchArray($res);
      $iter=$row;
?>
<div id="display_msg">
</div>
<br>
<FORM NAME="user_form" METHOD="POST" ACTION="user_info.php"
 onsubmit="top.restoreSession()">
<input type=hidden name=secure_pwd value="<?php echo $GLOBALS['secure_password']; ?>">
<TABLE>
<TR>
<TD><span class=text><?php xl('Full Name','e'); ?>: </span></TD>
<TD><span class=text><?php echo htmlspecialchars($iter["fname"] . " " . $iter["lname"], ENT_NOQUOTES); ?></span></td>
</TR>

<TR>
<TD><span class=text><?php xl('Username','e'); ?>: </span></TD>
<TD><span class=text><?php echo $iter["username"]; ?></span></td>
</TR>

<TR>
<TD><span class=text><?php xl('Current Password','e'); ?>: </span></TD>
<TD><input type=password name=curPass size=20 value="" autocomplete='off'></td>
</TR>

<TR>
<TD><span class=text><?php xl('New Password','e'); ?>: </span></TD>
<TD><input type=password name=newPass size=20 value="" autocomplete='off'></td>
</TR>
<TR>
<TD><span class=text><?php xl('Repeat New Password','e'); ?>: </span></TD>
<TD><input type=password name=newPass2 size=20 value="" autocomplete='off'></td>
</TR>

</TABLE>
<br>&nbsp;&nbsp;&nbsp;
<INPUT TYPE='Submit' VALUE=<?php echo xla('Save Changes'); ?> onClick='return update_password("<?php echo attr(addslashes($_SESSION['authUser'])) ?>")'>

<?php if (! $GLOBALS['concurrent_layout']) { ?>
&nbsp;&nbsp;&nbsp;
[<a href="../main/main_screen.php" target="_top" class="link_submit"
  onclick="top.restoreSession()"><?php xl('Back','e'); ?></font></a>]
<?php } ?>

</FORM>

<br><br>
</BODY>
</HTML>

<?php
//  da39a3ee5e6b4b0d3255bfef95601890afd80709 == blank
?>
