<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

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