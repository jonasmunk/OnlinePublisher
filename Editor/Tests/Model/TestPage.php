<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Network
 */

class TestPage extends UnitTestCase {

	function testCreate() {
		$template = TemplateService::getTemplateByUnique('document');
		if (!$template) {
			Log::debug('Skipping test since no document template exists');
			return;
		}
		
		$frame = new Frame();
		$frame->save();

		$design = new Design();
		$design->setUnique('custom');
		$design->save();

		$page = new Page();

		$page->setTemplateId($template->getId());
		$page->setDesignId($design->getId());
		$page->setFrameId($frame->getId());
		$page->setTitle('Test page');
		$page->setDescription('My page description');
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


		$this->assertFalse(Page::isChanged($page->getId()));
		$page->setTitle('Test page');
		$page->save();
		sleep(2);
		Page::markChanged($page->getId());
		$this->assertTrue(Page::isChanged($page->getId()));

		$page->publish();
		$this->assertFalse(Page::isChanged($page->getId()));
		

		// Check that the design and frame cannot be removed
		$this->assertFalse($design->canRemove());
		$this->assertFalse($design->remove());
		$this->assertFalse($frame->canRemove());
		$this->assertFalse($frame->remove());


		$page->delete();
		
		// Test that it is gone
		$loaded = Page::load($page->getId());
		$this->assertNull($loaded);
		
		
		
		$this->assertTrue($design->canRemove());
		$this->assertTrue($design->remove());
		$this->assertTrue($frame->canRemove());
		$this->assertTrue($frame->remove());
		
	}
}
?>