<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Templates
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestDocumentTemplate extends UnitTestCase {

	function testIt() {
		$page = TestService::createTestPage();
		
		$ctrl = new DocumentTemplateController();
		
		$data = $ctrl->build($page->getId());
		$this->assertEqual('<content xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/document/1.0/"><row><column></column></row></content>',$data['data']);
		
		DocumentTemplateEditor::addRow($page->getId(),1);
		
		$data = $ctrl->build($page->getId());
		$this->assertEqual('<content xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/document/1.0/"><row><column></column></row><row><column></column></row></content>',$data['data']);
		
		//return;
		$part = HeaderPartController::createPart();
		
		DocumentTemplateEditor::addPartAtEnd($page->getId(),$part);
		
		$data = $ctrl->build($page->getId());
		
		$sql = "select * from document_section where page_id=".Database::int($page->getId());
		$section = Database::selectFirst($sql);
		
		$expected = '<content xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/document/1.0/">'.
			'<row><column></column></row>'.
			'<row><column>'.
			'<section id="'.$section['id'].'"><part xmlns="http://uri.in2isoft.com/onlinepublisher/part/1.0/" type="header" id="'.$part->getId().'">'.
			'<sub><header level="1" xmlns="http://uri.in2isoft.com/onlinepublisher/part/header/1.0/"><style/>Velkommen</header></sub></part></section>'.
			'</column></row>'.
			'</content>';
		
		$this->assertEqual($expected,$data['data']);
		
		TestService::removeTestPage($page);
	}
}