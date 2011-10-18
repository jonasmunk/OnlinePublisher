<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Links
 */
require_once '../../../Include/Private.php';

$source = Request::getString('source');
$target = Request::getString('target');
$state = Request::getString('state');

if ($target=='all') {
	$target=null;
}
if ($source=='all') {
	$source=null;
}

$icons = array(
	'hierarchy' => 'monochrome/hierarchy',
	'file' => 'monochrome/file',
	'url' => 'monochrome/globe',
	'email' => 'monochrome/email',
	'page' => 'monochrome/page',
	'news' => 'monochrome/news'
);

$query = new LinkQuery();
$query->withTargetType($target)->withSourceType($source);
if ($state=='warnings') {
	$query->withOnlyWarnings();
}

$links = LinkService::search($query);

$graph = new Graph();
foreach ($links as $link) {
	$sourceId = $link->getSourceType().'_'.$link->getSourceId();
	$targetId = $link->getTargetType().'_'.$link->getTargetId();
	$targetIcon = $icons[$link->getTargetType()];
	if ($link->getStatus()) {
		$targetIcon = 'monochrome/warning';
	}
	$graph->addNode(new GraphNode($sourceId,$link->getSourceTitle(),$icons[$link->getSourceType()]));
	$graph->addNode(new GraphNode($targetId,$link->getTargetTitle(),$targetIcon));
	$graph->addEdge($sourceId,$targetId);
}

Response::sendUnicodeObject($graph);
?>