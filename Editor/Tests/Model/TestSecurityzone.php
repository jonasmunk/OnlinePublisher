<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestSecurityzone extends AbstractObjectTest {
    
	function TestSecurityzone() {
		parent::AbstractObjectTest('securityzone');
	}

	function testProperties() {
		$obj = new Securityzone();
		$obj->setTitle('My zone');
		$obj->setAuthenticationPageId(789);
		$obj->save();
		
		$obj2 = Securityzone::load($obj->getId());
		$this->assertEqual($obj2->getTitle(),$obj->getTitle());
		$this->assertEqual($obj2->getAuthenticationPageId(),$obj->getAuthenticationPageId());
		
		$obj2->remove();
	}
}
?>