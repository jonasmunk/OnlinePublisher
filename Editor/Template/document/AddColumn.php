<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Response.php';
require_once '../../Classes/Templates/DocumentTemplateEditor.php';

$rowId = Request::getInt('row');
$index = Request::getInt('index');

$columnId = DocumentTemplateEditor::addColumn($rowId,$index);

Response::redirect('Editor.php');
?>