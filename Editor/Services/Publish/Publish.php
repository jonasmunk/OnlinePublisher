<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Publish
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Include/Publishing.php';
require_once '../../Classes/Object.php';
require_once '../../Classes/Hierarchy.php';



$close = requestPostText('close');
$pages = requestPostArray('page');

foreach ($pages as $page) {
	publishPage($page);
}

$hiers = requestPostArray('hierarchy');
foreach ($hiers as $hierId) {
    $hier = Hierarchy::load($hierId);
    $hier->publish();
}

$objects = requestPostArray('object');
foreach ($objects as $objectId) {
	$object = Object::load($objectId);
	$object->publish();
}

redirect('index.php?close='.$close);
?>