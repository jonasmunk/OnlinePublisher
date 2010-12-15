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

$sectionId = Request::getInt('section');

DocumentTemplateEditor::deleteSection($sectionId);

Response::redirect('Editor.php?section=0');
?>