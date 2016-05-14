<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

$columnId = Request::getInt('column');

DocumentTemplateEditor::deleteColumn($columnId);

Response::redirect('../Editor.php?column=0');
?>