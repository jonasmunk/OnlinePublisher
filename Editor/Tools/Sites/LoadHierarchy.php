<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/Hierarchy.php';

$id = Request::getInt('id');
$hierarchy = Hierarchy::load($id);

In2iGui::sendUnicodeObject(array(
	'id' => $hierarchy->getId(),
	'name' => $hierarchy->getName(),
	'language' => $hierarchy->getLanguage(),
	'canDelete' => $hierarchy->canDelete()
));
?>