<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Include/Private.php';

$notInGroup = ImageService::getNumberOfImagesNotInGroup();
$groups = ImageService::getGroupCounts();

$writer = new ItemsWriter();

$writer->startItems();

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

if ($notInGroup>0) {
	$options = array(
		'value'=>-1,
		'title'=>'Ikke i gruppe',
		'icon'=>'common/folder_grey',
		'kind'=>'nogroup',
		'badge' => $notInGroup,
	);
	$writer->startItem($options)->endItem();
}

$writer->endItems();
?>


