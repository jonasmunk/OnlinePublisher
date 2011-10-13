<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Network
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestLink extends UnitTestCase {
	
	function testLinks() {
		// Create two pages
		$fromPage = TestService::createTestPage();
		$toPage = TestService::createTestPage();
		
		// They should not be changed now
		$this->assertFalse(PageService::isChanged($fromPage->getId()));
		$this->assertFalse(PageService::isChanged($toPage->getId()));
				
		// Create a link from one to the other
		$link = new Link();
		$link->setText('dummy');
		$link->setAlternative('This is the alternative');
		$link->setPageId($fromPage->getId());
		$link->setTypeAndValue('page',$toPage->getId());
		$link->save();
		
		
		$loaded = Link::load($link->getId());
		$this->assertNotNull($loaded);
		$this->assertEqual($loaded->getText(),'dummy');
		$this->assertEqual($loaded->getAlternative(),'This is the alternative');
		$this->assertEqual($loaded->getTargetType(),'page');
		$this->assertEqual($loaded->getPage(),$toPage->getId());
		$this->assertEqual($loaded->getFile(),null);
		$this->assertEqual($loaded->getEmail(),null);
		$this->assertEqual($loaded->getUrl(),null);
				
		
		// Check that the links
		$links = LinkService::getPageLinks($fromPage->getId());
		$this->assertEqual(count($links),1);
		
		// Wait a little to make timestamps different
		sleep(1);
		
		// Save the destination
		$toPage->save();
		
		// Now the source of the link should be changed
		$this->assertTrue(PageService::isChanged($fromPage->getId()));
	
		// Remove the two pages
		TestService::removeTestPage($fromPage);
		TestService::removeTestPage($toPage);
				
		// Check that the links are removed
		$links = LinkService::getPageLinks($fromPage->getId());
		$this->assertEqual(count($links),0);

		$loaded = Link::load($link->getId());
		$this->assertNull($loaded);
		
		$this->assertFalse($link->remove());
	}
	
}
?>