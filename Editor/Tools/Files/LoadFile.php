<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Request.php';
require_once '../../Classes/Interface/In2iGui.php';
require_once '../../Classes/Objects/File.php';

$id = Request::getInt('id');
$file=File::load($id);
$file->toUnicode();

$groups = $file->getGroupIds();

In2iGui::sendObject(array('file' => $file, 'groups' => $groups));
?>