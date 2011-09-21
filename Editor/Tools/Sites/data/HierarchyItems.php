<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../Include/Private.php';

$writer = new ItemsWriter();

$hierarchies = Hierarchy::search();

$writer->startItems();
foreach ($hierarchies as $hierarchy) {
	$writer->item(array(
		'icon' => 'common/hierarchy',
		'value' => $hierarchy->getId(),
		'title' => $hierarchy->getName(),
		'kind' => 'hierarchy'
	));
}
$writer->endItems();