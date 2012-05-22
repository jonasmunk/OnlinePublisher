<?php
/**
 * @package OnlinePublisher
 * @subpackage Parts.File
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Response.php';
require_once '../../Classes/Parts/FilePartController.php';

$id = FilePartController::getLatestUploadId();

Response::sendObject(array('id'=>$id));
?>