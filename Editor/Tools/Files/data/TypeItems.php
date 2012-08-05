<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Include/Private.php';

$counts = File::getTypeCounts();

$types = array();
foreach ($counts as $row) {
	if ($row['type']!='') {
		$info = FileService::mimeTypeToInfo($row['type']);
		if ($info) {
			$kind = $info['kind'];
			if (array_key_exists($kind,$types)) {
				$types[$kind]['count']+=$row['count'];
			} else {
				$types[$kind] = array('count' => $row['count'], 'label' => $info['label']);
			}
		}
	}
} 

$writer = new ItemsWriter();

$writer->startItems();
foreach ($types as $kind => $info) {
	$writer->startItem(array(
		'title' => $info['label'],
		'value' => $kind,
		'icon' => 'file/generic',
		'badge' => $info['count']
	))->endItem();
}
$writer->endItems();
?>


