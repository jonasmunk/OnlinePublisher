<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Interface
 */

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
		echo StringUtils::escapeXMLBreak($text,'<break/>');
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
?>