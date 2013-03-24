<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Objects
 */
require_once '../../Include/Private.php';

$ids = Request::getObject('ids');

if (!is_array($ids)) {
	Response::badRequest();
	exit;
}

foreach ($ids as $id) {
	if ($object = Object::load($id)) {
		$object->remove();
	} else {
		Log::debug('Unable to load object with id='.$id);
	}
}
?>