<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
// ondblclick="document.location=\'Editor.php?section='.$sectionId.'\';"
	$output.='<td style="'.$sectionStyle.'" class="sectionTDtext">';
	$sql="select * from document_text where section_id=".$sectionId;
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
	$output.='<div style="'.$style.'" class="TextDisplay">';
	if ($row['image_id']>0) {
		$sql="select * from image where object_id=".$row['image_id'];
		if ($image = Database::selectFirst($sql)) {
			$output.='<img src="../../../images/'.$image['filename'].'" width="'.$image['width'].'" height="'.$image['height'].'" class="TextImageDisplay TextImageDisplay-'.$row['imagefloat'].'" align="'.$row['imagefloat'].'"/>';
		}
	}
	$output.=convertText($row['text']).'</div>';
	$output.='</td>';
?>