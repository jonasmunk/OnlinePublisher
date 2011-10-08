<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Preview
 */
require_once '../../../Include/Private.php';

$pageId = Request::getInt('pageId');
$text = Request::getEncodedString('text');
$kind = Request::getEncodedString('kind');


$user = User::load(InternalSession::getUserId());
$page = Page::load($pageId);

if (!$user || !$page) {
	Response::badRequest();
	exit;
}
	$issue = new Issue();
	$issue->setTitle(StringUtils::shortenString($text,30));
	$issue->setNote($text);
	$issue->setKind($kind);
	$issue->save();

	RelationsService::relateObjectToPage($issue,$page,'subject');
	RelationsService::relateObjectToObject($isuse,$user,'reporter');	

?>