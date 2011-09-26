<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Objects/File.php';
require_once '../../Classes/Core/Request.php';

$id = Request::getInt('id');

$file = File::load($id);

$path = '../../../files/'.$file->getFilename();

header("Content-Disposition: attachment; filename=".$file->getFilename());
header("Content-Type: ".$file->getMimeType());
header("Content-Length: " . filesize($path));
readfile($path);
?>