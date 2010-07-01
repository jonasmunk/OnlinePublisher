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
$url = requestGetText('url');

if ($rs = $rss->get($url)) {
	$title=$rs['title'];
}

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="100%" height="100%">'.
'<titlebar title="'.encodeXml($title).'" icon="Tool/News"/>'.
'<toolbar xmlns="uri:Toolbar" align="left">'.
'<tool title="Vis kilde" icon="File/xml" overlay="Search" link="'.encodeXML($url).'" target="Result"/>'.
'<flexible/>'.
'<searchfield name="url" title="URL" width="400" action="SourceFrame.php" value="'.encodeXML($url).'"/>'.
'</toolbar>'.
'<content valign="top">'.
'<iframe xmlns="uri:Frame" source="SourceViewer.php?url='.$url.'" name="Result"/>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Frame");
writeGui($xwg_skin,$elements,$gui);
?>