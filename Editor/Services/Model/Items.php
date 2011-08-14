<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Model
 */
require_once '../../Include/Private.php';

$type = Request::getString('type');
$text = Request::getString('query');

$writer = new ItemsWriter();

$writer->startItems();
if ($type=='page') {
	$sql = "select page.id,page.title from page order by page.title";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$writer->item(array(
			'title' => $row['title'],
			'value' => $row['id']
		));
	}
	Database::free($result);
} else if ($type=='template') {
	$templates = TemplateService::getTemplatesSorted();
	foreach ($templates as $template) {
		$writer->item(array(
			'title' => $template['name'],
			'value' => $template['id']
		));
	}
} else if ($type=='frame') {
	$frames = Frame::search();
	foreach ($frames as $frame) {
		$writer->item(array(
			'title' => $frame->getName(),
			'value' => $frame->getId()
		));
	}
} else {
	$query = array('type'=>$type,'query'=>$text);
	$list = Object::find($query);
	$objects = $list['result'];
	foreach ($objects as $object) {
		$writer->item(array(
			'title' => $object->getTitle(),
			'value' => $object->getId(),
			'icon' => $object->getIn2iGuiIcon(),
			'kind' => $type
		));
	}
}
$writer->endItems();
?>