<?php
/**
 * @package OnlinePublisher
 * @subpackage Parts.File
 */
require_once '../../Include/Private.php';

$id = FilePartController::getLatestUploadId();

Response::sendObject(array('id'=>$id));
?>