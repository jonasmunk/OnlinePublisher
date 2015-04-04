<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Finder
 */
require_once '../../Include/Private.php';

$writer = new ItemsWriter();

$writer->startItems();

$writer->item(array(
	'value' => 'all',
	'title' => array('All','da'=>'Alle'),
	'icon' => 'common/files',
	'kind' => 'all'
));

$writer->endItems();
?>