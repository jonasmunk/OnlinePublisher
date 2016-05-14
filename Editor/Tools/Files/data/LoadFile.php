<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');
if ($file=File::load($id)) {

	$groups = $file->getGroupIds();

	Response::sendObject(array('file' => $file, 'groups' => $groups));
} else {
	Response::notFound();
}
?>