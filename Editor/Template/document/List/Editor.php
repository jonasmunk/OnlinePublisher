<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
	$sql="select document_list.*,document_section.left,document_section.right,document_section.top,document_section.bottom from document_list,document_section where document_list.section_id=document_section.id and document_section.id=".$sectionId;
	$row = Database::selectFirst($sql);
	$style='';
	if ($row['fontsize']!='') $style.='font-size: '.$row['fontsize'].';';
	if ($row['fontfamily']!='') $style.='font-family: '.$row['fontfamily'].';';
	if ($row['textalign']!='') $style.='text-align: '.$row['textalign'].';';
	if ($row['lineheight']!='') $style.='line-height: '.$row['lineheight'].';';
	if ($row['fontweight']!='') $style.='font-weight: '.$row['fontweight'].';';
	if ($row['color']!='') $style.='color: '.$row['color'].';';
	if ($row['fontstyle']!='') $style.='font-style: '.$row['fontstyle'].';';
	if ($row['wordspacing']!='') $style.='word-spacing: '.$row['wordspacing'].';';
	if ($row['letterspacing']!='') $style.='letter-spacing: '.$row['letterspacing'].';';
	if ($row['textdecoration']!='') $style.='text-decoration: '.$row['textdecoration'].';';
	if ($row['textindent']!='') $style.='text-indent: '.$row['textindent'].';';
	if ($row['texttransform']!='') $style.='text-transform: '.$row['texttransform'].';';
	if ($row['fontvariant']!='') $style.='font-variant: '.$row['fontvariant'].';';
	$output.=
	'<td style="'.$sectionStyle.'" id="selectedSectionTD" class="sectionTDlist sectionSelected">'.
	'<form name="TextForm" action="List/Update.php" method="post" style="margin: 0px; padding: 0px;">'.
	'<input type="hidden" name="left" value="'.$row['left'].'"/>'.
	'<input type="hidden" name="right" value="'.$row['right'].'"/>'.
	'<input type="hidden" name="top" value="'.$row['top'].'"/>'.
	'<input type="hidden" name="bottom" value="'.$row['bottom'].'"/>'.
	'<input type="hidden" name="fontSize" value="'.$row['fontsize'].'"/>'.
	'<input type="hidden" name="fontFamily" value="'.$row['fontfamily'].'"/>'.
	'<input type="hidden" name="textAlign" value="'.$row['textalign'].'"/>'.
	'<input type="hidden" name="lineHeight" value="'.$row['lineheight'].'"/>'.
	'<input type="hidden" name="fontWeight" value="'.$row['fontweight'].'"/>'.
	'<input type="hidden" name="fontStyle" value="'.$row['fontstyle'].'"/>'.
	'<input type="hidden" name="color" value="'.$row['color'].'"/>'.
	'<input type="hidden" name="wordSpacing" value="'.$row['wordspacing'].'"/>'.
	'<input type="hidden" name="letterSpacing" value="'.$row['letterspacing'].'"/>'.
	'<input type="hidden" name="textDecoration" value="'.$row['textdecoration'].'"/>'.
	'<input type="hidden" name="textIndent" value="'.$row['textindent'].'"/>'.
	'<input type="hidden" name="textTransform" value="'.$row['texttransform'].'"/>'.
	'<input type="hidden" name="fontVariant" value="'.$row['fontvariant'].'"/>'.
	'<input type="hidden" name="type" value="'.$row['type'].'"/>'.
	'<input type="hidden" name="start" value="'.$row['start'].'"/>'.
	'<textarea style="width: 100%; border: 1px solid lightgrey; height: 200px;'.$style.'" class="ListEditor" name="text">'.
	escapeHTML($row['text']).
	'</textarea>'.
	'</form>'.
	'<table width="200"></table>'.
	'<script>
	parent.Toolbar.location=\'List/Toolbar.php?\'+Math.random();
	document.forms.TextForm.text.focus();
	function saveSection() {
		document.forms.TextForm.submit();
	}
	</script>'.
	'</td>';
?>