<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

$rowId = Request::getInt('row');

DocumentTemplateEditor::deleteRow($rowId);

Response::redirect('../Editor.php?column=0');
?>