<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Issues
 */
require_once '../../../Include/Private.php';

$ids = Request::getObject('ids');

if (!is_array($ids)) {
	Response::badRequest();
	exit;
}

foreach ($ids as $id) {
	if ($object = Issue::load($id)) {
		$object->remove();
	}
}
?>