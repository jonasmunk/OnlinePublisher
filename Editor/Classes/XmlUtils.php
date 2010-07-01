<?

class XmlUtils {
	
	function XmlUtils() {
	}
	
	//////////////////////// Static /////////////////////
	
	function buildDate($tagName,$stamp) {
		return '<'.$tagName.' unix="'.$stamp.'" day="'.date('d',$stamp).'" weekday="'.date('w',$stamp).'" yearday="'.date('z',$stamp).'" month="'.date('m',$stamp).'" year="'.date('Y',$stamp).'" hour="'.date('H',$stamp).'" minute="'.date('i',$stamp).'" second="'.date('s',$stamp).'" offset="'.date('Z',$stamp).'" timezone="'.date('T',$stamp).'"/>';
	}
	
	function getPathText(&$node,$path) {
		$xpath = new DOMXPath($node->ownerDocument);
		if ($child =& $xpath->query($path,$node)->item(0)) {
			return $child->textContent;
		} else {
			return '';
		}
	}
}
?>