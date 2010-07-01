<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/File.php';
require_once 'Functions.php';

$id = requestGetNumber('id',0);

$file = File::load($id);

header("Content-Disposition: attachment; filename=".$file->getFilename());
header("Content-Type: ".$file->getMimeType());
header("Content-Length: " . filesize('../../../files/'.$file->getFilename()));
readfile('../../../files/'.$file->getFilename());
?>