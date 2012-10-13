<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');

$row = DocumentTemplateEditor::loadRow($id);
if ($row) {
	Response::sendObject($row);
} else {
	Response::notFound();
}
?>