<?php
/**
 * @package OnlinePublisher
 * @subpackage Customers
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Interface/In2iGui.php';
require_once '../../Classes/Model/Object.php';
require_once '../../Classes/Core/Request.php';
require_once '../../Classes/Utilities/DateUtils.php';
require_once '../../Classes/Utilities/StringUtils.php';

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

$writer = new ListWriter();

$writer->startList()->
	sort($sort,$direction)->
	window(array('total'=>$list['total'],'size'=>$list['windowSize'],'page'=>$list['windowPage']))->
	startHeaders()->
		header(array('title'=>array('Title','da'=>'Titel'),'key'=>'title','sortable'=>true))->
		header(array('title'=>array('Note','da'=>'Notat')));
	if ($type=='') {
		$writer->header(array('title'=>'Type','key'=>'title','sortable'=>true));
	}
	$writer->header(array('title'=>array('Modified','da'=>'Ændret'),'key'=>'updated','sortable'=>true));
	$writer->endHeaders();

foreach ($objects as $object) {
	echo '<row id="'.$object->getId().'" kind="'.$object->getType().'" icon="'.$object->getIn2iGuiIcon().'" title="'.StringUtils::escapeXML($object->getTitle()).'">'.
	'<cell icon="'.$object->getIn2iGuiIcon().'">'.StringUtils::escapeXML($object->getTitle()).'</cell>'.
	'<cell>'.StringUtils::escapeXML($object->getNote()).'</cell>'.
	($type=='' ? '<cell>'.$object->getType().'</cell>' : '').
	'<cell>'.DateUtils::formatDateTime($object->getCreated()).'</cell>'.
	'</row>';
}

$writer->endList();
?>