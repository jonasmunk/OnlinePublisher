<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Include/Private.php';

$main = Request::getString('main');
$group = Request::getInt('group');
$type = Request::getString('type');
$queryString = Request::getString('query');
$windowSize = Request::getInt('windowSize',30);
$windowPage = Request::getInt('windowPage',0);
$sort = Request::getString('sort');
$direction = Request::getString('direction');
if (!$sort) {
	$sort='title';
}
if (!$direction) {
	$direction='ascending';
}

InternalSession::setToolSessionVar('files','group',$group);

$query = array('windowSize' => $windowSize,'windowPage' => $windowPage,'sort' => $sort,'direction' => $direction);

//if ($type!='') $query['type'] = $type;
if ($queryString!='') $query['query'] = $queryString;

if ($group>0) {
	$query['filegroup'] = $group;
}
if ($type) {
	$query['mimetypes'] = FileService::kindToMimeTypes($type);
}
if ($main=='latest') {
	$query['createdMin'] = Dates::addDays(time(),-1);
}

$list = File::find($query);
$objects = $list['result'];

$writer = new ListWriter();

$writer->startList();
$writer->sort($sort,$direction);
$writer->window(array( 'total' => $list['total'], 'size' => $windowSize, 'page' => $windowPage ));
$writer->startHeaders();
$writer->header(array('title'=>array('Title','da'=>'Titel'),'width'=>40));
$writer->header(array('title'=>'Type'));
$writer->header(array('title'=>array('Size','da'=>'Størrelse')));
$writer->header(array('title'=>array('Modified','da'=>'Ændret')));
$writer->endHeaders();

foreach ($objects as $object) {
	$writer->
	startRow(array('kind'=>'file','id'=>$object->getId(),'icon'=>$object->getIcon(),'title'=>$object->getTitle()))->
		startCell(array('icon'=>$object->getIcon()))->
			startLine()->startWrap()->text($object->getTitle())->endWrap()->endLine()->
		endCell()->
		startCell()->
			startLine(array('dimmed'=>true))->text(FileService::mimeTypeToLabel($object->getMimeType()))->endLine()->
			//startLine(array('dimmed'=>true))->text($object->getFilename())->endLine()->
		endCell()->
		startCell()->startLine(array('dimmed'=>true))->text(GuiUtils::bytesToString($object->getSize()))->endLine()->endCell()->
		startCell()->startLine(array('dimmed'=>true))->text(Dates::formatDateTime($object->getUpdated()))->endLine()->endCell()->
	endRow();
}
$writer->endList();
?>