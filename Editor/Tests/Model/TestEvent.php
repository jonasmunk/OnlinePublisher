<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestEvent extends AbstractObjectTest {
    
	function TestEvent() {
		parent::AbstractObjectTest('event');
	}
	
	function makeValid($event) {
		$event->setStartdate(time());
		$event->setEnddate(time());
	}
}
?>