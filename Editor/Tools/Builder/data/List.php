<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Calendars
 */
require_once '../../../Include/Private.php';

$kind = Request::getString('kind');
$value = Request::getString('value');

if ($kind=='stream') {
  listStreamItems();
} else if ($kind=='category' && $value=='sources') {
  listSources();
}


function listStreamItems() {
  $items = Query::after('streamitem')->orderBy('originalDate')->get();

  $writer = new ListWriter();

  $writer->startList()
    ->startHeaders()
      ->header(['title'=>['Data','da'=>'Data']])
      ->header(['title'=>['Date','da'=>'Dato']])
      ->header(['title'=>['Synched']])
    ->endHeaders();

  foreach ($items as $item) {
    $writer
      ->startRow(['kind'=>'streamitem','id'=>$item->getId()])
      ->startCell()->startWrap()->text($item->getData())->endWrap()->endCell()
      ->startCell(['wrap'=>false])
        ->text(Dates::formatLongDateTime($item->getOriginalDate()))
      ->endCell()
      ->startCell(['wrap'=>false])
        ->text(Dates::formatLongDateTime($item->getRetrievalDate()))
      ->endCell()
    ->endRow();
  }
  $writer->endList();
}

function listSources() {
  $items = Query::after('source')->orderBy('title')->get();
  $writer = new ListWriter();
  $writer->startList()
    ->startHeaders()
      ->header(['title'=>['Title','da'=>'Titel']])
      ->header(['title'=>['Address','da'=>'Adresse']])
      ->header(['title'=>['Synchronized','da'=>'Synkroniseret']])
      ->header(['title'=>['Interval']])
    ->endHeaders();

  foreach ($items as $item) {
    $writer
      ->startRow(['kind'=>'source','id'=>$item->getId()])
      ->startCell()->text($item->getTitle())->endCell()
      ->startCell()->text($item->getUrl())->endCell()
      ->startCell(['wrap'=>false])
        ->text(Dates::formatLongDateTime($item->getSynchronized()))
      ->endCell()
      ->startCell()->text($item->getInterval())->endCell()
    ->endRow();
  }
  $writer->endList();
}