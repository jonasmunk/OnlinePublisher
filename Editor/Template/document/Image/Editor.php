<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
	$sql="select * from document_image,document_section where document_image.section_id=document_section.id and document_section.id=".$sectionId;
	$row = Database::selectFirst($sql);
	if ($row) {
		$style='';
		$output.=
		'<td style="'.$sectionStyle.'" id="selectedSectionTD" class="sectionTDimage sectionSelected">'.
		'<form name="ImageForm" action="Image/Update.php" method="post" style="margin: 0px;">'.
		'<input type="hidden" name="imageId" value="'.$row['image_id'].'"/>'.
		'<input type="hidden" name="align" value="'.$row['align'].'"/>'.
		'<input type="hidden" name="left" value="'.$row['left'].'"/>'.
		'<input type="hidden" name="right" value="'.$row['right'].'"/>'.
		'<input type="hidden" name="top" value="'.$row['top'].'"/>'.
		'<input type="hidden" name="bottom" value="'.$row['bottom'].'"/>'.
		'</form>'.
		'<div align="'.$row['align'].'" id="ImageDiv">'.
		'<img id="Image" src="Image/ImageDisplayer.php?id='.$row['image_id'].'" class="ImageEditor"/>'.
		'</div>'.
		'<table width="200"></table>'.
		'<script>
		parent.Toolbar.location=\'Image/Toolbar.php?\'+Math.random();
		function saveSection() {
			document.forms.ImageForm.submit();
		}
	
		</script>'.
		'</td>';
	}
?>