<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Issues
 */
require_once '../../../Include/Private.php';

$writer = new ItemsWriter();

$writer->startItems();

$statuses = Query::after('issuestatus')->get();


$writer->item(array(
	'title'=>''
));

foreach ($statuses as $status) {
	$writer->item(array(
		'title'=>$status->getTitle(),
		'value'=>$status->getId()
	));
}
$writer->endItems();
?>