<?php
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
		echo '<item';
		if (isset($options['value'])) {
			echo ' value="'.Strings::escapeXML($options['value']).'"';
		}
		if (isset($options['title'])) {
			echo ' title="'.Strings::escapeXML(GuiUtils::getTranslated($options['title'])).'"';
		}
		if (isset($options['text'])) {
			echo ' text="'.Strings::escapeXML(GuiUtils::getTranslated($options['text'])).'"';
		}
		if (isset($options['icon'])) {
			echo ' icon="'.$options['icon'].'"';
		}
		if (isset($options['kind'])) {
			echo ' kind="'.$options['kind'].'"';
		}
		if (isset($options['badge'])) {
			echo ' badge="'.$options['badge'].'"';
		}
		echo '>';
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

	function title($title=null) {
		echo '<title title="'.Strings::escapeXML(GuiUtils::getTranslated($title)).'"/>';
		return $this;
	}

}
?>