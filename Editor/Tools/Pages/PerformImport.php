<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Database.php';
require_once '../../Classes/Response.php';
require_once '../../Classes/FileUpload.php';
require_once '../../Classes/Page.php';
require_once '../../Classes/Request.php';
require_once '../../Libraries/domit/xml_domit_include.php';

$title = Request::getText('title');
$design = Request::getInt('design');
$frame = Request::getInt('frame');

header('Content-Type: text/plain; charset: ISO-8859-1;');

$upload = new FileUpload();
$response = $upload->process('file');
doImport($upload->getFilePath(),$title,$design,$frame);
$upload->clean();


function doImport($path,$title,$design,$frame) {
	$doc =& new DOMIT_Document();
	if ($doc->loadXML($path)) {
		$parts =& $doc->selectNodes("/package/part");
		for ($i=0;$i<$parts->getLength();$i++) {
			$part =& $parts->item($i);
			$type = $part->getAttribute('type');
			$subtype = $part->getAttribute('subtype');
			if ($type=='page') {
				importPage($subtype,$part,$title,$design,$frame);
			}
		}
	} else {
		error_log('could not load!');
	}
}

function importPage($template,&$part,$title,$design,$frame) {
	$page = new Page();
	$page->setTitle($title);
	$templateId = Page::translateToTemplateId($template);
	$page->setTemplateId($templateId);
	$page->setDesignId($design);
	$page->setFrameId($frame);
	$page->create();
	$controller = $page->getTemplateController($template);
	$data =& $part->getElementsByTagName('sub')->item(0)->firstChild;
	$controller->import($data);
	$page->publish();
	Response::redirect('EditPage.php?id='.$page->getId());
}
?>