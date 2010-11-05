<?
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Log.php');
require_once($basePath.'Editor/Classes/XmlUtils.php');

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

class FeedSerializer {
	
	function FeedSerializer() {
		
	}
	
	function sendHeaders() {
		header("Last-Modified: " . gmdate("D, d M Y H:i:s",gmmktime()) . " GMT");
		header('Content-type: application/rss+xml; charset=utf-8');
	}
	
	function serialize($feed) {
		$xml = '<?xml version="1.0" encoding="utf-8"?><rss version="2.0"><channel>'.
		$this->buildTextTag('title',$feed->getTitle()).
		$this->buildTextTag('link',$feed->getLink()).
		$this->buildTextTag('language',$feed->getLanguage()).
		$this->buildTextTag('description',$feed->getDescription()).
		$this->buildTextTag('copyright',$feed->getCopyright()).
		$this->buildDateTag('pubDate',$feed->getPubDate()).
		$this->buildDateTag('lastBuildDate',$feed->getLastBuildDate()).
		$this->buildTextTag('ttl',$feed->getTtl()).
		$this->buildTextTag('image',$feed->getImage()).
		$this->buildTextTag('rating',$feed->getRating()).
		$this->buildTextTag('docs',$feed->getDocs()).
		$this->buildTextTag('generator',$feed->getGenerator()).
		$this->buildTextTag('webMaster',$feed->getWebMaster()).
		$this->buildTextTag('managingEditor',$feed->getManagingEditor());
		$items =& $feed->getItems();
		foreach ($items as $item) {
			$xml.='<item>'.
			$this->buildTextTag('title',$item->getTitle()).
			$this->buildTextTag('description',$item->getDescription()).
			$this->buildTextTag('link',$item->getLink()).
			$this->buildTextTag('guid',$item->getGuid()).
			$this->buildDateTag('pubDate',$item->getPubDate());
			$encs = $item->getEnclosures();
			foreach ($encs as $enc) {
				$xml.='<enclosure url="'.$enc['url'].'" type="'.$enc['type'].'" length="'.$enc['length'].'"/>';
			}
			$xml.='</item>';
		}
		$xml .= '</channel></rss>';
		return $xml;
	}
	
	function serializeDate($date) {
		return gmdate("D, d M Y H:i:s T",$date);
	}
	
	function buildTextTag($tagName,$value) {
		if ($value) {
			return '<'.$tagName.'>'.encodeXML($value).'</'.$tagName.'>';
		}
	}
	
	function buildDateTag($tagName,$value) {
		if (strlen($value)>0) {
			return '<'.$tagName.'>'.$this->serializeDate($value).'</'.$tagName.'>';
		}
	}
}

class Feed {
	
	var $items = array();
	var $format;
	var $title;
	var $link;
	var $language;
	var $description;
	var $copyright;
	var $lastBuildDate;
	var $pubDate;
	var $docs;
	var $generator;
	var $webMaster;
	var $managingEditor;
	var $ttl;
	var $image;
	var $rating;
	
	
	function Feed() {
	}
	
	function addItem($item) {
		$this->items[] = $item;
	}
	
	function getItems() {
		return $this->items;
	}
	
	function setTitle($title) {
	    $this->title = $title;
	}

	function getTitle() {
	    return $this->title;
	}
	
	function setCopyright($copyright) {
	    $this->copyright = $copyright;
	}

	function getCopyright() {
	    return $this->copyright;
	}
	
	
	function setDescription($description) {
	    $this->description = $description;
	}

	function getDescription() {
	    return $this->description;
	}
	
	
	function setLanguage($language) {
	    $this->language = $language;
	}

	function getLanguage() {
	    return $this->language;
	}
	
	function setLink($link) {
	    $this->link = $link;
	}

	function getLink() {
	    return $this->link;
	}
	
	function setPubDate($pubDate) {
	    $this->pubDate = $pubDate;
	}

	function getPubDate() {
	    return $this->pubDate;
	}
	
	function setLastBuildDate($lastBuildDate) {
	    $this->lastBuildDate = $lastBuildDate;
	}

	function getLastBuildDate() {
	    return $this->lastBuildDate;
	}
	
	function setDocs($docs) {
	    $this->docs = $docs;
	}

	function getDocs() {
	    return $this->docs;
	}
	
	function setGenerator($generator) {
	    $this->generator = $generator;
	}

	function getGenerator() {
	    return $this->generator;
	}
	
	function setWebMaster($webMaster) {
	    $this->webMaster = $webMaster;
	}

	function getWebMaster() {
	    return $this->webMaster;
	}
	
	function setManagingEditor($managingEditor) {
	    $this->managingEditor = $managingEditor;
	}

	function getManagingEditor() {
	    return $this->managingEditor;
	}
	
	function setTtl($ttl) {
	    $this->ttl = $ttl;
	}

	function getTtl() {
	    return $this->ttl;
	}
	
	function setImage($image) {
	    $this->image = $image;
	}

	function getImage() {
	    return $this->image;
	}
	
	function setRating($rating) {
	    $this->rating = $rating;
	}

	function getRating() {
	    return $this->rating;
	}
	
}


class FeedItem {
	var $title;
	var $description;
	var $link;
	var $pubDate;
	var $guid;
	var $enclosures = array();
	
	function FeedItem() {
		
	}
	
	function addEnclosure($url,$type,$length) {
		$this->enclosures[] = array('url' => $url, 'type' => $type, 'length' => $length);
	}
	
	function getEnclosures() {
		return $this->enclosures;
	}
	
	function setTitle($title) {
	    $this->title = $title;
	}

	function getTitle() {
	    return $this->title;
	}
	
	function setDescription($description) {
	    $this->description = $description;
	}

	function getDescription() {
	    return $this->description;
	}
	
	function setLink($link) {
	    $this->link = $link;
	}

	function getLink() {
	    return $this->link;
	}
	
	function setPubDate($pubDate) {
	    $this->pubDate = $pubDate;
	}

	function getPubDate() {
	    return $this->pubDate;
	}
	
	function setGuid($guid) {
	    $this->guid = $guid;
	}

	function getGuid() {
	    return $this->guid;
	}
	
}
?>