<?
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
class HtmlDocument {

	var $html;
	
	function HtmlDocument($html='') {
		$this->html = $html;
	}
	
	function getBodyContents() {
		$pattern = '/<body(\s*[\w\d\"=#_]*)*>([\s\W\w]*)<\/body\s*>/mi';
		$matches = array();
		preg_match($pattern, $this->html, $matches);
		//error_log(print_r($matches,true));
		if (@$matches[2]) {
			return $matches[2];
		} else {
			return '';
		}
	}
}
?>