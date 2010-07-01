<?
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
$sql="select * from document_news where section_id=".$id;
$news= Database::selectFirst($sql);
if ($news) {
	$output.='<!--NEWS#'.$news['id'].'-->';
}
$dynamic=true;
?>