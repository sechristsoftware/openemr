<?php
$ignoreAuth=true;
include_once("./globals.php");
?>
<html>
<body>

<?php
 // This will retain a patient id (or external patient id) after a failed
 // login to allow direct link to a patient after a successful authentication.
 //  Note that only one id will be used (it will only use patient id parameter
 //  if both are provided)
 $patientID_param='';
 if ( isset($_GET['patientID']) && !(empty($_GET['patientID'])) ) {
  $patientID_param="&patientID=" . attr($_GET['patientID']);
 }
 $external_patientID_param='';
 if ( isset($_GET['external_patientID']) && !(empty($_GET['external_patientID'])) && (empty($patientID_param)) ) {
  $external_patientID_param="&external_patientID=" . attr($_GET['external_patientID']);
 }
?>

<script LANGUAGE="JavaScript">
 top.location.href='<?php echo "$rootdir/login/login_frame.php?site=".$_SESSION['site_id'].$patientID_param.$external_patientID_param; ?>';
</script>

<a href='<?php echo "$rootdir/login/login_frame.php?site=".$_SESSION['site_id'].$patientID_param.$external_patientID_param; ?>'><?php xl('Follow manually','e'); ?></a>

<p>
<?php xl('OpenEMR requires Javascript to perform user authentication.','e'); ?>

</body>
</html>
