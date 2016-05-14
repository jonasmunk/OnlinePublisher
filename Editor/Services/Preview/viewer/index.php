<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Preview
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id',0);
$history = Request::getInt('history');

if ($id==0) {
	if (InternalSession::getPageId()>0) {
		$id = InternalSession::getPageId();
	}
	else {
		$id = RenderingService::findPage('home');
	}
}

$html = RenderingService::previewPage(array(
	'pageId' => $id,
	'historyId' => $history,
	'relativePath' => '../../../../'
));
if ($html) {
	InternalSession::setPageId($id);
	header("Content-Type: text/html; charset=UTF-8");
	echo $html;
} else {
	Response::notFound('Siden findes ikke længere: '.$id);
}
?>