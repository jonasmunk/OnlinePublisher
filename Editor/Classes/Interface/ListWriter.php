<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Interface
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
class ListWriter {
	function startList($options=array()) {
		if (@$options['unicode']==true || ConfigurationService::isUnicode()) {
			header('Content-Type: text/xml; charset=utf-8');
			echo '<?xml version="1.0" encoding="UTF-8"?>';
		} else {
			header('Content-Type: text/xml; charset=iso-8859-1');
			echo '<?xml version="1.0" encoding="ISO-8859-1"?>';
		}
		echo '<list';
		if (@$options['checkboxes']==true) {
			echo ' checkboxes="true"';
		}
		echo '>';
		return $this;
	}

	function endList() {
		echo '</list>';
	}
	
	function sort($key,$direction) {
		echo "<sort key='$key' direction='$direction'/>";
		return $this;
	}
	
	function window($options) {
		echo '<window total="'.$options['total'].'" size="'.$options['size'].'" page="'.$options['page'].'"/>';
		return $this;
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
		if (is_string($options)) {
			echo ' title="'.Strings::escapeEncodedXML($options).'"';
		}
		else if (is_array($options)) {
			if (isset($options['title'])) {
				echo ' title="'.Strings::escapeEncodedXML(GuiUtils::getTranslated($options['title'])).'"';
			}
			if (isset($options['width'])) {
				echo ' width="'.$options['width'].'"';
			}
			if (isset($options['key'])) {
				echo ' key="'.$options['key'].'"';
			}
			if (isset($options['align'])) {
				echo ' align="'.$options['align'].'"';
			}
			if (isset($options['sortable'])) {
				echo ' sortable="'.($options['sortable'] ? 'true' : 'false').'"';
			}
		}
		echo '/>';
		return $this;
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
		if (isset($options['level'])) {
			echo ' level="'.$options['level'].'"';
		}
		if (isset($options['data'])) {
			echo ' data="'.Strings::escapeXML(Strings::toJSON($options['data'])).'"';
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
		if (isset($options['wrap'])) {
			echo ' wrap="'.($options['wrap'] ? 'true' : 'false').'"';
		}
		if (@$options['dimmed']) {
			echo ' dimmed="true"';
		}
		if (isset($options['width'])) {
			echo ' width="'.$options['width'].'"';
		}
		if (isset($options['variant'])) {
			echo ' variant="'.$options['variant'].'"';
		}
		if (isset($options['class'])) {
			echo ' class="'.$options['class'].'"';
		}
		if (isset($options['align'])) {
			echo ' align="'.$options['align'].'"';
		}
		echo '>';
		return $this;
	}
	
	function cell($str) {
		return $this->startCell()->text($str)->endCell();
	}
	
	function endCell() {
		echo '</cell>';
		return $this;
	}
	
	function startLine($options=array()) {
		echo '<line'.
		(@$options['dimmed'] ? ' dimmed="true"' : '').
		(@$options['minor'] ? ' minor="true"' : '').
		(@$options['mini'] ? ' mini="true"' : '').
		(@$options['top'] ? ' top="'.$options['top'].'"' : '').
		(@$options['class'] ? ' class="'.$options['class'].'"' : '').
		'>';
		return $this;
	}
	
	function endLine() {
		echo '</line>';
		return $this;
	}
	
	function startWrap() {
		echo '<wrap>';
		return $this;
	}
	
	function endWrap() {
		echo '</wrap>';
		return $this;
	}
	
	function startDelete() {
		echo '<delete>';
		return $this;
	}
	
	function endDelete() {
		echo '</delete>';
		return $this;
	}
	
	function startStrong() {
		echo '<strong>';
		return $this;
	}
	
	function endStrong() {
		echo '</strong>';
		return $this;
	}

	function text($text) {
		$text = GuiUtils::getTranslated($text);
		echo Strings::escapeXMLBreak($text,'<break/>');
		return $this;
	}

	function badge($mixed) {
		echo '<badge>';
		if (is_array($mixed)) {
			$this->text($mixed['text']);
		} else {
			$this->text($mixed);
		}
		echo '</badge>';
		return $this;
	}
	
	function object($options=array()) {
		echo '<object icon="'.$options['icon'].'">';
		$this->text($options['text']);
		echo '</object>';
		return $this;
	}
	
	function icon($options=array()) {
		if (is_string($options)) {
			$options = array('icon'=>$options);
		}
		echo '<icon icon="'.$options['icon'].'"';
		if (isset($options['data'])) {
			echo ' data="'.Strings::escapeXML(Strings::toJSON(Strings::toUnicode($options['data']))).'"';
		}
		if (@$options['revealing']) {
			echo ' revealing="true"';
		}
		if (@$options['action']) {
			echo ' action="true"';
		}
		if (isset($options['hint'])) {
			echo ' hint="'.Strings::escapeXML(GuiUtils::getTranslated($options['hint'])).'"';
		}
		if (isset($options['size'])) {
			echo ' size="'.Strings::escapeXML($options['size']).'"';
		}
		echo '/>';
		return $this;
	}

	function button($options=array()) {
		echo '<button text="'.Strings::escapeEncodedXML(GuiUtils::getTranslated($options['text'])).'"';
		if (isset($options['data'])) {
			echo ' data="'.Strings::escapeXML(Strings::toJSON($options['data'])).'"';
		}
		echo '/>';
		return $this;
	}
	
	function startIcons($options=array()) {
		echo '<icons';
		if (isset($options['left'])) {
			echo ' left="'.Strings::escapeXML($options['left']).'"';
		}		
		echo '>';
		return $this;
	}
	
	function endIcons() {
		echo '</icons>';
		return $this;
	}
}
?>