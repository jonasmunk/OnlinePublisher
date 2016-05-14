<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

$rowId = Request::getInt('row');
$index = Request::getInt('index');

$columnId = DocumentTemplateEditor::addColumn($rowId,$index);

Response::redirect('../Editor.php');
?>