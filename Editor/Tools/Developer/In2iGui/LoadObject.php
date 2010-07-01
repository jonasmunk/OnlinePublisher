<?php
/**
 * @package OnlinePublisher
 * @subpackage Developer
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Include/Functions.php';
require_once '../../../Classes/In2iGui.php';
require_once '../../../Classes/Object.php';
require_once '../../../Classes/Request.php';

$id = Request::getString('id');

$object = Object::load($id);

header('Content-Type: text/xml;');
echo '<?xml version="1.0"?>
<update>
	<text name="editorTitle">'.In2iGui::escape($object->getTitle()).'</text>
	<text name="editorNote">'.In2iGui::escape($object->getNote()).'</text>
</update>
';
?>