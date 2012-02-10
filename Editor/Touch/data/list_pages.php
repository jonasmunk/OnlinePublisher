<?php
require_once '../../Include/Private.php';


$list = array();

$pages = PageQuery::getRows()->asList();

foreach ($pages as $page) {
	$list[] = array('id' => $page['id'],'title' => $page['title']);
}

Log::debug('list_pages.php');

Response::sendUnicodeObject($list);
?>