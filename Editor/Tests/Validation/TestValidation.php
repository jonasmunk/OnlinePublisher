<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Validation
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestValidation extends UnitTestCase {

	function testCreate() {
		$template = TemplateService::getTemplateByUnique('document');
		if (!$template) {
			Log::debug('Skipping test since no document template exists');
			return;
		}

		$hierarchy = new Hierarchy();
		$hierarchy->create();
		
		$frame = new Frame();
		$frame->setHierarchyId($hierarchy->getId());
		$frame->save();

		$design = new Design();
		$design->setUnique('custom');
		$design->save();

		$page = new Page();

		$page->setTemplateId($template->getId());
		$page->setDesignId($design->getId());
		$page->setFrameId($frame->getId());
		$page->setTitle('Test page for validation');
		$page->setLanguage('en');
		$page->create();
		
		$page->publish();
		$this->assertFalse(PageService::isChanged($page->getId()));

		$loaded = Page::load($page->getId());
		$this->assertNotNull($loaded);
				
		$designs = DesignService::getAvailableDesigns();
		foreach ($designs as $name => $info) {
			$url = ConfigurationService::getCompleteBaseUrl().'?id='.$page->getId().'&design='.$name.'&dev=false';
			Log::debug('Checking: '.$url);
			$file = new RemoteFile($url);
			$html = $file->getData();
			Log::debug($html);
			$this->assertTrue(strpos($html, '<meta http-equiv="content-type" content="text/html; charset=utf-8"/>')!==false,'The design "'.$name.'" has no content-type');
			$this->assertTrue(strpos($html, '<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">')!==false,'The design "'.$name.'" does not have correct html tag');
			$this->assertFalse(strpos($html, 'http://uri.in2isoft.com')!==false,'The design "'.$name.'" may contain xml namespaces');
			$this->assertTrue(strpos($html, 'Test page for validation')!==false,'The design "'.$name.'" does not contain the title');
			if (isset($info->build)) {
                $this->assertTrue(strpos($html, '/js/script.js')!==false,'The design "'.$name.'" does not include scripts');
                $this->assertTrue(strpos($html, '/css/style.css')!==false,'The design "'.$name.'" does not include css');			  
			} else {
                $this->assertTrue(strpos($html, '/bin/minimized.site.js')!==false,'The design "'.$name.'" does not include minimized site scripts');
                $this->assertTrue(strpos($html, '/bin/minimized.site.css')!==false,'The design "'.$name.'" does not include minimized site css');			    
			}
			$this->assertTrue(XmlService::validateSnippet($html),'The design "'.$name.'" is not valid xml');
		}
		
		$page->delete();
		
		// Test that it is gone
		$loaded = Page::load($page->getId());
		$this->assertNull($loaded);
		
		$this->assertTrue($design->canRemove());
		$this->assertTrue($design->remove());
		$this->assertTrue($frame->canRemove());
		$this->assertTrue($frame->remove());		
		$this->assertTrue($hierarchy->delete());		
	}
}
?>