<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Optimization
 */
require_once '../../../Include/Private.php';

$index = PageService::getIndex(Request::getId());
$response = OnlineObjectsService::analyseText($index);
Response::sendObject($response);
?>
