<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Include/Private.php';

$data = Request::getObject('data');

if ($file = File::load($data->file)) {
	if ($group = Filegroup::load($data->group)) {
		$file->addGroupId($data->group);
	}
}
?>