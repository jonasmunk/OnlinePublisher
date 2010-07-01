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
require_once '../../../Classes/UserInterface.php';

$type = Request::getString('type');
$queryString = Request::getString('query');
$windowSize = Request::getInt('windowSize',30);
$windowNumber = Request::getInt('windowNumber',1);

header('Content-Type: text/xml;');
echo '<?xml version="1.0"?>';
echo '<list>';

$query = array('windowSize' => $windowSize,'windowNumber' => $windowNumber);

if ($type!='') $query['type'] = $type;
if ($queryString!='') $query['query'] = $queryString;

$list = Object::find($query);
$objects = $list['result'];
echo '<window total="'.$list['total'].'" size="'.$list['windowSize'].'" number="'.$list['windowNumber'].'"/>';
foreach ($objects as $object) {
	echo '<row uid="'.$object->getId().'" kind="'.$object->getType().'">'.
	'<cell icon="common/folder">'.In2iGui::escape($object->getTitle()).'</cell>'.
	'<cell>'.In2iGui::escape($object->getNote()).'</cell>'.
	'<cell>'.UserInterface::presentDateTime($object->getCreated()).'</cell>'.
	'</row>';
}

echo '</list>';
?>