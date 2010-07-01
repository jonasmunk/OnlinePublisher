<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
	$output.='<td style="'.$sectionStyle.'" class="sectionTDdivider" ondblclick="document.location=\'Editor.php?section='.$sectionId.'\';">';
	$sql="select * from document_divider where section_id=".$sectionId;
	$row = Database::selectFirst($sql);
	$output.='<hr class="DividerDisplay"/>';
	$output.='</td>';
?>