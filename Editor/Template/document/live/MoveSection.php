<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

$sectionId = Request::getInt('sectionId',0);
$rowIndex = Request::getInt('rowIndex',-1);
$columnIndex = Request::getInt('columnIndex',-1);
$sectionIndex = Request::getInt('sectionIndex',-1);

DocumentTemplateEditor::moveSectionFar(array(
	'sectionId' => $sectionId,
	'rowIndex' => $rowIndex,
	'columnIndex' => $columnIndex,
	'sectionIndex' => $sectionIndex
));

Response::redirect('../Editor.php');
?>