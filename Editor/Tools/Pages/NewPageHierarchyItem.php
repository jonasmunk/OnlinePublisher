<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Core/Database.php';
require_once '../../Classes/Core/InternalSession.php';
require_once '../../Classes/Utilities/GuiUtils.php';
require_once '../../Classes/Core/Request.php';
require_once '../../Classes/Services/TemplateService.php';
require_once '../../Classes/Utilities/StringUtils.php';

require_once 'PagesController.php';

$templates = TemplateService::getTemplatesKeyed();

$info = PagesController::getNewPageInfo();
if (Request::exists('frame')) {
	$frame = Request::getInt('frame');
	if ($frame!=$info['frame']) {
		// If new frame reset hierarchyitem
		$info['hierarchy']=0;
		$info['hierarchyParent']=0;
	}
	$info['frame']=$frame;
}
PagesController::setNewPageInfo($info);

$close = InternalSession::getToolSessionVar('pages','rightFrame');
$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="500" height="300" align="center">'.
'<titlebar title="Ny side" icon="Tool/Assistant">'.
'<close link="'.$close.'" help="Afbryd oprettelse af den nye side"/>'.
'</titlebar>'.
'<content padding="5" background="true">'.
'<layout xmlns="uri:Layout" width="100%" height="100%">'.
'<row><cell valign="top">'.
'<group xmlns="uri:Icon" size="1" titles="right" spacing="6" wrapping="false">'.
'<row><icon icon="Element/Template" title="Vælg skabelon" style="Disabled"/></row>'.
'<row><icon icon="Basic/Color" title="Vælg design" style="Disabled"/></row>'.
'<row><icon icon="Web/Frame" title="Vælg opsætning" style="Disabled"/></row>'.
'<row><icon icon="Element/Structure" title="Menupunkt" style="Hilited"/></row>'.
'<row><icon icon="Basic/Info" title="Angiv egenskaber"/></row>'.
'</group>'.
'</cell><cell width="99%">'.
'<area xmlns="uri:Area" width="100%" height="100%"><content padding="10">'.
'<layout xmlns="uri:Layout" width="100%" height="100%">'.
'<row><cell valign="top">'.
'<text xmlns="uri:Text" align="center" bottom="5">'.
'<strong>Opret menupunkt</strong>'.
'<break/><small>Klik på det menupunkt den nye side skal være et underpunkt til.<break/>Vælg &#34;Intet menupunkt&#34; hvis siden ikke skal have et menupunkt.</small>'.
'</text>'.
'<overflow xmlns="uri:Layout" height="250">';

$sql = "select hierarchy.id,hierarchy.name from hierarchy,frame where frame.hierarchy_id=hierarchy.id and frame.id=".$info['frame'];
$row = Database::selectFirst($sql);
$gui.=
'<hierarchy xmlns="uri:Hierarchy" persistence="true" unique="Tools.Pages.NewPage.Hierarchy.'.$row['id'].'">'.
'<element icon="Element/Structure" title="'.StringUtils::escapeXML($row['name']).'" link="NewPageProperties.php?hierarchy='.$row['id'].'&amp;parent=0" open="true">'.
buildHier($row['id'],0,$info['hierarchyParent']).
'</element>';

$gui.=
'</hierarchy>'.
'</overflow>'.
'</cell></row><row><cell valign="bottom">'.
'<group xmlns="uri:Button" align="right" size="Small" top="5">'.
'<button title="Intet menupunkt" link="NewPageProperties.php?hierarchy=0&amp;parent=0" help="Klik her hvis siden ikke skal have et menupunkt"/>'.
'</group>'.
'</cell></row></layout>'.
'</content></area>'.
'</cell></row>'.
'<row><cell colspan="2">'.
'<group size="Large" xmlns="uri:Button" align="right" top="5">'.
'<button title="Annuller" link="'.$close.'" help="Afbryd oprettelse af den nye side"/>'.
'<button title="Forrige" link="NewPageFrame.php" help="Gå tilbage til forrige punkt"/>'.
'<button title="Næste" link="NewPageProperties.php" help="Gå videre til næste punkt"/>'.
'</group>'.
'</cell></row>'.
'</layout>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Area","Layout","Icon","Text","Hierarchy","Button");
writeGui($xwg_skin,$elements,$gui);


function buildHier($id,$parent,$selected) {
	global $templates;
	$output="";
	$sql="select hierarchy_item.*,page.id as pageid,page.title as pagetitle,template.unique as templateunique,file.object_id as fileid,file.filename from hierarchy_item left join page on page.id = hierarchy_item.target_id left join template on template.id=page.template_id left join file on file.object_id = hierarchy_item.target_id where hierarchy_item.parent=".$parent." and hierarchy_item.hierarchy_id=".$id." order by `index`";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$output.=
		'<element icon="'.GuiUtils::getLinkIcon($row['target_type'],$row['templateunique'],$row['filename']).'" title="'.StringUtils::escapeXML($row['title']).'" link="NewPageProperties.php?hierarchy='.$row['hierarchy_id'].'&amp;parent='.$row['id'].'"'.
		($row['id']==$selected ? ' style="Hilited"' : '').
		'>'.
		buildHier($id,$row['id'],$selected).
		'</element>';
	}
	Database::free($result);
	return $output;
}
?>