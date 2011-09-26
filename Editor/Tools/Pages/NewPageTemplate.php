<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Core/InternalSession.php';
require_once '../../Classes/Core/Request.php';
require_once '../../Classes/Services/TemplateService.php';
require_once '../../Classes/Utilities/StringUtils.php';

require_once 'PagesController.php';

if (Request::getBoolean('reset')) {
	PagesController::resetNewPageInfo();
}

$info = PagesController::getNewPageInfo();

if (Request::exists('hierarchy') && Request::exists('parent')) {
	$info['fixedHierarchy']=Request::getInt('hierarchy');
	$info['fixedHierarchyParent']=Request::getInt('parent');
}
PagesController::setNewPageInfo($info);

$close = InternalSession::getToolSessionVar('pages','rightFrame');

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="500" align="center">'.
'<titlebar title="Ny side" icon="Tool/Assistant">'.
'<close link="'.$close.'" help="Afbryd oprettelse af den nye side"/>'.
'</titlebar>'.
'<content padding="5" background="true">'.
'<layout xmlns="uri:Layout" width="100%">'.
'<row><cell valign="top">'.
'<group xmlns="uri:Icon" size="1" titles="right" spacing="6" wrapping="false">'.
'<row><icon icon="Element/Template" title="Vælg skabelon" style="Hilited"/></row>'.
'<row><icon icon="Basic/Color" title="Vælg design"/></row>'.
'<row><icon icon="Web/Frame" title="Vælg opsætning"/></row>';
if ($info['fixedHierarchy']==0) {
	$gui.='<row><icon icon="Element/Structure" title="Menupunkt"/></row>';
}
$gui.=
'<row><icon icon="Basic/Info" title="Angiv egenskaber"/></row>'.
'</group>'.
'</cell><cell width="99%">'.
'<area xmlns="uri:Area" width="100%"><content padding="10">'.
'<text xmlns="uri:Text" align="center" bottom="5">'.
'<strong>Vælg skabelon</strong>'.
'<break/><small>Klik på den skabelon du vil bruge til den nye side</small>'.
'</text>'.
'<overflow xmlns="uri:Layout" height="300">'.
'<group xmlns="uri:Icon" size="3" titles="right" spacing="3" wrapping="true">';

$templates = TemplateService::getTemplatesSorted();
foreach ($templates as $template) {
	if ($template['status']=='active') {
		$gui.='<row>'.
		'<icon'.
		' link="NewPageDesign.php?template='.$template['id'].'"'.
		' icon="'.$template['icon'].'"'.
		' title="'.StringUtils::escapeXML($template['name']).'"'.
		' description="'.StringUtils::escapeXML($template['description']).'"'.
		($template['id']==$info['template'] ? ' style="Hilited"' : '').
		'/>'.
		'</row>';
	}
}

$gui.=
'</group>'.
'</overflow>'.
'</content></area>'.
'</cell></row>'.
'<row><cell colspan="2">'.
'<group size="Large" xmlns="uri:Button" align="right" top="5">'.
'<button title="Annuller" link="'.$close.'" help="Afbryd oprettelse af den nye side"/>'.
'<button title="Forrige" style="Disabled"/>'.
($info['template']>0
? '<button title="Næste" link="NewPageDesign.php" help="Gå videre til næste punkt"/>'
: '<button title="Næste" style="Disabled"/>'
).
'</group>'.
'</cell></row>'.
'</layout>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Area","Layout","Icon","Text","Button");
writeGui($xwg_skin,$elements,$gui);
?>