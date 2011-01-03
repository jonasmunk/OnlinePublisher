<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Organisation
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/InternalSession.php';
require_once '../../Classes/Persongroup.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Utilities/StringUtils.php';

require_once 'Functions.php';

if (Request::exists('id')) {
	setPersonGroup(Request::getInt('id',0));
}
$id = getPersonGroup();

$view = InternalSession::getRequestToolSessionVar('organisation','view','view','list');

if ($view=='list') {
	$iframeSource = 'PersonList.php';
}
else {
	$iframeSource = 'PersonIcons.php';
}

$group = PersonGroup::load($id);
InternalSession::setToolSessionVar('organisation','baseWindow','Persongroup.php');

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="100%" height="100%">'.
'<titlebar title="'.StringUtils::escapeXML($group->getTitle()).'" icon="Element/Folder">'.
'<close link="Library.php"/>'.
'</titlebar>'.
'<toolbar xmlns="uri:Toolbar" align="left">'.
'<viewgroup title="Visning">';
if ($view=='list')
	$gui.='<view type="List" style="Hilited"/>';
else
	$gui.='<view type="List" link="Persongroup.php?view=list"/>';
if ($view=='icon')
	$gui.='<view type="Icon" style="Hilited"/>';
else
	$gui.='<view type="Icon" link="Persongroup.php?view=icon"/>';
$gui.=
'</viewgroup>'.
'<divider/>'.
'<tool title="Ny person" icon="Element/Person" overlay="New" link="NewPerson.php"/>'.
'<tool title="Tilf&#248;j til gruppe" icon="Element/Folder" overlay="Add" link="AddToPersongroup.php"/>'.
($view=='list' ?
'<tool title="Fjern fra gruppe" icon="Element/Folder" overlay="Remove" link="javascript: List.getDocument().forms[0].submit();"/>'
: '').
'<flexible/>'.
'<tool title="Egenskaber" icon="Element/Folder" overlay="Info" link="PersongroupProperties.php"/>'.
'</toolbar>'.
'<content valign="top">'.
'<iframe xmlns="uri:Frame" source="'.$iframeSource.'" object="List"/>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Frame");
writeGui($xwg_skin,$elements,$gui);
?>