<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Request.php';
require_once '../../Classes/Model/Hierarchy.php';

$data = Request::getUnicodeObject('data');

if ($data->id) {
	if (!$item = Hierarchy::load($data->id)) {
		Response::badRequest();
		exit;
	}
} else {
	$item = new Hierarchy();
}
$item->setName($data->name);
$item->setLanguage($data->language);
$item->save();
?>