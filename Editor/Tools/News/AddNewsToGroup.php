<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Objects/News.php';

$data = Request::getObject('data');

$file = News::load($data->file);
$file->addGroupId($data->group);
?>