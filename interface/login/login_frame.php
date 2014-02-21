<?php
$ignoreAuth = true;
include_once ("../globals.php");
?>
<HTML>
<head>
<?php html_header_show(); ?>
<TITLE><?php xl ('Login','e'); ?></TITLE>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<link rel=stylesheet href="<?php echo $css_header;?>" type="text/css">
<link rel=stylesheet href="../themes/login.css" type="text/css">

</HEAD>

<?php
 // This will capture a patient id (or external patient id) to allow direct
 // link to a patient after a successful authentication.
 //  Note that only one id will be used (it will only use patient id parameter
 //  if both are provided)
 $patientID_param='';
 if ( isset($_GET['patientID']) && !(empty($_GET['patientID'])) ) {
  $patientID_param="?patientID=" . attr($_GET['patientID']);
 }
 $external_patientID_param='';
 if ( isset($_GET['external_patientID']) && !(empty($_GET['external_patientID'])) && (empty($patientID_param)) ) {
  $external_patientID_param="?external_patientID=" . attr($_GET['external_patientID']);
 }
?>

<frameset rows="<?php echo "$GLOBALS[logoBarHeight],$GLOBALS[titleBarHeight]" ?>,*" cols="*" frameborder="NO" border="0" framespacing="0">
  <frame class="logobar" src="<?php echo $rootdir;?>/login/filler.php" name="Filler Top" scrolling="no" noresize frameborder="NO">
  <frame class="titlebar" src="<?php echo $rootdir;?>/login/login_title.php" name="Title" scrolling="no" noresize frameborder="NO">
  <frame src="<?php echo $rootdir . "/login/login.php" . $patientID_param . $external_patientID_param ?>" name="Login" scrolling="auto" frameborder="NO">
  <!--<frame src="<?php echo $rootdir;?>/login/filler.php" name="Filler Bottom" scrolling="no" noresize frameborder="NO">-->
</frameset>

<noframes><body bgcolor="#FFFFFF">

</body></noframes>

</HTML>
