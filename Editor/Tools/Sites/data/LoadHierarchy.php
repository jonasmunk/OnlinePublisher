<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');
$hierarchy = Hierarchy::load($id);

Response::sendUnicodeObject(array(
	'id' => $hierarchy->getId(),
	'name' => $hierarchy->getName(),
	'language' => $hierarchy->getLanguage(),
	'canDelete' => $hierarchy->canDelete()
));
?>