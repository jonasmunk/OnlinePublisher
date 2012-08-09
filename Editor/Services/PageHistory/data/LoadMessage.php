<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.PageHistory
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');

$sql = "select message from page_history where id=".Database::int($id);
if ($row = Database::selectFirst($sql)) {
	Response::sendObject(array(
		'message' => $row['message']
	));
}
?>