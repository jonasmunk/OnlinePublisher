<?php
/**
 * @package OnlinePublisher
 * @subpackage Customers
 */
require_once '../../Include/Private.php';

$type = Request::getString('type');

header('Content-Type: text/xml;');
echo '<?xml version="1.0"?>';
echo '<checkboxes>';

$objects = Query::after($type)->orderBy('title')->get();
foreach ($objects as $object) {
	echo '<checkbox value="'.$object->getId().'" label="'.In2iGui::escape($object->getTitle()).'"/>';
}

echo '</checkboxes>';
?>