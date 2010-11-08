<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Include/XmlWebGui.php';
require_once '../../../Classes/File.php';
require_once '../../../Classes/Request.php';

$id = Request::getInt('id');

$file = Image::load($id);

$path = '../../../../images/'.$file->getFilename();

header("Content-Disposition: attachment; filename=".$file->getFilename());
header("Content-Type: ".$file->getMimeType());
header("Content-Length: " . filesize($path));
readfile($path);
?>