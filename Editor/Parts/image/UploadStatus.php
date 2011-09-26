<?php
/**
 * @package OnlinePublisher
 * @subpackage Parts.Image
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Interface/In2iGui.php';
require_once '../../Classes/Parts/ImagePartController.php';


$id = ImagePartController::getLatestUploadId();

In2iGui::sendObject(array('id'=>$id));
?>