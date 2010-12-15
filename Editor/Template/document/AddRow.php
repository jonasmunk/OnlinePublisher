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

$pageId = InternalSession::getPageId();
$index = Request::getInt('index');

$rowId = DocumentTemplateEditor::addRow($pageId,$index);

Response::redirect('Editor.php?row='.$rowId);
?>