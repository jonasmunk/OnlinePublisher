<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Security
 */
require_once '../../../Include/Private.php';

$text = Request::getString('text');

$writer = new ListWriter();

$writer->startList()
	->startHeaders()
		->header(array('title'=>array('Name','da'=>'Navn'),'width'=>40))
		->header(array('title'=>array('Username','da'=>'Brugernavn')))
		->header(array('title'=>array('E-mail','da'=>'E-post')))
		->header(array('title'=>array('Language','da'=>'Sprog')))
		->header(array('title'=>array('Internal','da'=>'Intern'),'align'=>'center'))
		->header(array('title'=>array('External','da'=>'Ekstern'),'align'=>'center'))
		->header(array('title'=>'Administrator','align'=>'center'))
	->endHeaders();

$list = Query::after('user')->withText($text)->get();
foreach ($list as $item) {
	$writer->startRow(array('kind'=>'user','id'=>$item->getId()));
	$writer->startCell(array('icon'=>$item->getIcon()))->text($item->getTitle())->endCell();
	$writer->startCell()->text($item->getUsername())->endCell();
	$writer->startCell()->text($item->getEmail())->endCell();
	$writer->startCell()->text($item->getLanguage())->endCell();
	$writer->startCell(array('align'=>'center'));
	if ($item->getInternal()) {
		$writer->startIcons()->icon('monochrome/checkmark')->endIcons();
	}
	$writer->endCell();
	$writer->startCell(array('align'=>'center'));
	if ($item->getExternal()) {
		$writer->startIcons()->icon('monochrome/checkmark')->endIcons();
	}
	$writer->endCell();
	$writer->startCell(array('align'=>'center'));
	if ($item->getAdministrator()) {
		$writer->startIcons()->icon('monochrome/checkmark')->endIcons();
	}
	$writer->endCell();
	$writer->endRow();
}
$writer->endList();
?>