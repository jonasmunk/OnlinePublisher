<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Pageblueprint.php';

$id = requestPostNumber('id');
$title = requestPostText('title');
$frame = requestPostNumber('frame');
$design = requestPostNumber('design');
$template = requestPostNumber('template');

if ($id>0) {
	$blueprint = PageBlueprint::load($id);
} else {
	$blueprint = new PageBlueprint();
}
$blueprint->setTitle($title);
$blueprint->setFrameId($frame);
$blueprint->setDesignId($design);
$blueprint->setTemplateId($template);
$blueprint->save();
$blueprint->publish();

redirect('Blueprints.php');
?>