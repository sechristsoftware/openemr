<?php
/**
 * main_title.php - The main titlebar, at the top of the 'concurrent' layout.
 */

include_once('../globals.php');
?>
<html>
<head>
<link rel="stylesheet" href="../themes/app.css" type="text/css">
<style type="text/css">
      .hidden {
        display:none;
      }
      .visible{
        display:block;
      }
</style>
<script type="text/javascript" src="../../library/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript">
/**
 * Called onchange of encounter list
 *
 * @param rawdata
 * @returns {boolean}
 */
function toencounter(rawdata) {
	document.getElementById('EncounterHistory').selectedIndex=0;
    if(rawdata=='') {
        return false;
    } else if (rawdata=='New Encounter') {
        top.window.parent.left_nav.loadFrame2('nen1','RBot','forms/newpatient/new.php?autoloaded=1&calenc=')
        return true;
    } else if (rawdata=='Past Encounter List') {
	 	top.window.parent.left_nav.loadFrame2('pel1','RBot','patient_file/history/encounters.php')
		return true;
    }
    var parts = rawdata.split("~");
    var enc = parts[0];
    var datestr = parts[1];
    var f = top.window.parent.left_nav.document.forms[0];
	frame = 'RBot';
    if (!f.cb_bot.checked) {
        frame = 'RTop';
    } else if (!f.cb_top.checked) {
        frame = 'RBot';
    }

    top.restoreSession();

    parent.left_nav.setEncounter(datestr, enc, frame);
    top.frames[frame].location.href  = '../patient_file/encounter/encounter_top.php?set_encounter=' + enc;
}
function showhideMenu() {
	var m = parent.document.getElementById("fsbody");
	var targetWidth = '0,*';
	if (m.cols == targetWidth) {
		m.cols = '<?php echo $GLOBALS['gbl_nav_area_width'] ?>,*';
		document.getElementById("showMenuLink").innerHTML = '<?php echo htmlspecialchars(xl('Hide Menu'), ENT_QUOTES); ?>';
	} else {
		m.cols = targetWidth;
		document.getElementById("showMenuLink").innerHTML = '<?php echo htmlspecialchars(xl('Show Menu'), ENT_QUOTES); ?>';
	}
}
$(document).ready(function(){
    $('form[name="find_patient"]').submit(function(form) {
        $('input[type="text"]').val('');
    });
});
</script>
</head>
<body class="body_title dark top_menu">
<?php
$res = sqlQuery("select * from users where username='".$_SESSION{"authUser"}."'");
if ($GLOBALS['concurrent_layout']) :
    if (acl_check('patients', 'demo', '', array('write','addonly'))) : ?>
        <div>
            <div class="small-6 columns">
                <ul class="no-bullet inline-list">
                    <li>
                        <a class="text"
                           href="main_title.php"
                           id='showMenuLink'
                           onclick='javascript:showhideMenu();return false;'>
                            <?php xl('Hide Menu', 'e'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="new/new.php"
                           data-function="loadFrame2">
                            <?php xl('NEW PATIENT', 'e');?>
                        </a>
                    </li>
                    <li>
                        <a href='#'
                           style="display:none;"
                           id='clear_active'
                           onClick="javascript:parent.left_nav.clearactive();return false;">
                            <?php xl('CLEAR ACTIVE PATIENT', 'e'); ?>
                        </a>
                    </li>
                    <li>
                        <a href='main_title.php'
						   onclick="javascript:parent.left_nav.goHome();return false;" >
                            <?php xl('Home', 'e'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="../logout.php"
						   target="_top" 
						   id="logout_link" onclick="top.restoreSession()" >
                            <?php xl('Logout', 'e'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                        <?php echo htmlspecialchars($res{"fname"} .' '. $res{"lname"}, ENT_NOQUOTES); ?>
                        </a>
                    </li>
                </ul>
        <?php
    endif; ?>
            </div>
            <div class="small-6 column">
                <form method='post'
                      name='find_patient'
                      target='RTop'
                      action='<?php echo $rootdir ?>/main/finder/patient_select.php'>
                    <input type="text" placeholder="Find patient" />
                    <input type='hidden' name='findBy' value='Any'/>
                    <input type="hidden" name="searchFields" id="searchFields"/>
                    <input type="hidden" name="search_service_code" value=''/>
                </form>
            </div>
            <div class="small-6 column">
                <div style='display:none' id="past_encounter_block">
                    <span class='title_bar_top' id="past_encounter"></span>
                </div>
                <div style='display:none' class='text' id="current_encounter_block" >
                    <span class='text'><?php xl('Selected Encounter', 'e'); ?>:&nbsp;</span>
                </div>
            </div>
        </div>
    <?php
endif; ?>

<script type="text/javascript">
parent.loadedFrameCount += 1;
</script>
</body>
</html>
