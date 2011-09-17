<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

$sectionId = Request::getInt('section',0);
$dir = Request::getInt('dir',0);

DocumentTemplateEditor::moveSection($sectionId,$dir);

Response::redirect('../Editor.php');
?>