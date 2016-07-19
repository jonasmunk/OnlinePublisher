<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Network
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class FeedParser {

	var $log = array();

	function FeedParser() {
	}

	function getLog() {
		return $this->log();
	}

  public function parseFile($path) {
    return $this->parseURL($path);
  }

	function parseURL($url) {
		$feed = new Feed();
		$doc = new DOMDocument('1.0','UTF-8');
		if (@$doc->load($url)) {
			if ($doc->documentElement->nodeName=='rss') {
				$this->parseRSS($doc,$feed);
			} else if ($doc->documentElement->nodeName=='feed') {
				$this->parseAtom($doc,$feed);
			}
			return $feed;
		} else {
      Log::debug('Could not load: '.$url);
			$log[] = 'Could not load: '.$url;
			return false;
		}
	}

	function parseRSS(&$doc,&$feed) {
		$feed->format = "RSS ".$doc->documentElement->getAttribute('version');
		$xpath = new DOMXPath($doc);
		$channel = $xpath->query('/rss/channel',$doc)->item(0);
		if ($channel) {
			$feed->setTitle(DOMUtils::getFirstChildText($channel,'title'));
			$feed->setLink(DOMUtils::getFirstChildText($channel,'link'));
			$feed->setLanguage(DOMUtils::getFirstChildText($channel,'language'));
			$feed->setDescription(DOMUtils::getFirstChildText($channel,'description'));
			$feed->setCopyright(DOMUtils::getFirstChildText($channel,'copyright'));
			$feed->setPubDate(Dates::parseRFC822(DOMUtils::getFirstChildText($channel,'pubDate')));
			$feed->setLastBuildDate(Dates::parseRFC822(DOMUtils::getFirstChildText($channel,'lastBuildDate')));

			$feed->setDocs(DOMUtils::getFirstChildText($channel,'docs'));
			$feed->setGenerator(DOMUtils::getFirstChildText($channel,'generator'));
			$feed->setWebMaster(DOMUtils::getFirstChildText($channel,'webMaster'));
			$feed->setManagingEditor(DOMUtils::getFirstChildText($channel,'managingEditor'));
			$feed->setTtl(DOMUtils::getFirstChildText($channel,'ttl'));
			$feed->setImage(DOMUtils::getFirstChildText($channel,'image'));
			$feed->setRating(DOMUtils::getFirstChildText($channel,'rating'));
		}
		$this->parseRSSItems($doc,$feed);
	}

	function parseRSSItems(&$doc,&$feed) {
		$nodes = $doc->getElementsByTagName('item');
		$len = $nodes->length;
		for ($i=0; $i < $len; $i++) {
			$node = $nodes->item($i);
			$item = new FeedItem();
			$item->setTitle(DOMUtils::getFirstChildText($node,'title'));
			$item->setDescription(DOMUtils::getFirstChildText($node,'description'));
			$item->setLink(DOMUtils::getFirstChildText($node,'link'));
			$item->setPubDate(Dates::parseRFC822(DOMUtils::getFirstChildText($node,'pubDate')));
			$item->setGuid(DOMUtils::getFirstChildText($node,'guid'));
			$feed->addItem($item);
		}
	}

	function parseAtom(&$doc,&$feed) {
		$root = $doc->documentElement;
		$feed->setTitle(DOMUtils::getFirstChildText($root,'title'));
		$entries = $doc->getElementsByTagName('entry');
		for ($i=0,$len=$entries->length; $i < $len; $i++) {
			$node = $entries->item($i);
			$item = new FeedItem();
			$item->setTitle(DOMUtils::getFirstChildText($node,'title'));
			$item->setGuid(DOMUtils::getFirstChildText($node,'id'));
			$item->setPubDate(Dates::parseRFC3339(DOMUtils::getFirstChildText($node,'updated')));
			$item->setDescription(DOMUtils::getFirstChildText($node,'content'));
      $link = DOMUtils::getFirstChildElement($node,'link');
      if ($link && $href = $link->getAttribute('href')) {
        $item->setLink($href);
      }
			$feed->addItem($item);
		}
	}
}
?>