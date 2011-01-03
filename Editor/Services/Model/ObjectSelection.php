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

$type = Request::getString('type');

header('Content-Type: text/xml;');
echo '<?xml version="1.0"?>';
echo '<selection>';

$query = array('type'=>$type);

$list = Object::find($query);
$objects = $list['result'];
foreach ($objects as $object) {
	echo '<item value="'.$object->getId().'" kind="'.$type.'" icon="'.$object->getIn2iGuiIcon().'" title="'.In2iGui::escape($object->getTitle()).'"/>';
}

echo '</selection>';
?>