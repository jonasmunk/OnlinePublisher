<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<hierarchy xmlns="uri:Hierarchy" persistence="true" unique="tools-news-sources">'.
'<element icon="Basic/Inbox" title="Indgående" open="true">'.
	'<element icon="Web/Service" title="FreshFolder" link="SourceFrame.php?url=http://www.freshfolder.com/rss.php" target="Right"/>'.
	'<element icon="Web/Service" title="Apple" link="SourceFrame.php?url=http://www.apple.com/main/rss/hotnews/hotnews.rss" target="Right"/>'.
'</element>'.
'<element icon="Basic/Outbox" title="Udgående" open="true">'.
	'<element icon="Web/Service" title="FreshFolder" link="SourceFrame.php?url=http://www.freshfolder.com/rss.php" target="Right"/>'.
	'<element icon="Web/Service" title="Apple" link="SourceFrame.php?url=http://www.apple.com/main/rss/hotnews/hotnews.rss" target="Right"/>'.
'</element>'.
'</hierarchy>'.
//'<poller xmlns="uri:Script" source="HierarchyUpdateCheck.php" interval="5000"/>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Hierarchy","Script");
writeGui($xwg_skin,$elements,$gui);
?>