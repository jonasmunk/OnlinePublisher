<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Classes/Request.php';
require_once '../../../Classes/In2iGui.php';
require_once '../../../Classes/Objects/News.php';

$id = Request::getInt('id');
$file=News::load($id);
$file->toUnicode();

$groups = $file->getGroupIds();

$links = In2iGui::toLinks($file->getLinks());

In2iGui::sendObject(array('news' => $file, 'groups' => $groups, 'links' => $links));
?>