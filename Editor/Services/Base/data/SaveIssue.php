<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Preview
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');
$text = Request::getEncodedString('text');
$kind = Request::getEncodedString('kind');

if ($issue = Issue::load($id)) {
	$issue->setTitle(StringUtils::shortenString($text,30));
	$issue->setNote($text);
	$issue->setKind($kind);	
	$issue->save();
	$issue->publish();
}
?>