<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Network
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestFeeds extends UnitTestCase {
    
    function testRSS() {
		$url = ConfigurationService::getBaseUrl().'Editor/Tests/Resources/twitter.rss';
		if ($url[0]=='/') {
			$url = 'http://localhost'.$url;
		}
		$parser = new FeedParser();
		$feed = $parser->parseURL($url);
		$this->assertTrue($feed!==false,'Unable to parse url: '.$url);
		$this->assertEqual($feed->getTitle(),'Twitter / in2isoft');
		$this->assertEqual($feed->getPubDate(),null);
		$this->assertEqual($feed->getLink(),'http://twitter.com/in2isoft');
		
		$items = $feed->getItems();
		$this->assertEqual(count($items),20);

		$first = $items[0];
		$this->assertEqual($first->getTitle(),'in2isoft: Fixing bugs related to Internet Explorer');
    }

    function testAtom() {
		$url = ConfigurationService::getBaseUrl().'Editor/Tests/Resources/github.atom';
		if ($url[0]=='/') {
			$url = 'http://localhost'.$url;
		}
		$parser = new FeedParser();
		$feed = $parser->parseURL($url);
		$this->assertTrue($feed!==false,'Unable to parse url: '.$url);
		$this->assertEqual($feed->getTitle(),'Recent Commits to OnlinePublisher:master');

		$items = $feed->getItems();
		$this->assertEqual(count($items),20);
		
		$first = $items[0];
		$this->assertEqual($first->getTitle(),'Improved reliability of water usage service / tool');
		$this->assertEqual($first->getPubDate(),1291749877);
    }

	function testNewsService() {
		$url = ConfigurationService::getBaseUrl().'Editor/Tests/Resources/twitter.rss';
		if ($url[0]=='/') {
			$url = 'http://localhost'.$url;
		}
		$src = new Newssource();
		$src->setUrl($url);
		$src->save();
		
		NewsService::synchronizeSource($src->getId());
		
		$src->remove();
	}
}
?>