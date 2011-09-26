<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestProductgroup extends AbstractObjectTest {
    
	function TestProductgroup() {
		parent::AbstractObjectTest('productgroup');
	}
	
}
?>