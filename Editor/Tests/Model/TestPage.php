<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Network
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestPage extends UnitTestCase {

	function testCreate() {
		$template = TemplateService::getTemplateByUnique('document');
		if (!$template) {
			Log::debug('Skipping test since no document template exists');
			return;
		}
		
		$hierarchy = new Hierarchy();
		$hierarchy->save();
		
		$frame = new Frame();
		$frame->setHierarchyId($hierarchy->getId());
		$frame->save();

		$design = new Design();
		$design->setUnique('custom');
		$design->save();

		$page = new Page();

		$this->assertNull($page->getId());
		$this->assertFalse(PageService::validate($page));
		$this->assertFalse($page->create());
	
		$page->setTemplateId($template->getId());
		$this->assertFalse(PageService::validate($page));
		$this->assertFalse($page->create());
		
		$page->setDesignId($design->getId());
		$this->assertFalse(PageService::validate($page));
		$this->assertFalse($page->create());
		
		$page->setFrameId($frame->getId());
		$this->assertFalse(PageService::validate($page));
		$this->assertFalse($page->create());
		
		$page->setTitle('Test page');
		$this->assertTrue(PageService::validate($page));
		
		$page->setDescription('My page description, find this: djsakJSDLjdasljslsdjljdslJ');
		$page->setPath('test/path.html');
		$page->setLanguage('en');
		$page->create();
		
		$loaded = Page::load($page->getId());
		$this->assertNotNull($loaded);
		
		$this->assertEqual($page->getId(),$loaded->getId());
		$this->assertEqual($page->getTemplateId(),$loaded->getTemplateId());
		$this->assertEqual($page->getDesignId(),$loaded->getDesignId());
		$this->assertEqual($page->getFrameId(),$loaded->getFrameId());
		$this->assertEqual($page->getTitle(),$loaded->getTitle());
		$this->assertEqual($page->getDescription(),$loaded->getDescription());
		$this->assertEqual($page->getPath(),$loaded->getPath());
		$this->assertEqual($page->getLanguage(),$loaded->getLanguage());


		$this->assertFalse(PageService::isChanged($page->getId()));
		$page->setTitle('Test page');
		$page->save();
		sleep(1);
		PageService::markChanged($page->getId());
		$this->assertTrue(PageService::isChanged($page->getId()));

		$page->publish();
		$this->assertFalse(PageService::isChanged($page->getId()));
		

		// Check that the design and frame cannot be removed
		$this->assertFalse($design->canRemove());
		$this->assertFalse($design->remove());
		$this->assertFalse($frame->canRemove());
		$this->assertFalse($frame->remove());
		
		
		$result = PageQuery::rows()->withText('djsakJSDLjdasljslsdjljdslJ')->search();
		$this->assertEqual($result->getTotal(),'1');
		$list = $result->getList();
		$first = $list[0];
		$this->assertEqual($first['title'],'Test page');
		$this->assertEqual($first['id'],$page->getId());
		$this->assertEqual($first['language'],$page->getLanguage());

		$page->delete();
		
		// Test that it is gone
		$loaded = Page::load($page->getId());
		$this->assertNull($loaded);
		
		
		Log::debug('Design id: '.$design->getId());
		$this->assertTrue($design->canRemove());
		$this->assertTrue($design->remove());
		$this->assertFalse(Design::load($design->getId()));
		
		$this->assertTrue($frame->canRemove());
		$this->assertTrue($frame->remove());
		$hierarchy->remove();
	}
	
	function testLinks() {
		// Create two pages
		$fromPage = TestService::createTestPage();
		$toPage = TestService::createTestPage();
		
		// They should not be changed now
		$this->assertFalse(PageService::isChanged($fromPage->getId()));
		$this->assertFalse(PageService::isChanged($toPage->getId()));
		
		// Create news with link to a page
		$news = new News();
		$news->setTitle('Unit test news article');
		$news->save();
		ObjectLinkService::addLink($news,'Read it','This is the alternative',null,'page',$toPage->getId());
		$news->publish();
		
		// Check that the news is not changed
		$this->assertFalse(ObjectService::isChanged($news->getId()));
		
		// Create a link from one to the other
		$link = new Link();
		$link->setText('dummy');
		$link->setPageId($fromPage->getId());
		$link->setTypeAndValue('page',$toPage->getId());
		$link->save();
		
		// Check that the links
		$links = LinkService::getPageLinks($fromPage->getId());
		$this->assertEqual(count($links),1);
		
		// Wait a little to make timestamps different
		sleep(1);
		
		// Save the destination
		$toPage->save();
		
		// Now the source of the link should be changed
		$this->assertTrue(PageService::isChanged($fromPage->getId()));

		// Now the news should be changed
		$this->assertTrue(ObjectService::isChanged($news->getId()));

	
		// Remove the two pages
		TestService::removeTestPage($fromPage);
		TestService::removeTestPage($toPage);
		
		$news->remove();
		
		// Check that the links are removed
		$links = LinkService::getPageLinks($fromPage->getId());
		$this->assertEqual(count($links),0);
	}
	
	function TestRendering() {
		$page = TestService::createTestPage();
		
		$url = ConfigurationService::getCompleteBaseUrl().'?id='.$page->getId();
		
		$response = HttpClient::send(new HttpRequest($url));
		$this->assertEqual($response->getStatusCode(),200);
		
		$page->setDisabled(true);
		$page->save();
		
		$response = HttpClient::send(new HttpRequest($url));
		$this->assertEqual($response->getStatusCode(),404);
		
		TestService::removeTestPage($page);
	}
}
?>