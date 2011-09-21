<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';

$list = Frame::search();

$writer = new ListWriter();

$writer->startList()->
	startHeaders()->
	header(array('title'=>'Navn'))->
	header(array('title'=>'Titel'))->
	header(array('title'=>'Hierarki'))->
endHeaders();

foreach ($list as $object) {
	$hierarchy = Hierarchy::load($object->getHierarchyId());
	$writer->startRow(array( 'kind'=>'frame', 'id'=>$object->getId()))->
		startCell()->text($object->getName())->endCell()->
		startCell()->text($object->getTitle())->endCell()->
		startCell()->text($hierarchy ? $hierarchy->getName() : '!! findes ikke !!')->endCell()->
	endRow();
}
$writer->endList();
?>