<?php
/**
 * @package OnlinePublisher
 * @subpackage Customers
 */
require_once '../../../Include/Private.php';

$text = Request::getString('text');

$writer = new ListWriter();
if (StringUtils::isBlank($text)) {
	$writer->startList()->endList();
	exit;
}

$sql = "select id,title,'page' as kind from page where (`index` like ".Database::search($text)." or title like ".Database::search($text).")";
//$sql.=" union select id,title,type as kind from object where (`index` like ".Database::search($text)." or title like ".Database::search($text).") and (type='image' or type='file')";
$sql.=" order by title";

$result = Database::select($sql);

$icons = array('page' => 'common/page','file' => 'file/generic', 'image' => 'common/image');

$writer->startList();
	while ($row = Database::next($result)) {
		$icon = $icons[$row['kind']];
		
		$writer->startRow(array('id'=>$row['id'],'kind'=>$row['kind']))->
			startCell(array('icon'=>$icon))->startWrap()->text($row['title'])->endWrap()->endCell()->
			startCell(array('width'=>1));
			if ($row['kind']=='page') {
				$writer->startIcons()->
					icon(array('icon'=>'monochrome/edit','revealing'=>true,'action'=>true,'data'=>array('action'=>'view')))->
				endIcons();
			}
			$writer->endCell()->
		endRow();
	}
$writer->endList();

Database::free($result);
?>