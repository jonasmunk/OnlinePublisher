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
	
}
?>