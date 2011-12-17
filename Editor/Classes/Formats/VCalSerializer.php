<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Formats
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
class VCalSerializer {
	
	function VCalSerializer() {
		
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
			return '<'.$tagName.'>'.StringUtils::escapeXML($value).'</'.$tagName.'>';
		}
	}
	
	function buildDateTag($tagName,$value) {
		if (strlen($value)>0) {
			return '<'.$tagName.'>'.$this->serializeDate($value).'</'.$tagName.'>';
		}
	}
}
?>