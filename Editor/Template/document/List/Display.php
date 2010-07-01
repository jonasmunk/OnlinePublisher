<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
	$output.='<td style="'.$sectionStyle.'" class="sectionTDlist">';
	$sql="select * from document_list where section_id=".$sectionId;
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
	if ($row['type']=='disc' || $row['type']=='circle' || $row['type']=='square') {
		$tag='ul';
	}
	else {
		$tag='ol';
	}
	$parsed=parseList($row['text']);
	$text='';
	for ($i=0;$i<count($parsed);$i++) {
		$item=$parsed[$i];
		$text.='<li class="ListItem">';
		for ($j=0;$j<count($item);$j++) {
			$line=$item[$j];
			$line=escapeHTML($line);
			$line=insertTags($line);
			$line=insertLink($line);
			if ($j==0) {
				$text.='<span class="ListItemFirst">'.$line.'</span>';
			}
			else {
				$text.='<br>'.$line;
			}
		}
		$text.='</li>';
	}
	$text='<'.$tag.' type="'.$row['type'].'" class="List">'.$text.'</'.$tag.'>';
	$output.='<div style="'.$style.'" class="ListDisplay">'.$text.'</div>';
	$output.='</td>';
?>