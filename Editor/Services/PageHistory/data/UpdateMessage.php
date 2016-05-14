<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.PageHistory
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');
$message = Request::getString('message');

$sql = "update page_history set message=".Database::text($message)." where id=".Database::int($id);
if (!Database::update($sql)) {
	Response::badRequest();
}
?>