<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../Include/Private.php';

$data = Request::getObject('data');

$file = News::load($data->file);
$file->addGroupId($data->group);
?>