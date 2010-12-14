<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Network
 */

class TestFeeds extends UnitTestCase {
    
    function testRSS() {
		global $baseUrl;
		$url = $baseUrl.'Editor/Tests/Resources/twitter.rss';
		if ($url[0]=='/') {
			$url = 'http://localhost'.$url;
		}
		$parser = new FeedParser();
		$feed = $parser->parseURL($url);
		$this->assertTrue($feed!==false,'Unable to parse url: '.$url);
		$this->assertEqual($feed->getTitle(),'Twitter / in2isoft');
		$this->assertEqual($feed->getPubDate(),943920000);
		$this->assertEqual($feed->getLink(),'http://twitter.com/in2isoft');
		
		$items = $feed->getItems();
		$this->assertEqual(count($items),20);

		$first = $items[0];
		$this->assertEqual($first->getTitle(),'in2isoft: Fixing bugs related to Internet Explorer');
    }

    function testAtom() {
		global $baseUrl;
		$url = $baseUrl.'Editor/Tests/Resources/github.atom';
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
		$this->assertEqual($first->getPubDate(),1291721077);
    }
}
?>