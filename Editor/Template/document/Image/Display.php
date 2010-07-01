<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
	$output.='<td style="'.$sectionStyle.'" class="sectionTDimage">';
	$sql="select * from document_image where section_id=".$sectionId;
	if ($row = Database::selectFirst($sql)) {
		$sql="select * from image where object_id=".$row['image_id'];
		if ($image = Database::selectFirst($sql)) {
			$output.='<div align="'.$row['align'].'"><img src="../../../images/'.$image['filename'].'" width="'.$image['width'].'" height="'.$image['height'].'" class="ImageDisplay"/></div>';
		}
		else {
			$output.='<div align="'.$row['align'].'"><img src="Graphics/ImageNotFound.gif" width="88" height="88" class="ImageDisplay"/></div>';
		}
	}
	$output.='</td>';
?>