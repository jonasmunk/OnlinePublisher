<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Services
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestNewsService extends UnitTestCase {
    
	function testCreateArticle() {
		$template = TemplateService::getTemplateByUnique('document');
		if (!$template) {
			Log::debug('Skipping test since no document template exists');
			return;
		}
		
		$design = new Design();
		$design->setUnique('custom');
		$design->save();
		$this->assertTrue($design->getId()>0,'The design was not saved');
		
		$frame = new Frame();
		$frame->save();
		$this->assertTrue($frame->getId()>0,'The frame was not saved');
		
		$blueprint = new Pageblueprint();
		$blueprint->setTemplateId($template->getId());
		$blueprint->setDesignId($design->getId());
		$blueprint->setFrameId($frame->getId());
		$blueprint->save();
		$this->assertTrue($blueprint->getId()>0,'The blueprint was not saved');
		
		$article = new NewsArticle();
		$article->setTitle('My fantastic new article');
		$article->setPageBlueprintId($blueprint->getId());
		$article->setLinkText('Read more');
		
		$response = NewsService::createArticle($article);
		
		$page = $response['page'];
		$news = $response['news'];
		
		$this->assertNotNull($page,'No page in the response');
		$this->assertTrue($page->getId()>0,'The page was not saved');

		$this->assertNotNull($news,'No news in the response');
		$this->assertTrue($news->getId()>0,'The news was not saved');
		
		$links = ObjectLinkService::getLinks($news);
		$this->assertEqual(count($links),1,'There should be exactly one link');
		$this->assertEqual($links[0]->getValue(),$page->getId());

		// Clean up
		
		$page->remove();
		$news->remove();
		
		$design->remove();
		$frame->remove();
		$blueprint->remove();
	}
}
?>