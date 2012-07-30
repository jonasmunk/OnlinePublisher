<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Start
 */
require_once '../../../Include/Private.php';

$list = Query::after('issue')->get();

//$list = array();

$writer = new ListWriter();

$writer->startList();

foreach($list as $item) {
	$page = PageQuery::getRows()->withRelationFrom($item)->first();
	$writer->startRow()->
		startCell(array('variant'=>'card'));
		$writer->startLine(array('mini'=>false))->text(StringUtils::shortenString($item->getNote(),300))->endLine();
		$writer->startLine(array('dimmed'=>true,'mini'=>true))->text(IssueService::translateKind($item->getKind()))->endLine();
		if ($page) {
			$writer->startLine(array('class'=>'task_page'))->
				object(array('icon'=>'common/page','text'=>$page['title']))->
				startIcons()->
					icon(array('icon'=>'monochrome/view','action'=>true,'revealing'=>true,'data'=>array('id'=>$page['id'],'action'=>'view')))->
					icon(array('icon'=>'monochrome/edit','action'=>true,'revealing'=>true,'data'=>array('id'=>$page['id'],'action'=>'edit')))->
				endIcons()->
			endLine();
		}
		$writer->endCell()->
	endRow();
}
$writer->endList();
?>