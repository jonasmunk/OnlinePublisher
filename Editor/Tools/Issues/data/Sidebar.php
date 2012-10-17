<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Issues
 */
require_once '../../../Include/Private.php';



$kinds = IssueService::getKindCounts();

$total = 0;
foreach ($kinds as $row) {
	$total+=$row['count'];
}

$writer = new ItemsWriter();

$writer->startItems();

$writer->item(array('title'=>array('All issues','da'=>'Alle sager'),'value'=>'all','icon'=>'view/list','badge'=>$total));


if ($kinds) {
	$writer->title(array('Types','da'=>'Typer'));
}
foreach ($kinds as $row) {
	$writer->item(array(
		'title' => IssueService::translateKind($row['kind']),
		'icon' => 'view/list',
		'badge' => $row['count'],
		'kind' => 'kind',
		'value' => $row['kind']
	));
}

$statuses = Query::after('issuestatus')->get();


$writer->title(array('Status','da'=>'Status'));

foreach ($statuses as $status) {
	$writer->item(array(
		'title'=>$status->getTitle(),
		'value'=>$status->getId(),
		'icon'=>'common/object',
		'kind' => $status->getType()
	));
}

$writer->item(array(
	'title'=>array('No status','da'=>'Ingen status'),
	'value'=>'nostatus',
	'icon'=>'monochrome/round_question'
));
$writer->endItems();
?>