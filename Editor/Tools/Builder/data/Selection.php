<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Builder
 */
require_once '../../../Include/Private.php';


$streams = Query::after('stream')->get();
$workflows = Query::after('workflow')->get();

$writer = new ItemsWriter();
$writer->startItems();
$writer->item(array(
	'icon' => 'common/folder',
	'value' => 'sources',
	'title' => 'Sources',
	'kind' => 'category'
));
$writer->title('Streams');
foreach ($streams as $item) {
	$writer->item(array(
		'icon' => $item->getIcon(),
		'value' => $item->getId(),
		'title' => $item->getTitle(),
		'kind' => $item->getType()
	));
}
$writer->title('Workflows');
foreach ($workflows as $item) {
	$writer->item(array(
		'icon' => $item->getIcon(),
		'value' => $item->getId(),
		'title' => $item->getTitle(),
		'kind' => $item->getType()
	));
}
$writer->endItems();