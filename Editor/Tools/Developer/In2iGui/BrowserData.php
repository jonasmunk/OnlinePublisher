<?php
/**
 * @package OnlinePublisher
 * @subpackage Developer
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Include/Functions.php';
require_once '../../../Classes/In2iGui.php';
require_once '../../../Classes/Object.php';
require_once '../../../Classes/Request.php';

$type = Request::getString('type');

header('Content-Type: text/xml;');
echo '<?xml version="1.0"?>';
echo '<browser>';
if ($type!='') {
	$query = array();
	$query['type'] = $type;
	$objects = Object::search($query);
	foreach ($objects as $object) {
		echo '<element title="'.In2iGui::escape($object->getTitle()).'" source="BrowserData.php?type=design"/>';
	}
} else {
	echo '<element title="Billeder" source="BrowserData.php?type=image"/>';
	echo '<element title="Filer" source="BrowserData.php?type=file"/>';
	echo '<element title="Nyheder" source="BrowserData.php?type=news"/>';
	echo '<element title="Personer" source="BrowserData.php?type=person"/>';
}
echo '</browser>';
?>