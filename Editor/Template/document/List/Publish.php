<?
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
$sql="select * from document_list where section_id=".$id;
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
$attrs.=' type="'.$text['type'].'"';
$parsed=parseList($text['text']);
$data='';
for ($i=0;$i<count($parsed);$i++) {
	$item=$parsed[$i];
	$data.='<item>';
	for ($j=0;$j<count($item);$j++) {
		$line=encodeXML($item[$j]);
		$line=insertTags($line);
		$line=insertLink($pageId,$line);
		if ($j==0) {
			$data.='<first>'.$line.'</first>';
		}
		else {
			$data.='<break/>'.$line;
		}
	}
	$data.='</item>';
}

//$data=convertText($data);

//$pattern = array ("/[\-]+\s*([\w\s<>=\"\?.\/\r\n]+)[\r\n]?/i");
//$replacement = array ("<item>\${1}</item>");
//$data=preg_replace($pattern, $replacement, $data);
$output.='<list'.$attrs.'>'.$data.'</list>';
$index.=' '.$text['text'];
?>