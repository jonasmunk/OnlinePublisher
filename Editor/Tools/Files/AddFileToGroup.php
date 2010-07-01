<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/File.php';

$data = Request::getObject('data');

$file = File::load($data->file);
$file->addGroupId($data->group);
?>