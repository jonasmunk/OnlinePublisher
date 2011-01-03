<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/InternalSession.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Utilities/StringUtils.php';

$id = Request::getInt('id',0);

$sql="select page.*,template.unique from page,template where template.id=page.template_id and page.id=".$id;
$row = Database::selectFirst($sql);
if (!$row) {
	pageNotFoundError();
	exit;
}
$language=$row['language'];
$title=$row['title'];

$close = InternalSession::getToolSessionVar('pages','rightFrame');
$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="500" top="10" align="center">'.
'<sheet width="300" object="ConfirmDelete">'.
'<message xmlns="uri:Message" icon="Caution">'.
'<title>Vil du virkelig slette siden?</title>'.
'<description>Handlingen kan ikke fortrydes og siden kan ikke gendannes!</description>'.
'<buttongroup size="Small">'.
'<button title="Annuller" link="javascript:ConfirmDelete.hide();" style="Hilited"/>'.
'<button title="Slet" link="DeletePage.php?id='.$id.'"/>'.
'</buttongroup>'.
'</message>'.
'</sheet>'.
'<titlebar title="'.StringUtils::escapeXML($title).'" icon="Web/Page">'.
'<close link="'.$close.'" help="Luk vinduet uden at gemme ændringer"/>'.
'</titlebar>'.
'<toolbar xmlns="uri:Toolbar" align="center">'.
'<tool title="Luk" icon="Basic/Close" link="'.$close.'" help="Luk vinduet uden at gemme ændringer"/>'.
'<divider/>'.
'<tool title="Rediger" icon="Basic/Edit" link="../../Template/Edit.php?id='.$id.'" target="Desktop" help="Rediger sidens indhold"/>'.
'<tool title="Vis siden" icon="Basic/View" link="../../Services/Preview/?id='.$row['id'].'&amp;return=Tools/Pages/" target="Desktop" help="Se siden"/>'.
'<tool title="Slet" icon="Basic/Delete" link="javascript: ConfirmDelete.show();" help="Slet siden"/>'.
'<flexible/>'.
'</toolbar>'.
'<content padding="5" background="true">'.
'<area xmlns="uri:Area" width="100%">'.
'<tabgroup align="center" size="Large">'.
'<tab title="Info" link="EditPage.php?id='.$id.'" help="Rediger grundlæggende egenskaber for siden"/>'.
'<tab title="Sprog" link="EditPageTranslations.php?id='.$id.'" help="Tilknyt oversættelser på andre sprog"/>'.
'<tab title="Sikkerhed" style="Hilited" help="Opsæt adgang til siden"/>'.
'</tabgroup>'.
'<toolbar xmlns="uri:Toolbar" align="center">'.
'<tool title="Gem" icon="Basic/Save" link="javascript: document.forms.Formula.submit();" help="Gem ændringer foretaget"/>'.
'</toolbar>'.
'<content padding="3">'.
'<form xmlns="uri:Form" name="Formula" action="UpdatePageSecurityZones.php" method="post">'.
'<hidden name="id">'.$id.'</hidden>'.
'<list xmlns="uri:List" width="100%">'.
'<content>'.
'<headergroup>'.
'<header title="" width="1%"/>'.
'<header title="Beskyttede områder" width="100%"/>'.
'</headergroup>';
// First collect all relations
$zones = array();
$sql="select securityzone_id from securityzone_page where page_id=".$id;
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$zones[] = $row['securityzone_id'];
}
Database::free($result);

$sql="select id,title from object where type='securityzone' order by title";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$gui.='<row>'.
	'<cell><checkbox name="zones[]" value="'.$row['id'].'" selected="'.(in_array($row['id'],$zones) ? 'true' : 'false').'"/></cell>'.
	'<cell><icon icon="Zone/Security"/><text>'.StringUtils::escapeXML($row['title']).'</text></cell>'.
	'</row>';
}
Database::free($result);
$gui.=
'</content>'.
'</list>'.
'</form>'.
'</content>'.
'</area>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","List","Area","Form","Message");
writeGui($xwg_skin,$elements,$gui);


function pageNotFoundError() {
	global $xwg_skin;
	$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
	'<interface background="Desktop">'.
	'<window xmlns="uri:Window" width="300" align="center">'.
	'<titlebar title="Fejl">'.
	'<close link="PagesFrame.php"/>'.
	'</titlebar>'.
	'<content background="true">'.
	'<message xmlns="uri:Message" icon="Caution">'.
	'<title>Siden findes ikke mere!</title>'.
	'<description>Den side dette menupunkt peger på findes ikke længere</description>'.
	'<buttongroup size="Large">'.
	'<button title="OK" link="PagesFrame.php"/>'.
	'</buttongroup>'.
	'</message>'.
	'</content>'.
	'</window>'.
	'</interface>'.
	'</xmlwebgui>';

$elements = array("Window","Message");
writeGui($xwg_skin,$elements,$gui);
}
?>