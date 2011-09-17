<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');
$width = Request::getString('width');

DocumentTemplateEditor::updateColumn(array(
	'id' => $id,
	'width' => $width
));
?>