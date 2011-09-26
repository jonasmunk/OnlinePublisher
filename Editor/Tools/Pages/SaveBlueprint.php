<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Response.php';
require_once '../../Classes/Objects/Pageblueprint.php';
require_once '../../Classes/Core/Request.php';

$id = Request::getInt('id');
$title = Request::getString('title');
$frame = Request::getInt('frame');
$design = Request::getInt('design');
$template = Request::getInt('template');

if ($id>0) {
	$blueprint = Pageblueprint::load($id);
} else {
	$blueprint = new Pageblueprint();
}
$blueprint->setTitle($title);
$blueprint->setFrameId($frame);
$blueprint->setDesignId($design);
$blueprint->setTemplateId($template);
$blueprint->save();
$blueprint->publish();

Response::redirect('Blueprints.php');
?>