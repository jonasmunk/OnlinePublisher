<?
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
$sql="select * from document_header where section_id=".$id;
$text = Database::selectFirst($sql);
if ($text) {
	$attrs = '';
	if ($text['fontsize']!='') $attrs.=' font-size="'.$text['fontsize'].'"';
	if ($text['fontfamily']!='') $attrs.=' font-family="'.$text['fontfamily'].'"';
	if ($text['textalign']!='') $attrs.=' text-align="'.$text['textalign'].'"';
	if ($text['lineheight']!='') $attrs.=' line-height="'.$text['lineheight'].'"';
	if ($text['fontweight']!='') $attrs.=' font-weight="'.$text['fontweight'].'"';
	if ($text['color']!='') $attrs.=' color="'.$text['color'].'"';
	if ($text['level']!='') $attrs.=' level="'.$text['level'].'"';
	if ($text['fontstyle']!='') $attrs.=' font-style="'.$text['fontstyle'].'"';
	if ($text['wordspacing']!='') $attrs.=' word-spacing="'.$text['wordspacing'].'"';
	if ($text['letterspacing']!='') $attrs.=' letter-spacing="'.$text['letterspacing'].'"';
	if ($text['textdecoration']!='') $attrs.=' text-decoration="'.$text['textdecoration'].'"';
	if ($text['textindent']!='') $attrs.=' text-indent="'.$text['textindent'].'"';
	if ($text['texttransform']!='') $attrs.=' text-transform="'.$text['texttransform'].'"';
	if ($text['fontvariant']!='') $attrs.=' font-variant="'.$text['fontvariant'].'"';
	$output.='<header'.$attrs.'>'.convertText($pageId,$text['text']).'</header>';
	$index.=' '.$text['text'];
}
?>