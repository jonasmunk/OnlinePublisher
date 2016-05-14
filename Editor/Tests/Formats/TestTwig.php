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
        $person = new Person();
        $person->setFirstname('John');
        $person->setSurname('Lennon');
        $result = $twig->render('Hello {{ person.firstname }} {{ person.surname }}!', array('person' => $person));
        $this->assertEqual('Hello John Lennon!',$result);
	}
}