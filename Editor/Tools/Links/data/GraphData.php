<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Links
 */
require_once '../../../Include/Private.php';
require_once 'LinksController.php';

$source = Request::getString('source');
$target = Request::getString('target');

$query = array('source'=>$source,'target'=>$target);

$gr = new Graph();


$sql = LinksController::buildSQL($query);
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$gr->addNode(new GraphNode($row['source_type'].'_'.$row['source_id'],$row['source_title'],'monochrome/image'));
	$gr->addNode(new GraphNode($row['target_type'].'_'.$row['target_id'],$row['target_value'],'monochrome/file'));
	$gr->addEdge($row['source_type'].'_'.$row['source_id'],$row['target_type'].'_'.$row['target_id']);
}
Database::free($result);


Response::sendUnicodeObject($gr)
?>