<?
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
$sql="select * from document_richtext where section_id=".$id;
$text = Database::selectFirst($sql);
$output.='<richtext><![CDATA[';
$output.=$text['data'];
$output.=']]></richtext>';
//$index.=' '.$text['text']; TODO
?>