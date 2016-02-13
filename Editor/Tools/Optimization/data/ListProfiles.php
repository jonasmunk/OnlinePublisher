<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Optimization
 */
require_once '../../../Include/Private.php';

$kind = Request::getString('kind');

$settings = OptimizationService::getSettings();


$writer = new ListWriter();

$writer->startList()->
	startHeaders()->
		header(array('title'=>array('Profile','da'=>'Profil'),'width'=>'30'))->
		header(array('title'=>array('Address','da'=>'Adresse')))->
		header(array('width'=>1))->
	endHeaders();

if (is_array($settings->profiles)) {
	foreach ($settings->profiles as $profile) {
	
		$writer->startRow()->
			startCell(array('icon'=>'common/page'))->text($profile->name)->endCell()->
			startCell()->
				text($profile->url)->
				startIcons()->
					icon(array('revealing'=>true,'icon'=>'monochrome/round_arrow_right','action'=>true,'data'=>array('action'=>'visit','url'=>$profile->url)))->
				endIcons()->
			endCell()->
			startCell()->
				startIcons()->
					icon(array('revealing'=>true,'icon'=>'monochrome/delete','action'=>true,'data'=>array('action'=>'delete','url'=>$profile->url)))->
				endIcons()->
			endCell()->
		endRow();
	}
}
$writer->endList();