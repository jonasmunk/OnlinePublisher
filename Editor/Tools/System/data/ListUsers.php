<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Include/Private.php';

$writer = new ListWriter();

$writer->startList();
$writer->startHeaders();
$writer->header(array('title'=>'Navn','width'=>40));
$writer->header(array('title'=>'Brugernavn'));
$writer->header(array('title'=>'E-mail'));
$writer->header(array('title'=>'Sprog'));
$writer->header(array('title'=>'Intern'));
$writer->header(array('title'=>'Ekstern'));
$writer->header(array('title'=>'Administrator'));
$writer->endHeaders();

$list = Object::find(array('type'=>'user'));
foreach ($list['result'] as $item) {
	$writer->startRow(array('kind'=>'user','id'=>$item->getId()));
	$writer->startCell(array('icon'=>$item->getIn2iGuiIcon()))->text($item->getTitle())->endCell();
	$writer->startCell()->text($item->getUsername())->endCell();
	$writer->startCell()->text($item->getEmail())->endCell();
	$writer->startCell()->text($item->getLanguage())->endCell();
	$writer->startCell()->text($item->getInternal() ? 'Ja' : 'Nej')->endCell();
	$writer->startCell()->text($item->getExternal() ? 'Ja' : 'Nej')->endCell();
	$writer->startCell()->text($item->getAdministrator() ? 'Ja' : 'Nej')->endCell();
	$writer->endRow();
}
$writer->endList();
?>