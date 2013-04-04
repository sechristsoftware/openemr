<?php

include_once(dirname(__file__)."/../../globals.php");

function newpatient_report( $pid, $encounter, $cols, $id) {
	$res = sqlStatement("select * from form_encounter where pid='$pid' and id='$id'");
	print "<table><tr><td valign=top>\n";
	while($result = sqlFetchArray($res)) {
	$reason = "";
	$reason .= htmlspecialchars( $result{"reason"}, ENT_NOQUOTES) . "";
	$reason=nl2br($reason);
		print "<span class=bold>" . xl('Reason') . ": </span></td><td valign=top><span class=text>";
		print "$reason";
		print "</td></tr><tr><td valign=top>";
		print "<span class=bold>" . xl('Facility') . ": </span></td><td valign=top><span class=text>" . $result{"facility"} . "<br>\n";

	}
	print "</td></tr></table>\n";
}

?>
