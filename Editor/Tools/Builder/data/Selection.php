<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Builder
 */
require_once '../../../Include/Private.php';


$streams = Query::after('stream')->get();
$workflows = Query::after('workflow')->orderByTitle()->get();

$writer = new ItemsWriter();
$writer->startItems();
$writer->item([
	'icon' => 'common/folder',
	'value' => 'sources',
	'title' => ['Sources', 'da' => 'Kilder'],
	'kind' => 'category'
]);
$writer->item([
	'icon' => 'common/folder',
	'value' => 'views',
	'title' => ['Views', 'da' => 'Visninger'],
	'kind' => 'category'
]);
$writer->item([
  'icon' => 'common/folder',
  'value' => 'listeners',
  'title' => ['Listeners', 'da' => 'Observatører'],
  'kind' => 'category'
]);
$writer->title(['Streams', 'da' => 'Strømme']);
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