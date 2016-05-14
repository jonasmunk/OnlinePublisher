<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Include/Private.php';

$writer = new ListWriter();

$writer->startList();
$writer->startHeaders();
$writer->header(array('title'=>'Type','width'=>40));
$writer->header(array('title'=>'Cache'));
$writer->header(array('width'=>1));
$writer->endHeaders();

$cachedPages = CacheService::getNumberOfCachedPages();
$imageCache = CacheService::getImageCacheInfo();
$tempCache = CacheService::getTempCacheInfo();
$urlCache = CacheService::getUrlCacheInfo();

$writer->startRow();
$writer->startCell(array('icon'=>'common/page'))->text('Sider')->endCell();
$writer->startCell()->text($cachedPages.($cachedPages==1 ? ' side' : ' sider'))->endCell();
$writer->startCell()->button(array('text'=>'Ryd','data'=>array('type'=>'pages')))->endCell();
$writer->endRow();

$writer->startRow();
$writer->startCell(array('icon'=>'common/image'))->text('Billeder')->endCell();
$writer->startCell()->text($imageCache['count'].' billeder ('.GuiUtils::bytesToString($imageCache['size']).')')->endCell();
$writer->startCell()->button(array('text'=>'Ryd','data'=>array('type'=>'images')))->endCell();
$writer->endRow();

$writer->startRow();
$writer->startCell(array('icon'=>'file/generic'))->text('Midlertidige filer')->endCell();
$writer->startCell()->text($tempCache['count'].' filer ('.GuiUtils::bytesToString($tempCache['size']).')')->endCell();
$writer->startCell()->button(array('text'=>'Ryd','data'=>array('type'=>'temp')))->endCell();
$writer->endRow();

$writer->startRow();
$writer->startCell(array('icon'=>'common/internet'))->text('Internet adresser')->endCell();
$writer->startCell()->text($urlCache['count'].' filer ('.GuiUtils::bytesToString($urlCache['size']).')')->endCell();
$writer->startCell()->button(array('text'=>'Ryd','data'=>array('type'=>'urls')))->endCell();
$writer->endRow();


$writer->endList();
?>