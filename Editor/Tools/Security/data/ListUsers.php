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
		->header(['title'=>['Name','da'=>'Navn'],'width'=>40])
		->header(['title'=>['Username','da'=>'Brugernavn']])
		->header(['title'=>['E-mail','da'=>'E-post']])
		->header(['title'=>['Language','da'=>'Sprog']])
		->header(['title'=>['Internal','da'=>'Intern'],'align'=>'center'])
		->header(['title'=>['External','da'=>'Ekstern'],'align'=>'center'])
		->header(['title'=>'Administrator','align'=>'center'])
	->endHeaders();

$list = Query::after('user')->withText($text)->get();
foreach ($list as $item) {
	$writer->startRow(array('kind'=>'user','id'=>$item->getId()))->
		startCell([ 'icon' => $item->getIcon() ])->text($item->getTitle())->endCell()->
		startCell()->text($item->getUsername())->endCell()->
		startCell()->text($item->getEmail())->endCell()->
		startCell()->text($item->getLanguage())->endCell()->
		startCell([ 'align'=>'center' ]);
	if ($item->getInternal()) {
		$writer->startIcons()->icon('monochrome/checkmark')->endIcons();
	}
	$writer->endCell()->startCell([ 'align'=>'center' ]);
	if ($item->getExternal()) {
		$writer->startIcons()->icon('monochrome/checkmark')->endIcons();
	}
	$writer->endCell()->startCell(['align'=>'center']);
	if ($item->getAdministrator()) {
		$writer->startIcons()->icon('monochrome/checkmark')->endIcons();
	}
	$writer->endCell();
	$writer->endRow();
}
$writer->endList();
?>