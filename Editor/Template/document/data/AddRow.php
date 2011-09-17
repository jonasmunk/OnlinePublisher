<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

$pageId = Request::getInt('pageId');
$index = Request::getInt('index');

$rowId = DocumentTemplateEditor::addRow($pageId,$index);

Response::redirect('../Editor.php?row='.$rowId);
?>