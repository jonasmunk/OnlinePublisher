<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Include/Private.php';

$subset = Request::getString('subset');
$group = Request::getInt('group',null);

if ($subset=='pages') {
  listPages();
} else if ($subset=='products') {
  listProducts();
} else if ($subset=='persons') {
  listPersons();
} else if ($group !== null && $group > 0) {
  listGroup($group);
} else {
  listImages();
}

function listGroup($group) {

  InternalSession::setToolSessionVar('images','group',$group);
  $text = Request::getString('text');
  $sort = Request::getString('sort','position');
  $direction = Request::getString('direction','ascending');

  $writer = new ListWriter();
  $writer->
  startList(['unicode'=>true,'checkboxes'=>true])->
    sort($sort,$direction)->
    //window(['total'=>$result->getTotal(),'size'=>$windowSize,'page'=>$windowPage])->
    startHeaders()->
      header(['title'=>['Image', 'da'=>'Billede'], 'width'=>40, 'key' => 'object.title', 'sortable' => true])->
      header(['title'=>['Size', 'da'=>'Størrelse'], 'key' => 'image.size', 'sortable' => true])->
      header(['title'=>['Height', 'da'=>'Højde'], 'key' => 'image.height', 'sortable' => true])->
      header(['title'=>['Width', 'da'=>'Bredde'], 'key' => 'image.width', 'sortable' => true])->
      header(['title'=>'Type'])->
      header(['title' => 'Position', 'width' => 1, 'key' => 'position', 'sortable' => true])->
    endHeaders();

  $moveUp = $direction == 'ascending';
  $images = ImageService::search(['direction'=>$direction, 'sort' => $sort, 'text' => $text, 'group' => $group]);
  foreach ($images as $row) {
    $writer->
    startRow(array('kind'=>'image','id'=>$row['id'],'icon'=>'common/image','title'=>$row['title']))->
      startCell(array('icon'=>'common/image'))->
        startLine()->text($row['title'])->endLine()->
      endCell()->
      startCell()->
        text(GuiUtils::bytesToString($row['size']))->
      endCell()->
      startCell()->
        text($row['height'])->
      endCell()->
      startCell()->
        text($row['width'])->
      endCell()->
      startCell()->
        text(FileService::mimeTypeToLabel($row['type']))->
      endCell()->
      startCell(['wrap'=>false])->
        text($row['position']);
    if ($sort == 'position') {
      $writer->startIcons()->
        icon(['icon'=>'monochrome/round_arrow_up','revealing'=>true,'action'=>true,'data'=>['action'=>'moveItem','direction'=>$moveUp ? 'up' : 'down','group'=>$group]])->
        icon(['icon'=>'monochrome/round_arrow_down','revealing'=>true,'action'=>true,'data'=>['action'=>'moveItem','direction'=>$moveUp ? 'down' : 'up','group'=>$group]])->
      endIcons();
    }
    $writer->endCell()->endRow();
  }

  $writer->endList();
}

function listImages() {

  $subset = Request::getString('subset');
  $group = Request::getInt('group',null);
  InternalSession::setToolSessionVar('images','group',$group);
  $text = Request::getString('text');
  $windowSize = Request::getInt('windowSize',30);
  $windowPage = Request::getInt('windowPage',0);
  $sort = Request::getString('sort','object.title');
  $direction = Request::getString('direction','ascending');

  $isGroup = $group !== null && $group > 0;

  $query = Query::after('image')->withText($text)->withWindowSize($windowSize)->withWindowPage($windowPage)->withDirection($direction)->orderBy($sort);
  if ($sort == 'imagegroup_image.position') {
    $query->orderBy('object.title');
  }
  if ($group === -1) {
    $query->withCustom('nogroup',true);
  } else if ($group) {
    $query->withCustom('group',$group);
  }
  if ($subset=='latest') {
    $query->withCustom('createdAfter',Dates::addDays(time(),-1));
  } else if ($subset=='unused') {
    $query->withCustom('unused',true);
  }
  $positions = [];
  if ($isGroup) {
    $sql = "select image_id, position from imagegroup_image where imagegroup_id=@int(group)";
    $positions = Database::selectMap($sql,['group' => $group]);
  }

  $result = $query->search();
  $list = $result->getList();

  $writer = new ListWriter();

  $writer->
  startList(['unicode'=>true,'checkboxes'=>true,'ordering'=>$isGroup])->
    sort($sort,$direction)->
    window(['total'=>$result->getTotal(),'size'=>$windowSize,'page'=>$windowPage])->
    startHeaders()->
      header(['title'=>['Image', 'da'=>'Billede'], 'width'=>40, 'key' => 'object.title', 'sortable' => true])->
      header(['title'=>['Size', 'da'=>'Størrelse'], 'key' => 'image.size', 'sortable' => true])->
      header(['title'=>['Height', 'da'=>'Højde'], 'key' => 'image.height', 'sortable' => true])->
      header(['title'=>['Width', 'da'=>'Bredde'], 'key' => 'image.width', 'sortable' => true])->
      header(['title'=>'Type']);
  if ($isGroup) {
    $writer->header(['title' => 'Position', 'width' => 1, 'key' => 'imagegroup_image.position', 'sortable' => true]);
  }
  $writer->endHeaders();


  foreach ($list as $image) {
    $writer->
    startRow(array('kind'=>'image','id'=>$image->getId(),'icon'=>$image->getIcon(),'title'=>$image->getTitle()))->
      startCell(array('icon'=>'common/image'))->
        startLine()->text($image->getTitle())->endLine()->
      endCell()->
      startCell()->
        text(GuiUtils::bytesToString($image->getSize()))->
      endCell()->
      startCell()->
        text($image->getHeight())->
      endCell()->
      startCell()->
        text($image->getWidth())->
      endCell()->
      startCell()->
        text(FileService::mimeTypeToLabel($image->getMimetype()))->
      endCell();
    if ($isGroup) {
      $writer->startCell(['wrap'=>false])->
        text($positions[$image->getId()])->
        startIcons()->
          icon(['icon'=>'monochrome/round_arrow_up','revealing'=>true,'action'=>true,'data'=>['action'=>'moveItem','direction'=>'up','group'=>$group]])->
          icon(['icon'=>'monochrome/round_arrow_down','revealing'=>true,'action'=>true,'data'=>['action'=>'moveItem','direction'=>'down','group'=>$group]])->
        endIcons()->
      endCell();
    }
    $writer->endRow();
  }

  $writer->endList();
}

function listProducts() {

  $writer = new ListWriter();

  $writer->
  startList(array('unicode'=>true))->
    startHeaders()->
      header(array('title'=>array('Image','da'=>'Billede'),'width'=>40))->
      header(array('title'=>array('Product','da'=>'Produkt')))->
    endHeaders();

  $result = ImageService::getProductImageRelations();

  foreach ($result as $row) {
    $writer->
    startRow(array('kind'=>'image','id'=>$row['image_id'],'icon'=>'common/image','title'=>$row['image_title']))->
      startCell(array('icon'=>'common/image'))->
        startLine()->text($row['image_title'])->endLine()->
      endCell()->
      startCell(array('icon'=>'common/product'))->
        text($row['product_title'])->
        /*startIcons()->
          icon(array(
            'icon' => 'monochrome/info_light',
            'action' => true,
            'data' => array('action' => 'editProduct','id' => $row['product_id']),
            'revealing' => true)
          )->
        endIcons()->*/
      endCell()->
    endRow();
  }

  $writer->endList();
}

function listPersons() {

  $writer = new ListWriter();

  $writer->
  startList(array('unicode'=>true))->
    startHeaders()->
      header(array('title'=>array('Image','da'=>'Billede'),'width'=>40))->
      header(array('title'=>'Person'))->
    endHeaders();

  $result = ImageService::getPersonImageRelations();

  foreach ($result as $row) {
    $writer->
    startRow(array('kind'=>'image','id'=>$row['image_id'],'icon'=>'common/image','title'=>$row['image_title']))->
      startCell(array('icon'=>'common/image'))->
        startLine()->text($row['image_title'])->endLine()->
      endCell()->
      startCell(array('icon'=>'common/person'))->
        text($row['person_title'])->
        startIcons()->
          icon(array(
            'icon' => 'monochrome/info_light',
            'action' => true,
            'data' => array('action' => 'editPerson','id' => $row['person_id']),
            'revealing' => true)
          )->
        endIcons()->
      endCell()->
    endRow();
  }

  $writer->endList();
}

function listPages() {
  $parts = PartService::getParts();

  $writer = new ListWriter();

  $writer->
  startList(array('unicode'=>true))->
    startHeaders()->
      header(array('title'=>array('Image','da'=>'Billede'),'width'=>40))->
      header(array('title'=>array('Page','da'=>'Side')))->
      header(array('title'=>array('Section','da'=>'Afsnit')))->
    endHeaders();

  $result = ImageService::getPageImageRelations();

  foreach ($result as $row) {
    $writer->
    startRow(array('kind'=>'image','id'=>$row['image_id'],'icon'=>'common/image','title'=>$row['image_title']))->
      startCell(array('icon'=>'common/image'))->
        startLine()->text($row['image_title'])->endLine()->
      endCell()->
      startCell(array('icon'=>'common/page'))->
        text($row['page_title'])->
        badge($row['template'])->
        startIcons()->
          icon(array(
            'icon' => 'monochrome/edit',
            'action' => true,
            'data' => array('action' => 'editPage','id' => $row['page_id']),
            'revealing' => true)
          )->
        endIcons()->
      endCell()->
      startCell()->text($parts[$row['part']]['name']['da'])->endCell()->
    endRow();
  }

  $writer->endList();
}
?>