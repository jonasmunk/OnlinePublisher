<?php
/**
 * @package OnlinePublisher
 * @subpackage Parts.File
 */
require_once '../../Include/Private.php';

$id = MoviePartController::getLatestUploadId();

Response::sendObject(array('id'=>$id));
?>