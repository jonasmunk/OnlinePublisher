<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once "../../Libraries/lastrss/lastRSS.php"; 
require_once 'Functions.php';

$rss = new lastRSS;
// Set cache dir and cache time limit (1200 seconds) 
// (don't forget to chmod cahce dir to 777 to allow writing) 
$rss->cache_dir = $basePath.'cache/rss/';
$rss->cache_time = 1200;
$rss->cp = 'US-ASCII';
$rss->date_format = 'd-m-y';

// Try to load and parse RSS file of Slashdot.org 
$rssurl = requestGetText('url');

if ($rs = $rss->get($rssurl)) {
	$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
	'<interface>'.
	'<list xmlns="uri:List" width="100%" margin="3" sort="true">'.
	'<content>'.
	'<headergroup>'.
	'<header title="Titel"/>'.
	'<header title="Dato"/>'.
	'</headergroup>';
	foreach ($rs['items'] as $article) {
		$gui.='<row link="'.encodeXML($article['link']).'" target="_blank">'.
		'<cell>'.
		'<icon size="1" icon="Part/News"/>'.
		'<text><strong>'.html_entity_decode($article['title']).'</strong><break/>'.html_entity_decode($article['description']).'</text>'.
		'</cell>'.
		'<cell>'.encodeXML($article['pubDate']).'</cell>'.
		'</row>';
		}
	$gui.=
	'</content>'.
	'</list>'.
	'</interface>'.
	'</xmlwebgui>';

	$elements = array("List");
	writeGui($xwg_skin,$elements,$gui);
	} 
else { 
	echo "Error: It's not possible to get $rssurl..."; 
}
?>