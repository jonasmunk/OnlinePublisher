<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Session.php';
require_once '../../Include/International.php';

$id = requestGetNumber('id',0);

$sql="select page.*,template.unique from page,template where template.id=page.template_id and page.id=".$id;
$row = Database::selectFirst($sql);
if (!$row) {
	pageNotFoundError();
	exit;
}
$language=$row['language'];
$title=$row['title'];

$close = getToolSessionVar('pages','rightFrame');
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
'<titlebar title="'.encodeXML($title).'" icon="Web/Page">'.
'<close link="'.$close.'" help="Luk vinduet uden at gemme ændringer"/>'.
'</titlebar>'.
'<toolbar xmlns="uri:Toolbar" align="center">'.
'<tool title="Luk" icon="Basic/Close" link="'.$close.'" help="Luk vinduet uden at gemme ændringer"/>'.
'<divider/>'.
'<tool title="Rediger" icon="Basic/Edit" link="../../Template/Edit.php?id='.$id.'" target="Desktop" help="Rediger sidens indhold"/>'.
'<tool title="Vis siden" icon="Basic/View" link="../../Services/Preview/?id='.$row['id'].'&amp;return=Tools/Pages/" target="Desktop" help="Se siden"/>'.
'<tool title="Slet" icon="Basic/Delete" link="javascript:ConfirmDelete.show();" help="Slet siden"/>'.
'<flexible/>'.
'</toolbar>'.
'<content padding="5" background="true">'.
'<area xmlns="uri:Area" width="100%">'.
'<tabgroup align="center" size="Large">'.
'<tab title="Info" link="EditPage.php?id='.$id.'" help="Rediger grundlæggende egenskaber for siden"/>'.
'<tab title="Sprog" style="Hilited" help="Tilknyt oversættelser på andre sprog"/>'.
'<tab title="Sikkerhed" link="EditPageSecurity.php?id='.$id.'" help="Opsæt adgang til siden"/>'.
'</tabgroup>'.
'<toolbar xmlns="uri:Toolbar" align="center">'.
'<tool title="Tilknyt oversættelse" icon="Basic/Attach" link="NewPageTranslation.php?id='.$id.'" help="Tilknyt versioner af siden på andre sprog"/>'.
'</toolbar>'.
'<content padding="3">'.
'<list xmlns="uri:List" width="100%">'.
'<content>'.
'<headergroup>'.
'<header title="Side" width="100%"/>'.
'<header title="Sprog" width="1%"/>'.
'<header title="" width="1%"/>'.
'</headergroup>';
$sql="select page_translation.id,page.title,page.language from page,page_translation where page.id=page_translation.translation_id and page_translation.page_id=".$id;
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$gui.='<row>'.
	'<cell>'.encodeXML($row['title']).'</cell>'.
	'<cell>'.encodeXML($row['language']).'</cell>'.
	'<cell><icon icon="Basic/Delete" link="DeletePageTranslation.php?id='.$row['id'].'&amp;page='.$id.'"/></cell>'.
	'</row>';
}
Database::free($result);
$gui.=
'</content>'.
'</list>'.
'</content>'.
'</area>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","List","Area","Message");
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