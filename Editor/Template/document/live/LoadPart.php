<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');
$type = Request::getString('type');

$sql = "select * from document_section where part_id=" . Database::int($id);
$section = Database::selectFirst($sql);
if ($section) {
	$section = array(
		'id' => intval($section['id']),
		'left' => $section['left'],
		'right' => $section['right'],
		'top' => $section['top'],
		'bottom' => $section['bottom'],
		'float' => $section['float'],
		'width' => $section['width']
	);
}

$part = PartService::load($type,$id);
//Response::sendObject($part);
Response::sendObject(array(
	'part' => $part,
	'section' => $section
));
?>