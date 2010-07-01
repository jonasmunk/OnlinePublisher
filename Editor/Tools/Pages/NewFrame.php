<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';

$hiers=buildHierarchies();

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" align="center" top="30">'.
'<titlebar title="Ny ramme" icon="Web/Frame">'.
'<close link="Frames.php"/>'.
'</titlebar>'.
'<content padding="5" background="true">';
if (strlen($hiers)>0) {
	$gui.=
	'<form xmlns="uri:Form" action="CreateFrame.php" method="post" name="Formula" focus="name" submit="true">'.
	'<validation>
	if (Name.isEmpty()) {
		Name.setError("Skal udfyldes!");
		Title.setError("");
		Name.blinkError(1000);
		Name.focus();
		return false;
	}
	else if (Title.isEmpty()) {
		Title.setError("Skal udfyldes!");
		Name.setError("");
		Title.blinkError(1000);
		Title.focus();
		return false;
	}
	else {
		return true;
	}
	</validation>'.
	'<group size="Large">'.
	'<textfield badge="Navn:" name="name" object="Name"/>'.
	'<textfield badge="Titel:" name="title" object="Title"/>'.
	'<select badge="Hierarki:" name="hierarchy">'.
	$hiers.
	'</select>'.
	'<buttongroup size="Large">'.
	'<button title="Annuller" link="Frames.php"/>'.
	'<button title="Opret" submit="true" style="Hilited"/>'.
	'</buttongroup>'.
	'</group>'.
	'</form>';
} else {
	$gui.='<message width="100%" icon="Message" xmlns="uri:Message">'.
	'<title>Kan ikke oprette ramme</title>'.
	'<description>Det er nødvendigt at der på forhånd er oprettet mindst eet '.
	'hierarki for at man kan oprette en ny ramme.'.
	'</description>'.
	'<description>Opret først et hierarki inden du opretter rammen.</description>'.
	'<buttongroup size="Large">'.
	'<button title="OK" link="Frames.php" style="Hilited"/>'.
	'</buttongroup>'.
	'</message>';
}
$gui.=
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Form","Message");
writeGui($xwg_skin,$elements,$gui);

function buildHierarchies() {
	$output="";
	$sql="select id,name from hierarchy order by name";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$output.='<option title="'.encodeXML($row['name']).'" value="'.$row['id'].'"/>';
	}
	Database::free($result);
	return $output;
}
?>