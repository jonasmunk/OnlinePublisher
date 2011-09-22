<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Preview
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Classes/Database.php';
require_once '../../../Classes/Hierarchy.php';
require_once '../../../Classes/Request.php';
require_once '../../../Classes/Services/RenderingService.php';
require_once '../../../Classes/Services/PageService.php';
require_once '../../../Classes/InternalSession.php';
require_once '../../../Classes/Utilities/StringUtils.php';

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