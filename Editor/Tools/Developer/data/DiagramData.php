<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Developer
 */
require_once '../../../Include/Private.php';

$parent = Request::getString('parent');

$diagram = array(
	"nodes" => array(
	),
	"lines" => array(
	)
);

$classes = ClassService::getClasses();

$num = 1;
foreach ($classes as $class) {
	if ($parent!='all' && !($class['parent']==$parent || $class['name']==$parent)) {
		continue;
	}
	$node = array(
		'id' => $class['name'],
		'title' => $class['name'],
		'properties' => array()
	);
	foreach ($class['properties'] as $key => $value) {
		$node['properties'][] = array('label'=>$key,'value'=>$value); //$value
	}
	$diagram['nodes'][] = $node;
	
	if ($class['parent']) {
		$diagram['lines'][] = array('from'=>$class['name'],'to'=>$class['parent']);
	}

	$num++;
}

Response::sendObject($diagram);

?>