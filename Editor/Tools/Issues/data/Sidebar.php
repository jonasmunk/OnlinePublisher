<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Issues
 */
require_once '../../../Include/Private.php';

$writer = new ItemsWriter();

$writer->startItems();

$writer->item(array(
	'title' => array('Issues','da'=>'Sager'),
	'value' => 'all','icon'=>'view/list',
	'badge' => IssueService::getTotalIssueCount()
));

$writer->item(array(
	'title' => array('Feedback','da'=>'Feedback'),
	'value' => 'babbelab',
	'kind' => 'feedback',
	'icon' => 'view/list'
));

$writer->endItems();
?>