<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Include/Private.php';

$writer = new ItemsWriter();

$writer->startItems();

$counts = FileService::getGroupCounts();
foreach ($counts as $row) {
	$options = array(
		'value'=>$row['id'],
		'title'=>$row['title'],
		'icon'=>'common/folder',
		'kind'=>'filegroup'
	);
	if ($row['count']>0) {
		$options['badge']=$row['count'];
	}
	$writer->startItem($options)->endItem();
}

$writer->endItems();
?>


