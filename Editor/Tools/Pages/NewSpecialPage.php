<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Database.php';
require_once '../../Classes/Utilities/GuiUtils.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Utilities/StringUtils.php';

$pages = buildPages();

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" align="center" top="30">'.
'<titlebar title="Ny speciel side" icon="Web/Page">'.
'<close link="SpecialPages.php"/>'.
'</titlebar>'.
'<content padding="5" background="true">';
if (strlen($pages)>0) {
	$languages = GuiUtils::getLanguages();
	$gui.='<form xmlns="uri:Form" action="CreateSpecialPage.php" method="post" name="Formula" focus="name">'.
	'<group size="Large">'.
	'<select badge="Type:" name="type">'.
	buildTypes().
	'</select>'.
	'<select badge="Sprog:" name="language">'.
	'<option value="" title="Alle"/>'.
	xwgBuildOptions($languages).
	'</select>'.
	'<select badge="Side:" name="page">'.
	$pages.
	'</select>'.
	'<buttongroup size="Large">'.
	'<button title="Annuller" link="SpecialPages.php"/>'.
	'<button title="Opret" submit="true" style="Hilited"/>'.
	'</buttongroup>'.
	'</group>'.
	'</form>';
} else {
	$gui.='<message width="100%" icon="Message" xmlns="uri:Message">'.
	'<title>Kan ikke oprette specielle sider</title>'.
	'<description>Da der ikke findes nogle sider i systemet er det ikke muligt at oprette en speciel side'.
	'</description>'.
	'<description>Opret først en side inden du opretter en speciel side.</description>'.
	'<buttongroup size="Large">'.
	'<button title="OK" link="SpecialPages.php" style="Hilited"/>'.
	'</buttongroup>'.
	'</message>';
}
$gui.=
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Message","Form");
writeGui($xwg_skin,$elements,$gui);

function buildPages() {
	$output="";

	$sql="select page.id,page.title from page order by page.title";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$output.='<option title="'.StringUtils::escapeXML($row['title']).'" value="'.$row['id'].'"/>';
	}
	Database::free($result);
	return $output;
}

function buildTypes() {
	return '<option title="Forside" value="home"/>'.
	'<option title="Intern fejl" value="internalerror"/>';
}
?>