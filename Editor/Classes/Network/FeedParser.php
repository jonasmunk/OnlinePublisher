<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Network
 */
require_once($basePath.'Editor/Classes/Log.php');
require_once($basePath.'Editor/Classes/XmlUtils.php');
require_once($basePath.'Editor/Classes/Network/Feed.php');
require_once($basePath.'Editor/Classes/Network/FeedItem.php');
require_once($basePath.'Editor/Classes/Utilities/DateUtils.php');

class FeedParser {
	
	var $log = array();
	
	function FeedParser() {
	}
	
	function getLog() {
		return $this->log();
	}
	
	function parseURL($url) {
		$feed = new Feed();
		$doc = new DOMDocument('1.0','UTF-8');
		if (@$doc->load($url)) {
			if ($doc->documentElement->nodeName=='rss') {
				$this->parseRSS(&$doc,$feed);
			} else if ($doc->documentElement->nodeName=='feed') {
				$this->parseAtom(&$doc,$feed);
			}
			return $feed;
		} else {
			$log[] = 'Could not load: '.$url;
			return false;
		}
	}
	
	function parseRSS(&$doc,&$feed) {
		$feed->format = "RSS ".$doc->documentElement->getAttribute('version');
		$xpath = new DOMXPath($doc);
		$channel =& $xpath->query('/rss/channel',$doc)->item(0);
		if ($channel) {
			$feed->setTitle(DOMUtils::getFirstChildText($channel,'title'));
			$feed->setLink(DOMUtils::getFirstChildText($channel,'link'));
			$feed->setLanguage(DOMUtils::getFirstChildText($channel,'language'));
			$feed->setDescription(DOMUtils::getFirstChildText($channel,'description'));
			$feed->setCopyright(DOMUtils::getFirstChildText($channel,'copyright'));
			$feed->setPubDate(DateUtils::parseRFC822(DOMUtils::getFirstChildText($channel,'pubDate')));
			$feed->setLastBuildDate(DateUtils::parseRFC822(DOMUtils::getFirstChildText($channel,'lastBuildDate')));
			
			$feed->setDocs(DOMUtils::getFirstChildText($channel,'docs'));
			$feed->setGenerator(DOMUtils::getFirstChildText($channel,'generator'));
			$feed->setWebMaster(DOMUtils::getFirstChildText($channel,'webMaster'));
			$feed->setManagingEditor(DOMUtils::getFirstChildText($channel,'managingEditor'));
			$feed->setTtl(DOMUtils::getFirstChildText($channel,'ttl'));
			$feed->setImage(DOMUtils::getFirstChildText($channel,'image'));
			$feed->setRating(DOMUtils::getFirstChildText($channel,'rating'));
		}
		$this->parseRSSItems(&$doc,$feed);
	}
	
	function parseRSSItems(&$doc,&$feed) {
		$nodes =& $doc->getElementsByTagName('item');
		$len = $nodes->length;
		for ($i=0; $i < $len; $i++) {
			$node =& $nodes->item($i);
			$item = new FeedItem();
			$item->setTitle(DOMUtils::getFirstChildText($node,'title'));
			$item->setDescription(DOMUtils::getFirstChildText($node,'description'));
			$item->setLink(DOMUtils::getFirstChildText($node,'link'));
			$item->setPubDate(DateUtils::parseRFC822(DOMUtils::getFirstChildText($node,'pubDate')));
			$item->setGuid(DOMUtils::getFirstChildText($node,'guid'));
			$feed->addItem($item);
		}
	}
	
	function parseAtom(&$doc,&$feed) {
		$root = $doc->documentElement;
		$feed->setTitle(DOMUtils::getFirstChildText($root,'title'));
		$entries = $doc->getElementsByTagName('entry');
		for ($i=0,$len=$entries->length; $i < $len; $i++) {
			$node =& $entries->item($i);
			$item = new FeedItem();
			$item->setTitle(DOMUtils::getFirstChildText($node,'title'));
			$item->setPubDate(DateUtils::parseRFC3339(DOMUtils::getFirstChildText($node,'updated')));
			$feed->addItem($item);
		}
	}
}
?>