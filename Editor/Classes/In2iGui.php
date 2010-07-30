<?
require_once($basePath.'Editor/Classes/Response.php');
require_once($basePath.'Editor/Classes/Log.php');

class In2iGui {

	function display($elements,&$xmlData) {
		global $basePath,$xwg_skin;
		$skin = $xwg_skin;
		$xmlData='<?xml version="1.0" encoding="ISO-8859-1"?>'.$xmlData;
		$xslData='<?xml version="1.0" encoding="ISO-8859-1"?>'.
		'<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">'.
		'<xsl:output method="html" indent="no" encoding="ISO-8859-1"/>'.
		'<xsl:include href="'.$basePath.'XmlWebGui/Skins/'.$skin.'/Main.xsl"/>';
		for ($i=0;$i<sizeof($elements);$i++) { 
			$xslData.='<xsl:include href="'.$basePath.'XmlWebGui/Skins/'.$skin.'/Include/'.$elements[$i].'.xsl"/>';
		}
		$xslData.='<xsl:template match="/"><xsl:apply-templates/></xsl:template>'.
		'</xsl:stylesheet>';
	
		if (function_exists('xslt_create')) {
			$arguments = array('/_xml' => &$xmlData,'/_xsl' => &$xslData);
			$xp = xslt_create();
			header('Content-Type: text/html; charset=iso-8859-1');
			echo xslt_process($xp, 'arg:/_xml', 'arg:/_xsl', NULL, $arguments );
	    	xslt_free($xp);
		}
		else {
			function xslErrorHandler($errno, $errmsg, $filename, $linenum, $vars) {
				header('Content-Type: text/xml');
				echo $vars['xmlData'];
				exit;
			}
			$xslt = new xsltProcessor;
			$xslt->importStyleSheet(DomDocument::loadXML($xslData));
			header('Content-Type: text/html; charset=iso-8859-1');
			echo $xslt->transformToXML(DomDocument::loadXML($xmlData));
		}
	}

	function render(&$gui) {
		global $basePath,$baseUrl;
		$xhtml = strpos($_SERVER['HTTP_ACCEPT'],'application/xhtml+xml')!==false;
		if ($_GET['xhtml']=='false') {
			$xhtml=false;
		}
		$dev = $_GET['dev']=='true' ? 'true' : 'false';
		//$dev='true';
		$xmlData='<?xml version="1.0" encoding="UTF-8"?>'.$gui;
		$xslData='<?xml version="1.0" encoding="UTF-8"?>'.
		'<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">'.
		'<xsl:output method="'.($xhtml ? 'xml' : 'html').'"/>'.
		'<xsl:variable name="dev">'.$dev.'</xsl:variable>'.
		'<xsl:variable name="context">'.substr($baseUrl,0,-1).'</xsl:variable>'.
		'<xsl:include href="'.$basePath.'In2iGui/xslt/gui.xsl"/>';
		$xslData.='<xsl:template match="/"><xsl:apply-templates/></xsl:template>'.
		'</xsl:stylesheet>';
	
		if (function_exists('xslt_create')) {
			$arguments = array('/_xml' => &$gui,'/_xsl' => &$xslData);
			$xp = xslt_create();
			header('Content-Type: '.($xhtml ? 'application/xhtml+xml' : 'text/html'));
			echo xslt_process($xp, 'arg:/_xml', 'arg:/_xsl', NULL, $arguments );
	    	xslt_free($xp);
		}
		else {
			function xslErrorHandler($errno, $errmsg, $filename, $linenum, $vars) {
				header('Content-Type: text/xml');
				echo $vars['gui'];
				exit;
			}
			header('Content-Type: '.($xhtml ? 'application/xhtml+xml' : 'text/html'));
			$xslt = new xsltProcessor;
			$xslt->importStyleSheet(DomDocument::loadXML($xslData));
			echo $xslt->transformToXML(DomDocument::loadXML($gui));
		}
	}
	
	function sendObject($obj) {
		header('Content-Type: text/plain; charset=utf-8');
		echo In2iGui::toJSON($obj);
	}
	
	function sendUnicodeObject($obj) {
		foreach ($obj as $key => $value) {
			if (is_string($value)) {
				if (is_array($obj)) {
					$obj[$key] = Request::toUnicode($value);
				} else {
					$obj->$key = Request::toUnicode($value);
				}
			}
		}
		In2iGui::sendObject($obj);
	}
	
	function toJSON($obj) {
		global $basePath;
		require_once($basePath.'Editor/Libraries/json/JSON2.php');
		$json = new Services_JSON();
		return $json->encode($obj);
	}
	
	function respondSuccess() {
		header('Content-Type: text/xml');
		echo '<?xml version="1.0" encoding="UTF-8"?><success/>';
	}

	function respondUploadSuccess() {
		header('Content-Type: text/plain');
		echo 'SUCCESS';
	}

	function respondUploadFailure() {
		Response::badRequest();
		echo 'FAILURE';
	}
	
	function toDateTime($stamp) {
		return date("YmdHis",$stamp);
	}
	
	function buildOptions($objects,$selected=array()) {
		$gui='';
		foreach ($objects as $object) {
			$gui.='<option title="'.In2iGui::escape($object->getTitle()).'" value="'.In2iGui::escape($object->getId()).'" selected="'.(in_array($object->getId(), $selected) ? 'true' : 'false').'"/>';
		}
		return $gui;
	}
	
	function escape($input) {
		$output = In2iGui::_htmlnumericentities($input);
		$output = str_replace('&#151;', '-', $output);
		$output = str_replace('&#146;', '&#39;', $output);
		$output = str_replace('&#147;', '&#8220;', $output);
		$output = str_replace('&#148;', '&#8221;', $output);
		$output = str_replace('&#128;', '&#243;', $output);
		$output = str_replace('&#128;', '&#243;', $output);
		$output = str_replace('"', '&quot;', $output);
		return $output;
	}
	
	function escapeUnicode($input) {
		$output = str_replace('<', '&#60;', $input);
		$output = str_replace('>', '&#62;', $output);
		return $output;
	}
	
	function presentDate($timestamp) {
		if ($timestamp==null) return '';
		setlocale(LC_TIME, "da_DK");
		return strftime("%e. %b %Y",$timestamp);
	}

	function _htmlnumericentities(&$str){
	  return preg_replace('/[^!-%\x27-;=?-~ ]/e', '"&#".ord("$0").chr(59)', $str);
	}
	
	function toLinks($links) {
		$out = array();
		foreach ($links as $link) {
			$link->toUnicode();
			$out[] = array(
				'id' => $link->getId(), 
				'text' => $link->getText(), 
				'kind' => $link->getType(), 
				'value' => $link->getValue(), 
				'info' => $link->getInfo(), 
				'icon' => $link->getIcon()
			);
		}
		return $out;
	}
	
	function fromLinks($links) {
		global $basePath;
		require_once($basePath.'Editor/Classes/ObjectLink.php');
		$out = array();
		foreach ($links as $link) {
			$objectLink = new ObjectLink();
			$objectLink->setText(Request::fromUnicode($link->text));
			$objectLink->setType($link->kind);
			$objectLink->setValue($link->value);
			$out[] = $objectLink;
		}
		return $out;
	}
}

class ListWriter {
	function startList() {
		header('Content-Type: text/xml; charset=iso-8859-1');
		echo '<?xml version="1.0" encoding="ISO-8859-1"?><list>';
	}

	function endList() {
		echo '</list>';
	}
	
	function sort($key,$direction) {
		echo "<sort key='$key' direction='$direction'/>";
	}
	
	function window($options) {
		echo '<window total="'.$options['total'].'" size="'.$options['size'].'" page="'.$options['page'].'"/>';
	}
	
	function startHeaders() {
		echo '<headers>';
		return $this;
	}
	
	function endHeaders() {
		echo '</headers>';
		return $this;
	}
	
	function header($options=array()) {
		echo '<header';
		if (isset($options['title'])) {
			echo ' title="'.$options['title'].'"';
		}
		if (isset($options['width'])) {
			echo ' width="'.$options['width'].'"';
		}
		if (isset($options['key'])) {
			echo ' key="'.$options['key'].'"';
		}
		if (isset($options['sortable'])) {
			echo ' sortable="'.($options['sortable'] ? 'true' : 'false').'"';
		}
		echo '/>';
	}
	
	function startRow($options=array()) {
		echo '<row';
		if (isset($options['id'])) {
			echo ' id="'.$options['id'].'"';
		}
		if (isset($options['value'])) {
			echo ' value="'.$options['value'].'"';
		}
		if (isset($options['kind'])) {
			echo ' kind="'.$options['kind'].'"';
		}
		echo '>';
		return $this;
	}
	
	function endRow() {
		echo '</row>';
		return $this;
	}
	
	function startCell($options=array()) {
		echo '<cell';
		if (isset($options['icon'])) {
			echo ' icon="'.$options['icon'].'"';
		}
		echo '>';
		return $this;
	}
	
	function endCell() {
		echo '</cell>';
		return $this;
	}
	
	function startLine($options=array()) {
		echo '<line'.
		($options['dimmed'] ? ' dimmed="true"' : '').
		'>';
		return $this;
	}
	
	function endLine() {
		echo '</line>';
		return $this;
	}
	
	function text($text) {
		echo In2iGui::escape($text);
		return $this;
	}
	
	function icon($options=array()) {
		echo '<icon icon="'.$options['icon'].'"/>';
		return $this;
	}
	
	function startIcons() {
		echo '<icons>';
		return $this;
	}
	
	function endIcons() {
		echo '</icons>';
		return $this;
	}
}

class ItemsWriter {
	function startItems() {
		header('Content-Type: text/xml; charset=iso-8859-1');
		echo '<?xml version="1.0" encoding="ISO-8859-1"?><items>';
		return $this;
	}

	function startItem($options) {
		echo '<item value="'.In2iGui::escape($options['value']).'" title="'.In2iGui::escape($options['title']).'" icon="'.$options['icon'].'" kind="'.$options['kind'].'" badge="'.$options['badge'].'">';
		return $this;
	}

	function endItem() {
		echo '</item>';
		return $this;
	}

	function endItems() {
		echo '</items>';
		return $this;
	}
}

class ArticlesWriter {
	function startArticles() {
		header('Content-Type: text/xml; charset=UTF-8');
		echo '<?xml version="1.0" encoding="UTF-8"?><articles>';
		return $this;
	}

	function endArticles() {
		echo '</articles>';
		return $this;
	}

	function startArticle($options=array()) {
		echo '<article value="'.In2iGui::escape($options['value']).'" kind="'.$options['kind'].'">';
		return $this;
	}

	function endArticle() {
		echo '</article>';
		return $this;
	}

	function startTitle() {
		echo '<title>';
		return $this;
	}

	function endTitle() {
		echo '</title>';
		return $this;
	}
	
	function text($text) {
		echo In2iGui::escapeUnicode($text);
		return $this;
	}

	function startParagraph($options=array()) {
		echo '<paragraph';
		if ($options['dimmed']==true) {
			echo ' dimmed="true"';
		}
		echo '>';
		return $this;
	}

	function endParagraph() {
		echo '</paragraph>';
		return $this;
	}
}
?>