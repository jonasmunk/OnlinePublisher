<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Formats
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestTwig extends UnitTestCase {
	
	function testSimple() {
    
        $loader = new Twig_Loader_String();
        $twig = new Twig_Environment($loader);

        $result = $twig->render('Hello {{ name }}!', array('name' => 'John'));
        $this->assertEqual('Hello John!',$result);
	}
}