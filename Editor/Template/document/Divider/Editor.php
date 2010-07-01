<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
	$sql="select * from document_divider,document_section where document_divider.section_id=document_section.id and document_section.id=".$sectionId;
	$row = Database::selectFirst($sql);
	$output.=
	'<td style="'.$sectionStyle.'" id="selectedSectionTD" class="sectionTDdivider sectionSelected">'.
	'<form name="DividerForm" action="Text/Update.php" method="post" style="margin: 0px; padding: 0px;">'.
	'<input type="hidden" name="left" value="'.$row['left'].'"/>'.
	'<input type="hidden" name="right" value="'.$row['right'].'"/>'.
	'<input type="hidden" name="top" value="'.$row['top'].'"/>'.
	'<input type="hidden" name="bottom" value="'.$row['bottom'].'"/>'.
	'<hr class="DividerDisplay"/>'.
	'</form>'.
	'<script>'.
	'parent.Toolbar.location=\'Divider/Toolbar.php?\'+Math.random();
	function saveSection() {
		document.forms.DividerForm.submit();
	}
	</script>'.
	'</td>';
?>