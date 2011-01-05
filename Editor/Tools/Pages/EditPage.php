<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Database.php';
require_once '../../Classes/InternalSession.php';
require_once '../../Classes/Page.php';
require_once '../../Classes/BumbleBee.php';
require_once '../../Classes/DevelopmentMode.php';
require_once '../../Classes/GuiUtils.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Utilities/StringUtils.php';

$id = Request::getInt('id',0);

$page = Page::load($id);

if (!$page) {
	pageNotFoundError();
	exit;
}

$designs=buildDesigns();
$frames=buildFrames();
$pages=GuiUtils::buildPageOptions();

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
'<titlebar title="'.StringUtils::escapeXML($page->getTitle()).'" icon="Web/Page">'.
'<close link="'.$close.'" help="Luk vinduet uden at gemme ændringer"/>'.
'</titlebar>'.
'<toolbar xmlns="uri:Toolbar" align="center">'.
'<tool title="Luk" icon="Basic/Close" link="'.$close.'" help="Luk vinduet uden at gemme ændringer"/>'.
'<divider/>'.
'<tool title="Rediger" icon="Basic/Edit" link="../../Template/Edit.php?id='.$id.'" target="Desktop" help="Rediger sidens indhold"/>'.
'<tool title="Vis siden" icon="Basic/View" link="../../Services/Preview/?id='.$id.'&amp;return=Tools/Pages/" target="Desktop" help="Se siden"/>'.
'<tool title="Slet" icon="Basic/Delete" link="javascript: ConfirmDelete.show();" help="Slet siden"/>'.
'<flexible/>'.
'<tool title="Avanceret" icon="Tool/System" overlay="DropDown">'.
'<menu xmlns="uri:Menu">'.
'<item title="Opret nyhed" link="../News/?action=newnews&amp;page='.$id.'" target="Desktop"/>'.
'<item title="Eksporter" link="Export.php?id='.$id.'"/>';
if (DevelopmentMode::isDevelopmentMode()) {
	$gui.='<item title="Eksporter (debug)" link="Export.php?id='.$id.'&amp;debug=true" target="_blank"/>'.
	'<item title="Opret kopi" link="Duplicate.php?id='.$id.'"/>';
}
if (BumbleBee::isConfigured()) {
	$gui.='<item title="Vis som PDF" link="../../../util/pages/preview/?id='.$id.'&amp;format=pdf&amp;'.$page->getChanged().'" target="_blank"/>';
}
$gui.=
'</menu>'.
'</tool>'.
'</toolbar>'.
'<content padding="5" background="true">'.
'<area xmlns="uri:Area" width="100%">'.
'<tabgroup align="center" size="Large">'.
'<tab title="Info" style="Hilited" help="Rediger grundlæggende egenskaber for siden"/>'.
'<tab title="Sprog" link="EditPageTranslations.php?id='.$id.'" help="Tilknyt oversættelser på andre sprog"/>'.
'<tab title="Sikkerhed" link="EditPageSecurity.php?id='.$id.'" help="Opsæt adgang til siden"/>'.
'</tabgroup>'.
'<content padding="5">'.
'<layout xmlns="uri:Layout" width="100%"><row>';
if (BumbleBee::isConfigured()) {
	$gui.='<cell width="120" valign="top">'.
	'<html xmlns="uri:Html">'.
	'<div id="preview" style="width: 120px; border: 1px solid #ccc; background-repeat: no-repeat; visibility: hidden; cursor: pointer;" onclick="window.parent.location.href=\'../../Services/Preview/?id='.$id.'&amp;return=Tools/Pages/\'">'.
	'</div>'.
	'<script type="text/javascript">
	var img = new Image();
	img.src = \'../../../util/pages/preview/?id='.$id.'&amp;format=png&amp;width=120&amp;'.$page->getChanged().'\';
	img.onload = function() {
		if (this.height&lt;160) {
			$id("preview").style.height = this.height+"px";
		} else {
			$id("preview").style.height = "160px";
		}
		$id("preview").style.backgroundImage = "url(\'../../../util/pages/preview/?id='.$id.'&amp;format=png&amp;width=120&amp;'.$page->getChanged().'\')";
		$id("preview").style.visibility = "visible";
	}
	</script>'.
	'</html>'.
	'</cell>';
}
$gui.=
'<cell>'.
'<form xmlns="uri:Form" action="UpdatePage.php" method="post" name="Formula" focus="title">'.
'<hidden name="id">'.$id.'</hidden>'.
'<group size="Large" badgewidth="30%">'.
'<textfield badge="Titel:" name="title">'.
StringUtils::escapeXML($page->getTitle()).
'</textfield>'.
'<textfield badge="Beskrivelse:" name="description" lines="4">'.
StringUtils::escapeXML($page->getDescription()).
'</textfield>';
if ($item = $page->getHierarchyItem()) {
    $gui.=
    '<space/>'.
    '<hidden name="hierarchyItemId">'.$item['id'].'</hidden>'.
    '<textfield name="hierarchyItemTitle" badge="Menupunkt:">'.StringUtils::escapeXML($item['title']).'</textfield>'.
    '<buttongroup size="Small">'.
    '<button title="Rediger..." link="EditHierarchyItem.php?id='.$item['id'].'&amp;return='.urlencode('EditPage.php?id='.$id).'"/>'.
    '</buttongroup>';
}
$gui.=
'<disclosure label="Avanceret:">'.
'<checkbox badge="Inaktiv:" name="disabled" selected="'.($page->getDisabled() ? 'true' : 'false').'"/>';
$gui.=
'<space/>'.
'<select badge="Design:" name="design" selected="'.$page->getDesignId().'">'.
$designs.
'</select>'.
'<select badge="Opsætning:" name="frame" selected="'.$page->getFrameId().'">'.
$frames.
'</select>'.
'<select badge="Sprog:" name="language" selected="'.$page->getLanguage().'">'.
'<option value="" title=""/>';
$languages = GuiUtils::getLanguages();
while ($language = current($languages)) {
    $gui.='<option value="'.key($languages).'" title="'.$language.'"/>';
    next($languages);
}
$gui.=
'</select>'.
'<space/>'.
'<checkbox badge="Søgbar:" name="searchable" selected="'.($page->getSearchable() ? 'true' : 'false').'"/>'.
'<textfield badge="Nøgleord:" name="keywords" lines="2">'.StringUtils::escapeXML($page->getKeywords()).'</textfield>'.
'<textfield badge="Sti:" name="path">'.StringUtils::escapeXML($page->getPath()).'</textfield>'.
'<space/>'.
'<select badge="Næste side:" name="nextPage" selected="'.$page->getNextPage().'">'.
'<option value="" title=""/>'.
$pages.
'</select>'.
'<select badge="Forrige side:" name="previousPage" selected="'.$page->getPreviousPage().'">'.
'<option value="" title=""/>'.
$pages.
'</select>'.
'</disclosure>'.
'<buttongroup size="Large">'.
'<button title="Annuller" link="'.$close.'" help="Luk vinduet uden at gemme ændringer"/>'.
'<button title="Opdater" submit="true" style="Hilited" help="Gem foretagede ændringer"/>'.
'</buttongroup>'.
'</group>'.
'</form>'.
'</cell></row></layout>'.
'</content>'.
'</area>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Form","Area","Message","Layout","Html","Menu");
writeGui($xwg_skin,$elements,$gui);


function buildDesigns() {
	$output="";
	$sql="select id,title from object where type='design' order by title";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$output.='<option title="'.StringUtils::escapeXML($row['title']).'" value="'.$row['id'].'"/>';
	}
	Database::free($result);
	return $output;
}

function buildFrames() {
	$output="";
	$sql="select id,name from frame order by name";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$output.='<option title="'.StringUtils::escapeXML($row['name']).'" value="'.$row['id'].'"/>';
	}
	Database::free($result);
	return $output;
}

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