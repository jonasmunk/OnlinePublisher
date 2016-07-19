<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Builder
 */
require_once '../../../Include/Private.php';


$workflows = Query::after('workflow')->orderByTitle()->get();

$writer = new ItemsWriter();
$writer->startItems();
foreach ($workflows as $item) {
	$writer->item(array(
		'value' => $item->getId(),
		'text' => $item->getTitle()
	));
}
$writer->endItems();