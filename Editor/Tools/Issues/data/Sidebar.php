<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Issues
 */
require_once '../../../Include/Private.php';



$kinds = IssueService::getKindCounts();
$states = IssueService::getStatusCounts();

$total = 0;
foreach ($kinds as $row) {
	$total+=$row['count'];
}

$writer = new ItemsWriter();

$writer->startItems();

$writer->item(array('title'=>array('All issues','da'=>'Alle sager'),'value'=>'all','icon'=>'view/list','badge'=>$total));

$writer->item(array('title'=>array('Feedback','da'=>'Feedback'),'value'=>'babbelab','kind'=>'feedback','icon'=>'view/list'));


if ($kinds) {
	$writer->title(array('Types','da'=>'Typer'));
}
foreach ($kinds as $row) {
	$writer->item(array(
		'title' => $row['kind'] ? IssueService::translateKind($row['kind']) : array('No type','da'=>'Ingen type'),
		'icon' => 'view/list',
		'badge' => $row['count'],
		'kind' => 'kind',
		'value' => $row['kind']
	));
}

$writer->title(array('Status','da'=>'Status'));

foreach ($states as $row) {
	$writer->item(array(
		'title' => $row['title'] ? $row['title'] : array('No status','da'=>'Ingen status'),
		'icon' => 'view/list',
		'badge' => $row['count'],
		'kind' => 'status',
		'value' => $row['id'] ? $row['id'] : 'nostatus'
	));
}

$writer->endItems();
?>