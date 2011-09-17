<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');

$column = DocumentTemplateEditor::loadColumn($id);
if ($column) {
	Response::sendObject($column);
} else {
	Response::notFound();
}
?>