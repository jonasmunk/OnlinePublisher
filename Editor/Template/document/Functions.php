<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

// UNCATEGORIZED!!!


function parseList($list) {
	$list="\r\n".$list;
	$items = spliti("\r\n-",$list);
	$parsed = array();
	for ($i=1;$i<count($items);$i++) {
		$item=$items[$i];
		$lines=spliti("\r\n",$item);
		$parsed[]=$lines;
	}
	return $parsed;
}

function getDocumentRow() {
	if (isset($_SESSION['template.document.row'])) {
		return $_SESSION['template.document.row'];
	}
	else {
		return -1;
	}
}
	
function setDocumentRow($id) {
	$_SESSION['template.document.row']=$id;
}

function getDocumentColumn() {
	if (isset($_SESSION['template.document.column'])) {
		return $_SESSION['template.document.column'];
	}
	else {
		return -1;
	}
}
	
function setDocumentColumn($id) {
	$_SESSION['template.document.column']=$id;
}

function getDocumentSection() {
	if (isset($_SESSION['template.document.section'])) {
		return $_SESSION['template.document.section'];
	}
	else {
		return -1;
	}
}
	
function setDocumentSection($id) {
	$_SESSION['template.document.section']=$id;
}

function getDocumentDesign() {
	if (isset($_SESSION['template.document.design'])) {
		return $_SESSION['template.document.design'];
	}
	else {
		return -1;
	}
}
	
function setDocumentDesign($id) {
	$_SESSION['template.document.design']=$id;
}

function getDocumentToolbarTab() {
	if (isset($_SESSION['template.document.toolbar.tab'])) {
		return $_SESSION['template.document.toolbar.tab'];
	}
	else {
		return 'document';
	}
}
	
function setDocumentToolbarTab($id) {
	$_SESSION['template.document.toolbar.tab']=$id;
}

function getDocumentHeaderTab() {
	if (isset($_SESSION['template.document.header.tab'])) {
		return $_SESSION['template.document.header.tab'];
	}
	else {
		return 'text';
	}
}
	
function setDocumentHeaderTab($id) {
	$_SESSION['template.document.header.tab']=$id;
}

function getDocumentImageTab() {
	if (isset($_SESSION['template.document.image.tab'])) {
		return $_SESSION['template.document.image.tab'];
	}
	else {
		return 'image';
	}
}
	
function setDocumentImageTab($id) {
	$_SESSION['template.document.image.tab']=$id;
}

function getDocumentTextTab() {
	if (isset($_SESSION['template.document.text.tab'])) {
		return $_SESSION['template.document.text.tab'];
	}
	else {
		return 'text';
	}
}
	
function setDocumentTextTab($id) {
	$_SESSION['template.document.text.tab']=$id;
}

function getDocumentListTab() {
	if (isset($_SESSION['template.document.list.tab'])) {
		return $_SESSION['template.document.list.tab'];
	}
	else {
		return 'text';
	}
}
	
function setDocumentListTab($id) {
	$_SESSION['template.document.list.tab']=$id;
}

function getDocumentPersonTab() {
	if (isset($_SESSION['template.document.person.tab'])) {
		return $_SESSION['template.document.person.tab'];
	}
	else {
		return 'person';
	}
}
	
function setDocumentPersonTab($id) {
	$_SESSION['template.document.person.tab']=$id;
}

function getDocumentNewsTab() {
	if (isset($_SESSION['template.document.news.tab'])) {
		return $_SESSION['template.document.news.tab'];
	}
	else {
		return 'news';
	}
}
	
function setDocumentNewsTab($id) {
	$_SESSION['template.document.news.tab']=$id;
}
?>