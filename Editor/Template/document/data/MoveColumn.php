<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

$columnId = Request::getInt('column',0);
$dir = Request::getInt('dir',0);

$columnId = DocumentTemplateEditor::moveColumn($columnId,$dir);

Response::redirect('../Editor.php');
?>