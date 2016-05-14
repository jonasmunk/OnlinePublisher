<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Issues
 */
require_once '../../../Include/Private.php';

$kinds = IssueService::getKindCounts();

$writer = new ItemsWriter();

$writer->startItems();

if ($kinds) {
	$writer->title(array('Types','da'=>'Typer'));
	$writer->item(array('title'=>array('Any type','da'=>'Enhver type'),'value'=>'any','icon'=>'view/list'));
	foreach ($kinds as $row) {
		$writer->item(array(
			'title' => $row['kind'] ? IssueService::translateKind($row['kind']) : array('No type','da'=>'Ingen type'),
			'icon' => 'view/list',
			'badge' => $row['count'],
			'kind' => 'kind',
			'value' => $row['kind']
		));
	}
}
$writer->endItems();
?>