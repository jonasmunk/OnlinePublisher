<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

$rowId = Request::getInt('row',0);
$dir = Request::getInt('dir',0);

DocumentTemplateEditor::moveRow($rowId,$dir);

Response::redirect('../Editor.php');
?>