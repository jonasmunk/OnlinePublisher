<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
	$output.='<td style="'.$sectionStyle.'" class="sectionTDrichtext">';
	$sql="select * from document_richtext where section_id=".$sectionId;
	$row = Database::selectFirst($sql);
	$output.=$row['data'];
	$output.='</td>';
?>