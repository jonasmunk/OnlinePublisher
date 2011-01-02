<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/InternalSession.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Services/TemplateService.php';
require_once 'PagesController.php';

PagesController::setActiveItem('allpages');
InternalSession::setToolSessionVar('pages','rightFrame','PagesFrame.php');
if (Request::exists('freetext')) {
	InternalSession::setToolSessionVar('pages','freeTextSearch',requestPostText('freetext'));
}
else if (Request::exists('searchPairKey')) {
	InternalSession::setToolSessionVar('pages','freeTextSearch','');
	PagesController::setSearchPair(requestGetText('searchPairKey'),requestGetText('searchPairValue'));
}
$freetext = InternalSession::getToolSessionVar('pages','freeTextSearch');
$searchPair = PagesController::getSearchPair();
if ($searchPair[0]=='' || $searchPair[0]=='allPages') {
	$title="Alle sider";
}
else if ($searchPair[0]=='noHierarchyItem') {
	$title="Sider uden menupunkt";
}
else if ($searchPair[0]=='noSecurityZone') {
	$title="Sider uden sikkerhedszone";
}
else if ($searchPair[0]=='securityZone') {
	$sql = "select title from object where id=".$searchPair[1];
	$row = Database::selectFirst($sql);
	$title='Sider i sikkerhedszonen "'.$row['title'].'"';
}
else if ($searchPair[0]=='hierarchy') {
	$sql = "select * from hierarchy where id=".$searchPair[1];
	$row = Database::selectFirst($sql);
	$title='Sider i hierarkiet "'.$row['name'].'"';
}
else if ($searchPair[0]=='frame') {
	$sql = "select * from frame where id=".$searchPair[1];
	$row = Database::selectFirst($sql);
	$title='Rammen "'.$row['name'].'"';
}
else if ($searchPair[0]=='template') {
	$sql = "select * from template where id=".$searchPair[1];
	$row = Database::selectFirst($sql);
	$templates = TemplateService::getTemplatesKeyed();
	$title='Skabelonen "'.$templates[$row['unique']]['name'].'"';
}
else {
	$title=$searchPair[0]."/".$searchPair[1];
}
$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="100%" height="100%">'.
'<titlebar title="Alle sider" icon="Template/Generic"/>'.
'<toolbar xmlns="uri:Toolbar" align="left">'.
'<tool title="Ny side" icon="Template/Generic" overlay="New" link="NewPageTemplate.php?reset=true" help="Opret en ny side"/>';
$gui.=
'<flexible/>'.
'<searchfield title="Søgning" width="120" focus="true" name="freetext" method="post" value="'.encodeXML($freetext).'" action="PagesFrame.php"/>'.
'</toolbar>'.
'<content valign="top">'.
'<iframe xmlns="uri:Frame" source="Result.php" name="Pages"/>'.
'</content>'.
'</window>'.
'<internalscript xmlns="uri:Script" source="Frame.js"/>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Frame","Script");
writeGui($xwg_skin,$elements,$gui);
?>