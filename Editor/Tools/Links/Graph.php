<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Developer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Graph.php';
require_once 'LinksController.php';

$graph = new Graph();


$sql = LinksController::buildSQL();

$result = Database::select($sql);

while ($row = Database::next($result)) {
    $analyzed = LinksController::analyzeLink($row);
	$graph->add($analyzed['sourceTitle'],$analyzed['targetTitle']);
}
Database::free($result);

$graph->display('links.dot');
?>