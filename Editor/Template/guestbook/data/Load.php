<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Guestbook
 */
require_once '../../../Include/Private.php';

$id = Request::getId();

$sql="select * from guestbook where page_id=".Database::int($id);
if ($row = Database::getRow($sql)) {
	Response::sendObject($row);
} else {
	Response::notFound();
}
?>