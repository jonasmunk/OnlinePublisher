<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Calendars
 */
require_once '../../../Include/Private.php';

$kind = Request::getString('kind');
$value = Request::getString('value');

if ($kind=='stream') {
  listStreamItems($value);
} else if ($kind=='category' && $value=='sources') {
  listSources();
} else if ($kind=='category' && $value=='views') {
  listViews();
} else if ($kind=='category' && $value=='listeners') {
  listListeners();
}


function listStreamItems($streamId) {
  $items = Query::after('streamitem')->withProperty(Streamitem::$STREAM_ID,$streamId)->orderBy('originalDate')->descending()->get();

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
      ->startCell()->startLine()->text($item->getIdentity())->endLine()->startWrap()->text($item->getData())->endWrap()->endCell()
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

function listListeners() {
  $items = Query::after('listener')->orderBy('title')->get();
  $writer = new ListWriter();
  $writer->startList()
    ->startHeaders()
      ->header(['title'=>['Title','da'=>'Titel']])
      ->header(['title'=>['Event','da'=>'Begivenhed']])
      ->header(['title'=>['Latest execution','da'=>'Seneste afvikling']])
      ->header(['title'=>['Interval']])
      ->header(['title'=>['Runnable','da'=>'Eksekvérbar']])
    ->endHeaders();

  foreach ($items as $item) {

    $flow = Query::after('workflow')->withRelationFrom($item)->first();
    $writer
      ->startRow(['kind'=>$item->getType(),'id'=>$item->getId()])
      ->startCell()->text($item->getTitle())->endCell()
      ->startCell()->text($item->getEvent())->endCell()
      ->startCell(['wrap'=>false])
        ->text(Dates::formatFuzzy($item->getLatestExecution()))
      ->endCell()
      ->startCell()->text($item->getInterval())->endCell()
      ->startCell()->text($flow ? $flow->getTitle() : '-')->endCell()
    ->endRow();
  }
  $writer->endList();
}

function listViews() {
  $items = Query::after('view')->orderBy('title')->get();
  $writer = new ListWriter();
  $writer->startList()
    ->startHeaders()
      ->header(['title'=>['Title','da'=>'Titel']])
      ->header(['title'=>['Path','da'=>'Sti']])
    ->endHeaders();

  foreach ($items as $item) {
    $writer
      ->startRow(['kind'=>'view','id'=>$item->getId()])
      ->startCell()->text($item->getTitle())->endCell()
      ->startCell()->text($item->getPath())->endCell()
    ->endRow();
  }
  $writer->endList();
}