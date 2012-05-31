<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.HTML
 */
require_once '../../../Include/Private.php';

$id = Request::getId();

$sql="select * from html where page_id=".Database::int($id);
if ($row = Database::getRow($sql)) {
	Response::sendUnicodeObject($row);
} else {
	Response::notFound();
}
?>