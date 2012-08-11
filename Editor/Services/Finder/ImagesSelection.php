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

$writer->title(array('Groups','da'=>'Grupper'));

$groups = ImageService::getGroupCounts();

foreach ($groups as $group) {
	$options = array(
		'value' => $group['id'],
		'title' => $group['title'],
		'icon' => 'common/folder',
		'kind' => 'imagegroup'
	);
	if ($group['count']>0) {
		$options['badge'] = $group['count'];
	}
	$writer->startItem($options)->endItem();	
}

$writer->endItems();
?>


