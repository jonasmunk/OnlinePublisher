<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

$sectionId = Request::getInt('id');


$sql = "select part.type,document_section.page_id,part.id as part_id,document_section.left,document_section.right,document_section.bottom,document_section.top,document_section.width,document_section.float from document_section,part where part.id = document_section.part_id and document_section.id=".Database::int($sectionId);
if ($row = Database::selectFirst($sql)) {
	$out = array();
	
	$partType = $row['type'];
	$pageId = $row['page_id'];
	$partId = $row['part_id'];
	$section = DocumentTemplateEditor::getSection($sectionId);

	$partContext = DocumentTemplateController::buildPartContext($pageId);

	$ctrl = PartService::getController($partType);
	if (!$ctrl) {
		return;
	}
	$part = PartService::load($partType,$partId);
	if (!$part) {
		return;
	}
	$html=
	//'<div style="'.$sectionStyle.'" id="selectedSection" class="part_section_'.$partType.' '.$ctrl->getSectionClass($part).' section_selected">'.
	'<form name="PartForm" action="data/UpdatePart.php" method="post" charset="utf-8">'.
	'<input type="hidden" name="id" value="'.$partId.'"/>'.
	'<input type="hidden" name="part_type" value="'.$partType.'"/>'.
	'<input type="hidden" name="section" value="'.$sectionId.'"/>'.
	'<input type="hidden" name="left" value="'.Strings::escapeXML($row['left']).'"/>'.
	'<input type="hidden" name="right" value="'.Strings::escapeXML($row['right']).'"/>'.
	'<input type="hidden" name="bottom" value="'.Strings::escapeXML($row['bottom']).'"/>'.
	'<input type="hidden" name="top" value="'.Strings::escapeXML($row['top']).'"/>'.
	'<input type="hidden" name="width" value="'.Strings::escapeXML($row['width']).'"/>'.
	'<input type="hidden" name="float" value="'.Strings::escapeXML($row['float']).'"/>';
	$html.= $ctrl->editor($part,$partContext);
	$html.= '</form>';
	if (method_exists($ctrl,'editorGui')) {
		$html.=$ctrl->editorGui($part,$partContext);
	}
	Response::sendObject(array(
		'html' => Strings::fromUnicode($html),
		'partType' => $partType,
		'partId' => $partId,
		'style' => array(
			'left' => $row['left'],
			'right' => $row['right'],
			'top' => $row['top'],
			'bottom' => $row['bottom'],
			'width' => $row['width'],
			'float' => $row['float']
		)
	));
} else {
	echo 'No good! id='.$sectionId;
}
?>