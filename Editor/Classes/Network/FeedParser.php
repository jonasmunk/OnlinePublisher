<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Network
 */
require_once($basePath.'Editor/Classes/Log.php');
require_once($basePath.'Editor/Classes/XmlUtils.php');
require_once($basePath.'Editor/Classes/Network/Feed.php');
require_once($basePath.'Editor/Classes/Network/FeedItem.php');

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
			$this->analyze(&$doc,$feed);
			$this->parseItems(&$doc,$feed);
			return $feed;
		} else {
			$log[] = 'Could not load: '.$url;
			return false;
		}
	}
	
	function analyze(&$doc,&$feed) {
		if ($doc->documentElement->nodeName=='rss') {
			$feed->format = "RSS ".$doc->documentElement->getAttribute('version');
			$xpath = new DOMXPath($doc);
			$channel =& $xpath->query('/rss/channel',$doc)->item(0);
			if ($channel) {
				$feed->setTitle(XmlUtils::getPathText($channel,'title'));
				$feed->setLink(XmlUtils::getPathText($channel,'link'));
				$feed->setLanguage(XmlUtils::getPathText($channel,'language'));
				$feed->setDescription(XmlUtils::getPathText($channel,'description'));
				$feed->setCopyright(XmlUtils::getPathText($channel,'copyright'));
				$feed->setPubDate($this->parseDate(XmlUtils::getPathText($channel,'pubDate')));
				$feed->setLastBuildDate($this->parseDate(XmlUtils::getPathText($channel,'lastBuildDate')));
				
				$feed->setDocs(XmlUtils::getPathText($channel,'docs'));
				$feed->setGenerator(XmlUtils::getPathText($channel,'generator'));
				$feed->setWebMaster(XmlUtils::getPathText($channel,'webMaster'));
				$feed->setManagingEditor(XmlUtils::getPathText($channel,'managingEditor'));
				$feed->setTtl(XmlUtils::getPathText($channel,'ttl'));
				$feed->setImage(XmlUtils::getPathText($channel,'image'));
				$feed->setRating(XmlUtils::getPathText($channel,'rating'));
			}
		} else if ($doc->documentElement->nodeName=='feed') {
			$root = $doc->documentElement;
			$feed->setTitle(DOMUtils::getFirstChildText($root,'title'));
		}
	}
	
	function parseItems(&$doc,&$feed) {
		$nodes =& $doc->getElementsByTagName('item');
		$len = $nodes->length;
		for ($i=0; $i < $len; $i++) {
			$node =& $nodes->item($i);
			$item = new FeedItem();
			$item->setTitle(XmlUtils::getPathText($node,'title'));
			$item->setDescription(XmlUtils::getPathText($node,'description'));
			$item->setLink(XmlUtils::getPathText($node,'link'));
			$item->setPubDate($this->parseDate(XmlUtils::getPathText($node,'pubDate')));
			$item->setGuid(XmlUtils::getPathText($node,'guid'));
			$feed->addItem($item);
		}
	}
	
	function parseDate($date) {
		preg_match("/(.+)\, (\d+) (\w+) (\d+) (\d+):(\d+):(\d+) (.+)/i",$date, $matches);
		
		$months = array("Jan" => 1,"Feb" => 2,"Mar" => 3,"Apr" => 4,"May" => 5,"Jun" => 6,"Jul" => 7,"Aug" => 8,"Sep" => 9,"Oct" => 10,"Nov" => 11,"Dec" => 12);
		
		return gmmktime ( $matches[5],$matches[6],$matches[7], $months[$matches[3]],$matches[2], $matches[4]);
	}
}
?>