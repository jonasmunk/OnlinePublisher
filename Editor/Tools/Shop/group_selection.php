<?php
/**
 * @package OnlinePublisher
 * @subpackage Customers
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/Object.php';
require_once '../../Classes/Request.php';

header('Content-Type: text/xml;');
echo '<?xml version="1.0"?>';
echo '<selection>';

$query = array('type'=>'productgroup');

$list = Object::find($query);
$objects = $list['result'];
foreach ($objects as $object) {
	echo '<item value="'.$object->getId().'" kind="persongroup" icon="common/folder" title="'.In2iGui::escape($object->getTitle()).'"/>';
}

echo '</selection>';
?>