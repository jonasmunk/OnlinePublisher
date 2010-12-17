<?php
/**
 * @package OnlinePublisher
 * @subpackage Customers
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/Database.php';
require_once '../../Classes/Object.php';
require_once '../../Classes/Request.php';

$type = Request::getString('type');
$text = Request::getString('query');

header('Content-Type: text/xml;');
echo '<?xml version="1.0"?>';
echo '<items>';

if ($type=='page') {
	$sql = "select page.id,page.title from page order by page.title";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		echo '<item title="'.encodeXML($row['title']).'" value="'.$row['id'].'"/>';
	}
	Database::free($result);
} else {
	$query = array('type'=>$type,'query'=>$text);
	$list = Object::find($query);
	$objects = $list['result'];
	$objects = Query::after($type)->withText($text)->search()->getList();
	foreach ($objects as $object) {
		echo '<item value="'.$object->getId().'" kind="'.$type.'" icon="'.$object->getIn2iGuiIcon().'" title="'.In2iGui::escape($object->getTitle()).'"/>';
	}
}

echo '</items>';
?>