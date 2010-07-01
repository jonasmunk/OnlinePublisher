<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Image.php';

$id = requestGetNumber('id',0);

$image = Image::load($id);

header("Content-Disposition: attachment; filename=".$image->getFilename());
header("Content-Type: ".$image->getMimeType());
header("Content-Length: " . filesize('../../../images/'.$image->getFilename()));
readfile('../../../images/'.$image->getFilename());
?>