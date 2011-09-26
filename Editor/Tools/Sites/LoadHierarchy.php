<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Request.php';
require_once '../../Classes/Interface/In2iGui.php';
require_once '../../Classes/Model/Hierarchy.php';

$id = Request::getInt('id');
$hierarchy = Hierarchy::load($id);

In2iGui::sendUnicodeObject(array(
	'id' => $hierarchy->getId(),
	'name' => $hierarchy->getName(),
	'language' => $hierarchy->getLanguage(),
	'canDelete' => $hierarchy->canDelete()
));
?>