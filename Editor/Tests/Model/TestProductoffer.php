<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestProductoffer extends AbstractObjectTest {
    
	function TestProductoffer() {
		parent::AbstractObjectTest('productoffer');
	}
	
}
?>