<?php
$ignoreAuth = true;
include_once ("../globals.php");
?>
<html>
<head>
    <?php html_header_show(); ?>
    <title><?php xl('Login', 'e'); ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel=stylesheet href="<?php echo $css_header;?>" type="text/css">
    <link rel=stylesheet href="../themes/login.css" type="text/css">
</head>

<frameset
    rows="<?php echo "$GLOBALS[logoBarHeight], $GLOBALS[titleBarHeight]" ?>,*"
    cols="*"
    frameborder="no"
    border="0"
    framespacing="0">
    <frame
        class="logobar"
        src="<?php echo $rootdir;?>/login/filler.php"
        name="Filler Top"
        scrolling="no"
        noresize
        frameborder="no">
    <frame
        class="titlebar"
        src="<?php echo $rootdir;?>/login/login_title.php"
        name="Title"
        scrolling="no"
        noresize
        frameborder="no">
    <frame
        src="<?php echo $rootdir;?>/login/login.php"
        name="Login"
        scrolling="auto"
        frameborder="no">
    <!--
    <frame
        src="<?php echo $rootdir;?>/login/filler.php"
        name="Filler Bottom"
        scrolling="no"
        noresize
        frameborder="no">
    -->
</frameset>
<noframes>
    <body bgcolor="#ffffff">

    </body>
</noframes>
</html>
