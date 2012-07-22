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
	'title' => 'Alle',
	'icon' => 'common/files',
	'kind' => 'all'
));

$writer->title('Grupper');

$groups = FileService::getGroupCounts();

foreach ($groups as $group) {
	$options = array(
		'value' => $group['id'],
		'title' => $group['title'],
		'icon' => 'common/folder',
		'kind' => 'filegroup'
	);
	if ($group['filecount']>0) {
		$options['badge']=$group['filecount'];
	}
	$writer->startItem($options)->endItem();	
}

$writer->endItems();
?>


