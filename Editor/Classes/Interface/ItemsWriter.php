<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Interface
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
class ItemsWriter {
	function startItems() {
		header('Content-Type: text/xml; charset=iso-8859-1');
		echo '<?xml version="1.0" encoding="ISO-8859-1"?><items>';
		return $this;
	}

	function startItem($options) {
		echo '<item value="'.StringUtils::escapeXML($options['value']).'" title="'.StringUtils::escapeXML($options['title']).'" icon="'.$options['icon'].'" kind="'.$options['kind'].'" badge="'.$options['badge'].'">';
		return $this;
	}

	function item($options) {
		return $this->startItem($options)->endItem();
	}

	function endItem() {
		echo '</item>';
		return $this;
	}

	function endItems() {
		echo '</items>';
		return $this;
	}

	function title($title) {
		echo '<title title="'.StringUtils::escapeXML($title).'"/>';
		return $this;
	}

}
?>