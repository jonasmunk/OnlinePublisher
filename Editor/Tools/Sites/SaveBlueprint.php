<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../Include/Private.php';

$data = Request::getObject('data');

if ($data->id>0) {
	$object = Pageblueprint::load($data->id);
} else {
	$object = new Pageblueprint();
}
$object->setTitle(Request::fromUnicode($data->title));
$object->setDesignId($data->designId);
$object->setTemplateId($data->templateId);
$object->setFrameId($data->frameId);
$object->save();
$object->publish();
?>