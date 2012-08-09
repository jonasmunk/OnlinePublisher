<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Developer
 */
require_once '../../../Include/Private.php';





$gr = new Graph();

$images = Query::after('image')->get();

foreach ($images as $image) {
	$gr->addNode(new GraphNode($image->getId(),$image->getTitle(),'monochrome/image'));
}

$groups = Query::after('imagegroup')->get();

foreach ($groups as $group) {
	$gr->addNode(new GraphNode($group->getId(),$group->getTitle(),'monochrome/folder'));
}

$sql = "select image_id,imagegroup_id from imagegroup_image,image,imagegroup where image.object_id=imagegroup_image.image_id and imagegroup.object_id=imagegroup_image.imagegroup_id";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$gr->addEdge(intval($row['image_id']),intval($row['imagegroup_id']));
}
Database::free($result);


$sql = "select image.object_id as image_id,person.object_id as person_id from image,person where person.`image_id`=image.object_id";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$person = Person::load($row['person_id']);
	$gr->addNode(new GraphNode($person->getId(),$person->getTitle(),'monochrome/person'));
	$gr->addEdge(intval($row['image_id']),intval($row['person_id']));
}
Database::free($result);


$sql = "select image_id,page.title as page_title,page.id as page_id from `part_image`,document_section,page where part_image.part_id=document_section.part_id and page.id=document_section.page_id";

$result = Database::select($sql);
while ($row = Database::next($result)) {
	$gr->addNode(new GraphNode(intval($row['page_id']),$row['page_title'],'monochrome/file'));
	$gr->addEdge(intval($row['image_id']),intval($row['page_id']));
}
Database::free($result);

Response::sendObject($gr)
?>