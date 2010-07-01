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
require_once 'NewsController.php';

$id = requestGetNumber('id',0);
$close = NewsController::getBaseWindow();


$news = News::load($id);



$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="500" align="center" top="20">'.
'<titlebar title="'.encodeXML(shortenString($news->getTitle(),20)).'" icon="Part/News">'.
'<close link="'.$close.'"/>'.
'</titlebar>'.
'<tabgroup size="Large">'.
'<tab title="Egenskaber" link="NewsProperties.php?id='.$id.'"/>'.
'<tab title="Links" style="Hilited"/>'.
'</tabgroup>'.
'<toolbar xmlns="uri:Toolbar" align="center">'.
'<tool title="Nyt link" icon="Web/Link" overlay="New" link="NewNewsLink.php?id='.$id.'"/>'.
'</toolbar>'.
'<content padding="5" valign="top">'.
'<list xmlns="uri:List" width="100%">'.
'<content>'.
'<headergroup>'.
'<header title="Tekst" width="30%"/>'.
'<header title="Link"/>'.
'<header title="" width="1%"/>'.
'</headergroup>';

$sql="select * from object_link where object_id=".$id." order by position";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	if ($row['target_type']=='page') {
		$icon = 'Web/Page';
		$text = getPageTitle($row['target_value']);
		if ($text==NULL) {
			$text = 'Siden findes ikke mere!';
			$icon = 'Basic/Close';
		}
	}
	else if ($row['target_type']=='file') {
		$icon = 'File/Generic';
		$text = getFileTitle($row['target_value']);
		if ($text==NULL) {
			$text = 'Filen findes ikke mere!';
			$icon = 'Basic/Close';
		}
	}
	else if ($row['target_type']=='url') {
		$icon = 'Element/InternetAddress';
		$text = $row['target_value'];
	}
	else if ($row['target_type']=='email') {
		$icon = 'Element/EmailAddress';
		$text = $row['target_value'];
	}
	$gui.='<row link="EditNewsLink.php?id='.$row['id'].'">'.
	'<cell>'.
	'<icon icon="Web/Link"/>'.
	'<text>'.encodeXML($row['title']).'</text>'.
	'</cell>'.
	'<cell>'.
	'<icon icon="'.$icon.'"/>'.
	'<text>'.encodeXML(shortenString($text,40)).'</text>'.
	'</cell>'.
	'<cell>'.
	'<direction direction="Up" link="MoveNewsLink.php?id='.$row['id'].'&amp;dir=-1&amp;news='.$id.'"/>'.
	'<direction direction="Down" link="MoveNewsLink.php?id='.$row['id'].'&amp;dir=1&amp;news='.$id.'"/>'.
	'</cell>'.
	'</row>';
}
Database::free($result);

$gui.=
'</content>'.
'</list>'.
'<group xmlns="uri:Button" size="Large" align="right" top="6">'.
'<button title="Slet" link="DeleteNews.php?id='.$id.'"/>'.
'<button title="Annuller" link="'.$close.'"/>'.
'<button title="Udgiv"'.
(!$news->isPublished()
? ' link="PublishNews.php?id='.$id.'&amp;return=NewsLinks.php"'
: ' style="Disabled"'
).'/>'.
'</group>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","List","Button");
writeGui($xwg_skin,$elements,$gui);

function getPageTitle($id) {
	$output=NULL;
	$sql = "select title from page where id=".$id;
	if ($row = Database::selectFirst($sql)) {
		$output = $row['title'];
	}
	return $output;
}

function getFileTitle($id) {
	$output=NULL;
	$sql = "select title from object where type='file' and id=".$id;
	if ($row = Database::selectFirst($sql)) {
		$output = $row['title'];
	}
	return $output;
}
?>