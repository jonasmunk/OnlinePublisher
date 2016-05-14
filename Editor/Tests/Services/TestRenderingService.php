<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Services
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestRenderingService extends UnitTestCase {
    
    function testPreview() {
		$page = TestService::createTestPage();
		
		$preview = RenderingService::previewPage(array(
			'pageId' => $page->getId(),
			'relativePath' => '../../../../'
		));
		
		$this->assertTrue(Strings::isNotBlank($preview));
		
		TestService::removeTestPage($page);
    }
}
?>