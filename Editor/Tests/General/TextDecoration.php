<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.General
 */
class TestTextDecoration extends UnitTestCase {
    
    function testSomething() {
        $text = 'Lorem [s]ipsum[s] dolor sdjahsdak@dshajsak.com sit amet, http://www.joansmunk.dk/ consectetuer adipiscing elit. Morbi commodo, ipsum sed pharetra gravida, orci magna rhoncus neque, id pulvinar odio lorem non turpis. Nullam [s]ipsumfafdasdfaa[s] sit amet enim. Suspendisse id velit vitae ligula volutpat condimentum. Aliquam erat volutpat. Sed quis velit. Nulla facilisi. Nulla libero. Vivamus pharetra posuere sapien. Nam consectetuer. Sed aliquam, nunc eget euismod ullamcorper, lectus nunc ullamcorper orci, fermentum bibendum enim nibh eget ipsum. Donec porttitor ligula eu dolor. http://www.apple.com/ Maecenas vitae nulla jbm@ah.dk consequat libero cursus venenatis. Nam magna enim, accumsan eu, blandit sed, blandit a, eros.';
		$dec = new TextDecorator();
		$dec->addTag('s','strong');
		$dec->addReplacement('Lorem','<em>','</em>');
		$dec->addReplacement('Lor','<strike>','</strike>');
		$result = $dec->decorate($text);
		$expected = '<strike>Lor</strike>em <strong>ipsum</strong> dolor <a href="mailto:sdjahsdak@dshajsak.com">sdjahsdak@dshajsak.com</a> sit amet, <a href="http://www.joansmunk.dk/">http://www.joansmunk.dk/</a> consectetuer adipiscing elit. Morbi commodo, ipsum sed pharetra gravida, orci magna rhoncus neque, id pulvinar odio lorem non turpis. Nullam <strong>ipsumfafdasdfaa</strong> sit amet enim. Suspendisse id velit vitae ligula volutpat condimentum. Aliquam erat volutpat. Sed quis velit. Nulla facilisi. Nulla libero. Vivamus pharetra posuere sapien. Nam consectetuer. Sed aliquam, nunc eget euismod ullamcorper, lectus nunc ullamcorper orci, fermentum bibendum enim nibh eget ipsum. Donec porttitor ligula eu dolor. <a href="http://www.apple.com/">http://www.apple.com/</a> Maecenas vitae nulla <a href="mailto:jbm@ah.dk">jbm@ah.dk</a> consequat libero cursus venenatis. Nam magna enim, accumsan eu, blandit sed, blandit a, eros.';
		$this->assertEqual($expected,$result);
    }

    function testUrl() {
		$text = 'Lorem ipsum dolor sit amet, http://www.apple.com/ consectetur adipisicing elit';
		$expected = 'Lorem ipsum dolor sit amet, <a href="http://www.apple.com/">http://www.apple.com/</a> consectetur adipisicing elit';
		$this->doUrlTest($text,$expected);
		
		$text = 'Lorem ipsum dolor sit amet, http://www.apple.com consectetur adipisicing elit';
		$expected = 'Lorem ipsum dolor sit amet, <a href="http://www.apple.com">http://www.apple.com</a> consectetur adipisicing elit';
		$this->doUrlTest($text,$expected);
		
		$text = 'http://dk2.php.net/get/php_manual_en.tar.gz/from/a/mirror?kern=23&x=y#abc';
		$expected = '<a href="http://dk2.php.net/get/php_manual_en.tar.gz/from/a/mirror?kern=23&x=y#abc">http://dk2.php.net/get/php_manual_en.tar.gz/from/a/mirror?kern=23&x=y#abc</a>';
		$this->doUrlTest($text,$expected);
		
		$text = 'lorem http://msdn.microsoft.com ipsum';
		$expected = 'lorem <a href="http://msdn.microsoft.com">http://msdn.microsoft.com</a> ipsum';
		$this->doUrlTest($text,$expected);
	}
	
	function doUrlTest($text,$expected) {
		$dec = new TextDecorator();
		$result = $dec->decorate($text);
		$this->assertEqual($expected,$result);
	}
}
?>