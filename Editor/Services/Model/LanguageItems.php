<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Model
 */
require_once '../../Include/Private.php';

$writer = new ItemsWriter();

$writer->startItems()->
	item(array('value'=>'','text'=>array('None','da'=>'Intet')))->
	item(array('value'=>'EN','text'=>array('English','da'=>'Engelsk')))->
	item(array('value'=>'DA','text'=>array('Danish','da'=>'Dansk')))->
	item(array('value'=>'DE','text'=>array('German','da'=>'Tysk')))->
	item(array('value'=>'ES','text'=>array('Spanish','da'=>'Spansk')))->
	item(array('value'=>'FR','text'=>array('French','da'=>'Fransk')))->
endItems();
?>