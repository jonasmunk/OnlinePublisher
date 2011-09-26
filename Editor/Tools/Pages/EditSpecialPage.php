<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Core/Database.php';
require_once '../../Classes/Utilities/GuiUtils.php';
require_once '../../Classes/Core/Request.php';
require_once '../../Classes/Utilities/StringUtils.php';

$id=Request::getInt('id',0);

$sql="select * from specialpage where id=".$id;
$row = Database::selectFirst($sql);
$type=$row['type'];
$page=$row['page_id'];
$language=$row['language'];
$pages = buildPages();
$sql="select * from page where design_id=".$id;

$languages = GuiUtils::getLanguages();

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" align="center" top="30">'.
'<titlebar title="Redigering af speciel side" icon="Web/Page">'.
'<close link="SpecialPages.php"/>'.
'</titlebar>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="UpdateSpecialPage.php" method="post" name="Formula" focus="name">'.
'<hidden name="id">'.$id.'</hidden>'.
'<group size="Large">'.
'<select badge="Type:" name="type" selected="'.$type.'">'.
buildTypes().
'</select>'.
'<select badge="Sprog:" name="language" selected="'.$language.'">'.
'<option value="" title="Alle"/>'.
xwgBuildOptions($languages).
'</select>'.
'<select badge="Side:" name="page" selected="'.$page.'">'.
$pages.
'</select>'.
'<buttongroup size="Large">'.
'<button title="Slet" link="DeleteSpecialPage.php?id='.$id.'"/>'.
'<button title="Annuller" link="SpecialPages.php"/>'.
'<button title="Opdater" submit="true" style="Hilited"/>'.
'</buttongroup>'.
'</group>'.
'</form>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Form");
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