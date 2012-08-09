<?php
/**
 * @package OnlinePublisher
 * @subpackage Parts.Image
 */
require_once '../../Include/Private.php';


$id = ImagePartController::getLatestUploadId();

Response::sendObject(array('id'=>$id));
?>