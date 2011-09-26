<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Services/FileService.php';
require_once '../../Classes/Interface/In2iGui.php';
require_once '../../Classes/Objects/File.php';

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


