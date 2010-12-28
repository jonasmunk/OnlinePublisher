<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/Request.php';

$writer = new ItemsWriter();

$writer->startItems();
$writer->startItem(array('title'=>'Alle','badge'=>ImageService::getTotalImageCount(),'icon'=>'common/files','value'=>'all'))->endItem();
$writer->startItem(array('title'=>'Ikke anvendt','badge'=>ImageService::getUnusedImagesCount(),'icon'=>'monochrome/round_question','value'=>'unused'))->endItem();
$writer->endItems();
?>