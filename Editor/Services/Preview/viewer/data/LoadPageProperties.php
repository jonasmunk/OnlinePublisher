<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Preview
 */
require_once '../../../../Include/Private.php';

$id = Request::getInt('id');

$page = Page::load($id);

$arr = array(
	'title'=>$page->getTitle(), 
	'path'=>$page->getPath(), 
	'keywords'=>$page->getKeywords(), 
	'language'=>$page->getLanguage(), 
	'description'=>$page->getDescription()
);

Response::sendUnicodeObject($arr);
?>