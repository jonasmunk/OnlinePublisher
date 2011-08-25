<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Central
 */
require_once '../../../Include/Private.php';

$writer = new ListWriter();

$writer->startList();
$writer->startHeaders();
$writer->header(array('title'=>'Titel'));
$writer->header(array('title'=>'Adresse'));
$writer->header(array('title'=>'Version'));
$writer->header(array('title'=>'Skabeloner'));
$writer->endHeaders();

$objects = Query::after('remotepublisher')->orderBy('title')->get();
foreach ($objects as $site) {
	$data = RemoteDataService::getRemoteData($site->getUrl().'services/info/json/');
	$templates = 'Unknown';
	if ($data->isHasData()) {
		$str = file_get_contents($data->getFile());
		$obj = StringUtils::fromJSON($str);
		$version = DateUtils::formatLongDate($obj->date);
		if ($obj && property_exists($obj,'templates')) {
			$templates = StringUtils::toJSON($obj->templates);
		}
	} else {
		$version = 'Unknown';
	}
	$writer->startRow(array('kind'=>'remotepublisher','id'=>$site->getId()))->
		startCell(array('wrap'=>false))->text($site->getTitle())->endCell()->
		startCell()->text($site->getUrl())->endCell()->
		startCell(array('wrap'=>false))->text($version)->endCell()->
		startCell()->startWrap()->text($templates)->endWrap()->endCell()->
	endRow();
}
$writer->endList();