<?php
/**
 * @package OnlinePublisher
 * @subpackage Parts.Image
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Parts/ImagePartController.php';
require_once '../../Classes/Core/Response.php';


$id = ImagePartController::getLatestUploadId();

Response::sendObject(array('id'=>$id));
?>