<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Templates/DocumentTemplateEditor.php';

$rowId = Request::getInt('row');

DocumentTemplateEditor::deleteRow($rowId);

Response::redirect('Editor.php?column=0');
?>