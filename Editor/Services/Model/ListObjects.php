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
require_once '../../Classes/UserInterface.php';

$type = Request::getString('type');
$queryString = Request::getUnicodeString('query');
$windowSize = Request::getInt('windowSize',30);
$windowPage = Request::getInt('windowPage',0);
$sort = Request::getString('sort');
$direction = Request::getString('direction');
if ($sort=='') $sort='title';
if ($direction=='') $direction='ascending';

$query = array('windowSize' => $windowSize,'windowPage' => $windowPage,'sort' => $sort,'direction' => $direction);

if ($type!='') $query['type'] = $type;
if ($queryString!='') $query['query'] = $queryString;

$list = Object::find($query);
$objects = $list['result'];
header('Content-Type: text/xml;');
echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<list>';
echo '<sort key="'.$sort.'" direction="'.$direction.'"/>';
echo '<window total="'.$list['total'].'" size="'.$list['windowSize'].'" page="'.$list['windowPage'].'"/>';
echo '<headers>'.
'<header title="Titel" width="30" key="title" sortable="true"/>'.
'<header title="Notat" width="30"/>'.
($type=='' ? '<header title="Type" key="type" sortable="true"/>' : '').
'<header title="Ændringsdato" key="updated" sortable="true"/>'.
'</headers>';
foreach ($objects as $object) {
	echo '<row id="'.$object->getId().'" kind="'.$object->getType().'" icon="'.$object->getIn2iGuiIcon().'" title="'.In2iGui::escape($object->getTitle()).'">'.
	'<cell icon="'.$object->getIn2iGuiIcon().'">'.In2iGui::escape($object->getTitle()).'</cell>'.
	'<cell>'.In2iGui::escape($object->getNote()).'</cell>'.
	($type=='' ? '<cell>'.$object->getType().'</cell>' : '').
	'<cell>'.UserInterface::presentDateTime($object->getCreated()).'</cell>'.
	'</row>';
}

echo '</list>';
?>