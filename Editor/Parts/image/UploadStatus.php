<?php
/**
 * @package OnlinePublisher
 * @subpackage Parts.Image
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/In2iGui.php';
require_once 'image.php';


$id = PartImage::getLatestUploadId();

In2iGui::sendObject(array('id'=>$id));
?>