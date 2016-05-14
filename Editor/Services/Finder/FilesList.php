<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Finder
 */
require_once '../../Include/Private.php';

$queryString = Request::getString('query');
$windowSize = 20;//Request::getInt('windowSize',30);
$windowPage = Request::getInt('windowPage',0);
$sort = Request::getString('sort','title');
$direction = Request::getString('direction','ascending');
$group = Request::getInt('group',0);

$query = Query::after('file')->
    withWindowSize($windowSize)->
    withWindowPage($windowPage)->
    withDirection($direction)->
    orderBy($sort)->
    withText($queryString);

if ($group) {
	$query->withCustom('group',$group);
}
$result = $query->search();

$objects = $result->getList();

$writer = new ListWriter();

$writer->startList()->
	sort($sort,$direction)->
	window([
        'total' => $result->getTotal(),
        'size' => $result->getWindowSize(),
        'page' => $result->getWindowPage()
    ])->
	startHeaders()->
		header(['title' => ['Title','da'=>'Titel'],'key' => 'title','sortable' => true])->
    	header(['title' => ['Type','da'=>'Type'],'width' => 1,'key' => 'file.type','sortable' => true])->
	endHeaders();
	foreach ($objects as $object) {
		$writer->startRow([
            'id' => $object->getId(),
            'kind' => $object->getType(),
            'icon' => $object->getIcon(),
            'title' => $object->getTitle()
        ])->
			startCell(['icon' => $object->getIcon()])->startWrap()->text($object->getTitle())->endWrap()->endCell()->
			startCell(['wrap' => false])->text(FileService::mimeTypeToLabel($object->getMimeType()))->endCell()->
		endRow();
	}
$writer->endList();
?>