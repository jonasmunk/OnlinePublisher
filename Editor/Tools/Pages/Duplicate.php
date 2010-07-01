<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/FileUpload.php';
require_once '../../Classes/Page.php';
require_once '../../Libraries/domit/xml_domit_include.php';

$id = requestGetNumber('id');

$oldPage = Page::load($id);

$page = new Page();
$page->setTitle($oldPage->getTitle().' kopi');
$page->setTemplateId($oldPage->getTemplateId());
$page->setDesignId($oldPage->getDesignId());
$page->setFrameId($oldPage->getFrameId());
$xml = '<?xml version="1.0"?>'.$oldPage->getData();
$doc =& new DOMIT_Document();

if ($doc->parseXML($xml)) {
	$data = $doc->documentElement;
	$page->create();
	$controller = $page->getTemplateController($oldPage->getTemplateUnique());
	$controller->import($data);
	$page->publish();
	redirect('EditPage.php?id='.$page->getId());
} else {
	echo 'Could not parse data of existing page!';
}
?>