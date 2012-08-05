<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');

if ($file = File::load($id)) {
	$path = '../../../../files/'.$file->getFilename();

	header("Content-Disposition: attachment; filename=".$file->getFilename());
	header("Content-Type: ".$file->getMimeType());
	header("Content-Length: " . filesize($path));
	readfile($path);
	exit;
}
Response::notFound();
?>