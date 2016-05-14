<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Issues
 */
require_once '../../../Include/Private.php';

$ids = Request::getObject('ids');
$kind = Request::getObject('kind');

if (!is_array($ids)) {
	Log::debug($ids);
	Response::badRequest();
	exit;
}

foreach ($ids as $id) {
	if ($object = Issue::load($id)) {
		$object->setKind($kind);
		$object->save();
		$object->publish();
	}
}
?>