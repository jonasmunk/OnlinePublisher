<?php
/**
 * @package OnlinePublisher
 * @subpackage Developer
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Include/Functions.php';
require_once '../../../Classes/In2iGui.php';
require_once '../../../Classes/Object.php';
require_once '../../../Classes/Request.php';

$id = Request::getPostInt('id');
$title = Request::getUnicodeString('editorTitle');
$note = Request::getUnicodeString('editorNote');

$object = Object::load($id);
$object->setTitle($title);
$object->setNote($note);
$object->update();
$object->publish();

In2iGui::respondSuccess();
?>