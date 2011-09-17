<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

$sectionId = Request::getInt('section');

DocumentTemplateEditor::deleteSection($sectionId);

Response::redirect('../Editor.php?section=0');
?>