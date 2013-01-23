<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Optimization
 */
require_once '../../../Include/Private.php';

$sql = "select `index` from page where id=".Database::int(Request::getId());
$row = Database::selectFirst($sql);
if ($row) {
	$response = OnlineObjectsService::analyseText($row['index']);
	Response::sendObject($response);
	
} else {
	Log::debug($sql);
	Response::notFound();
	
}
?>
