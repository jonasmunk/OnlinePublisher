<?
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
$sql="select * from document_image where section_id=".$id;
if ($image= Database::selectFirst($sql)) {
	$imageAtts="";
	if ($image['align']!='') {
		$imageAtts.=' align="'.$image['align'].'"';
	}
	$sql="select object.data from object where id=".$image['image_id'];
	if ($img = Database::selectFirst($sql)) {
		$output.=
		'<image'.$imageAtts.'>'.
		$img['data'].
		'</image>';
	}
}
?>