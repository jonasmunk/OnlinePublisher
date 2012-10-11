<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Developer
 */
require_once '../../../Include/Private.php';

$super = Request::getString('parent');

$diagram = new DiagramData();

$classes = ClassService::getClassInfo();

foreach ($classes as $class) {
	$parent = $class->getParent();
	
	if ($super && !in_array($super,$class->getHierarchy())) {
		//Log::debug('Skipping: '.$class->getName());
		continue;
	}
	$node = new DiagramNode();
	$node->setId($class->getName());
	$node->setTitle($class->getName());
	$properties = array();

	foreach ($class->getProperties() as $property) {
		if ($property->getOrigin()==$class->getName()) {
			$properties[] = array('label'=>$property->getName(),'value'=>$property->getValue(),'hint'=>$property->getType());
		}
	}

	$node->setProperties($properties);
	
	$diagram->addNode($node);
	
	$relations = $class->getRelations();
	
	foreach ($relations as $relation) {
		$diagram->addEdge()->from($relation->getFromClass())->to($relation->getToClass())->withLabel($relation->getFromProperty());
	}
	
	if ($class->getParent()) {
		$diagram->addEdge()->from($class->getName())->to($class->getParent())->withColor('#eee');
	}
}

Response::sendObject($diagram);

?>