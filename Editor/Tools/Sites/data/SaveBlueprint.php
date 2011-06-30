<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';

$data = Request::getUnicodeObject('data');

if ($data->id>0) {
	$object = Pageblueprint::load($data->id);
} else {
	$object = new Pageblueprint();
}
$object->setTitle($data->title);
$object->setDesignId($data->designId);
$object->setTemplateId($data->templateId);
$object->setFrameId($data->frameId);
$object->save();
$object->publish();
?>