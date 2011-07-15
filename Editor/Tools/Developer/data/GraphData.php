<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Developer
 */
require_once '../../../Include/Private.php';

$graph = array(
	'nodes'=>array(
//		array('id'=>'person','label'=>'Person','icon'=>'monochrome/person'),
//		array('id'=>'email','label'=>'E-mail')
	),
	'edges'=>array(
//		array('from'=>'person','to'=>'email')
	)
);

$images = Query::after('image')->get();

foreach ($images as $image) {
	$graph['nodes'][] = array('id'=>$image->getId(),'label'=>$image->getTitle(),'icon'=>'monochrome/image');
}

$groups = Query::after('imagegroup')->get();

foreach ($groups as $group) {
	$graph['nodes'][] = array('id'=>$group->getId(),'label'=>$group->getTitle(),'icon'=>'monochrome/folder');
}

$sql = "select image_id,imagegroup_id from imagegroup_image,image,imagegroup where image.object_id=imagegroup_image.image_id and imagegroup.object_id=imagegroup_image.imagegroup_id";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$graph['edges'][] = array('from'=>intval($row['image_id']),'to'=>intval($row['imagegroup_id']));
}
Database::free($result);


$sql = "select image.object_id as image_id,person.object_id as person_id from image,person where person.`image_id`=image.object_id";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$person = Person::load($row['person_id']);
	$graph['nodes'][] = array('id'=>$person->getId(),'label'=>$person->getTitle(),'icon'=>'monochrome/person');
	$graph['edges'][] = array('from'=>intval($row['image_id']),'to'=>intval($row['person_id']));
}
Database::free($result);

$pageIds = array();

$sql = "select image_id,page.title as page_title,page.id as page_id from `part_image`,document_section,page where part_image.part_id=document_section.part_id and page.id=document_section.page_id";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	if (!in_array($pageIds,$row['page_id'])) {
		$pageIds[] = $row['page_id'];
		$graph['nodes'][] = array('id'=>intval($row['page_id']),'label'=>$row['page_title'],'icon'=>'monochrome/file');
	}
	$graph['edges'][] = array('from'=>intval($row['image_id']),'to'=>intval($row['page_id']));
}
Database::free($result);

Response::sendUnicodeObject($graph)
?>