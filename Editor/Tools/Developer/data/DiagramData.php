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
	if (!($class['parent']=='Object' || $class['name']=='Object' || $class['parent']=='Entity' || $class['name']=='Entity' || $class['parent']=='Part' || $class['name']=='Part')) {
	//if ($parent!='all' && !($class['parent']==$parent || $class['name']==$parent)) {
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
	
	if (isset(Entity::$schema[$class['name']])) {
		$info = Entity::$schema[$class['name']];
		if ($info['properties']) {
			foreach ($info['properties'] as $key => $value) {
				if (isset($value['relation']) && is_array($value['relation'])) {
					$diagram['lines'][] = array('from'=>$class['name'],'to'=>$value['relation']['class']);
				}
				if (isset($value['relations']) && is_array($value['relations'])) {
					foreach ($value['relations'] as $relation) {
						$diagram['lines'][] = array('from'=>$class['name'],'to'=>$relation['class']);
					}
				}
			}
		}
	}
	
	if ($class['parent']) {
		$diagram['lines'][] = array('from'=>$class['name'],'to'=>$class['parent'],'color'=>'#eee');
	}

	$num++;
}

Response::sendObject($diagram);

?>