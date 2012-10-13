<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Issues
 */
require_once '../../../Include/Private.php';

$list = Query::after('issue')->get();

//$list = array();

$writer = new ItemsWriter();

$writer->startItems();

$writer->item(array('title'=>array('All issues','da'=>'Alle sager'),'value'=>'all','icon'=>'view/list'));


$kinds = IssueService::getKindCounts();
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

$writer->endItems();
?>