<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Hierarchy.php';
require_once '../../Classes/Graph.php';
require_once '../../Classes/Request.php';

$id = Request::getInt('id');

$hier = Hierarchy::load($id);

$graph = new Graph();

buildGraph($id,0,$hier->getName(),$graph);

$graph->display(Request::getString('format'));

function buildGraph($hierarchyId,$parentId,$parentName,&$graph) {
	global $templates;
	$sql="select id,title from hierarchy_item where parent=".$parentId." and hierarchy_id=".$hierarchyId." order by `index`";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$graph->add($parentName,$row['title']);
		buildGraph($hierarchyId,$row['id'],$row['title'],$graph);
	}
	Database::free($result);
}
?>