<?
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
$sql="select * from document_text where section_id=".$id;
$text = Database::selectFirst($sql);
$attrs = '';
if ($text['fontsize']!='') $attrs.=' font-size="'.$text['fontsize'].'"';
if ($text['fontfamily']!='') $attrs.=' font-family="'.$text['fontfamily'].'"';
if ($text['textalign']!='') $attrs.=' text-align="'.$text['textalign'].'"';
if ($text['lineheight']!='') $attrs.=' line-height="'.$text['lineheight'].'"';
if ($text['fontweight']!='') $attrs.=' font-weight="'.$text['fontweight'].'"';
if ($text['color']!='') $attrs.=' color="'.$text['color'].'"';
if ($text['fontstyle']!='') $attrs.=' font-style="'.$text['fontstyle'].'"';
if ($text['wordspacing']!='') $attrs.=' word-spacing="'.$text['wordspacing'].'"';
if ($text['letterspacing']!='') $attrs.=' letter-spacing="'.$text['letterspacing'].'"';
if ($text['textdecoration']!='') $attrs.=' text-decoration="'.$text['textdecoration'].'"';
if ($text['textindent']!='') $attrs.=' text-indent="'.$text['textindent'].'"';
if ($text['texttransform']!='') $attrs.=' text-transform="'.$text['texttransform'].'"';
if ($text['fontvariant']!='') $attrs.=' font-variant="'.$text['fontvariant'].'"';
$output.='<text'.$attrs.'>';
if ($text['image_id']>0) {
	$sql="select data from object where id=".$text['image_id'];
	if ($image = Database::selectFirst($sql)) {
		$output.=
		'<image float="'.$text['imagefloat'].'">'.
		$image['data'].
		'</image>';
	}
}
$output.=convertText($pageId,$text['text']);
$output.='</text>';
$index.=' '.$text['text'];
?>