<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Issues
 */
require_once '../../../Include/Private.php';

$id = Request::getId();

$object = Issue::load($id);
if ($object) {
	Response::sendObject($object);
} else {
	Response::notFound();
}
?>