<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

$columnId = Request::getInt('column');
$index = Request::getInt('index');
$part = Request::getString('part');

$sectionId = DocumentTemplateEditor::addSection($columnId,$index,$part);

Response::sendObject(array('sectionId'=>$sectionId));
?>