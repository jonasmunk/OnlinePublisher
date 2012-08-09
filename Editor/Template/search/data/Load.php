<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Search
 */
require_once '../../../Include/Private.php';

$id = Request::getId();

$obj = SearchTemplate::load($id);
if ($obj) {
	Response::sendObject($obj);
} else {
	Response::notFound();
}
?>