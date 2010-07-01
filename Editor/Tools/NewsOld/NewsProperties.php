<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/News.php';
require_once '../../Classes/Newsgroup.php';
require_once '../../Classes/Image.php';
require_once '../../Classes/GuiUtils.php';
require_once 'NewsController.php';

$id = requestGetNumber('id',0);
$close = NewsController::getBaseWindow();

$news = News::load($id);
$groupIds = $news->getGroupIds();
$groups = NewsGroup::search();

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="500" align="center" top="20">'.
'<titlebar title="'.encodeXML(shortenString($news->getTitle(),30)).'" icon="Part/News">'.
'<close link="'.$close.'"/>'.
'</titlebar>'.
'<tabgroup size="Large">'.
'<tab title="Egenskaber" style="Hilited"/>'.
'<tab title="Links" link="NewsLinks.php?id='.$id.'"/>'.
'</tabgroup>'.
'<content padding="10" background="true" valign="top">'.
'<form xmlns="uri:Form" action="UpdateNews.php" method="post" name="Formula" focus="title">'.
'<validation>
if (Title.isEmpty()) {
	Title.setError("Skal udfyldes!");
	Title.blinkError(1000);
	Title.focus();
	return false;
}
else {
	Title.setError("");
}
if (EndDateCheck.isSelected() &amp;&amp; StartDateCheck.isSelected()) {
	if (parseInt(StartDate.getValue()) &gt;= parseInt(EndDate.getValue())) {
		EndDate.setError("Slutdatoen skal være større end startdatoen!");
		EndDate.blinkError(1000);
		return false;
	}
	else {
		EndDate.setError("");
	}
}
else {
	EndDate.setError("");
}
return true;
</validation>'.
'<hidden name="id">'.$id.'</hidden>'.
'<group size="Large" badgeplacement="above">'.
'<textfield badge="Titel:" name="title" object="Title">'.encodeXML($news->getTitle()).'</textfield>'.
'<textfield badge="Resume:" name="description" lines="6">'.encodeXML($news->getNote()).'</textfield>'.
'<object badge="Billede:" name="image" empty="true">';
	if ($news->getImageId()>0) {
		$gui.=GuiUtils::buildImageEntity(Image::load($news->getImageId()));
	}
	$gui.=
	'<translation choose="Vælg" remove="Fjern" none="Intet valgt"/>'.
	'<source list="ImagePicker.php"/>'.
'</object>'.
'</group>'.
'<group size="Large">'.
'<datetime badge="Start dato:" name="startdate" object="StartDate" value="'.xwgTimeStamp2dateTime($news->getStartdate()).'" disabled="'.(is_numeric($news->getStartdate()) ? 'false' : 'true').'" display="dmy">'.
'<check name="startdateCheck" selected="'.(is_numeric($news->getStartdate()) ? 'true' : 'false').'" autoenable="StartDate" object="StartDateCheck"/>'.
'</datetime>'.
'<datetime badge="Slut dato:" name="enddate" object="EndDate" value="'.xwgTimeStamp2dateTime($news->getEnddate()).'" disabled="'.(is_numeric($news->getEnddate()) ? 'false' : 'true').'" display="dmy">'.
'<check name="enddateCheck" selected="'.(is_numeric($news->getEnddate()) ? 'true' : 'false').'" autoenable="EndDate" object="EndDateCheck"/>'.
'</datetime>'.
'<checkbox badge="Søgbar:" name="searchable" selected="'.($news->isSearchable() ? 'true' : 'false').'"/>'.
'<space/>'.
'<disclosure label="Grupper">'.
'<select badge="Grupper:" lines="5" name="groups[]" multiple="true">';
foreach ($groups as $group) {
	$gui.='<option title="'.encodeXML($group->getTitle()).'" value="'.encodeXML($group->getId()).'" selected="'.(in_array($group->getId(),$groupIds) ? 'true' : 'false').'"/>';
}

$gui.='</select>'.
'</disclosure>'.
'<buttongroup size="Large">'.
'<button title="Slet" link="DeleteNews.php?id='.$id.'"/>'.
'<button title="Annuller" link="'.$close.'"/>'.
'<button title="Udgiv"'.
(!$news->isPublished()
? ' link="PublishNews.php?id='.$id.'&amp;return=NewsProperties.php"'
: ' style="Disabled"'
).'/>'.
'<button title="Opdater" submit="true" style="Hilited"/>'.
'</buttongroup>'.
'</group>'.
'</form>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Form","Layout","Area","Html");
writeGui($xwg_skin,$elements,$gui);
?>