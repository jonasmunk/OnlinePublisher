<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.General
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class Testin2iGui extends UnitTestCase {

	function testLocalize() {
		$str = '<tag title="{ View ; da: Vis: }" label="{ en: Edit ; da: Rediger mig }" />';
		$result = In2iGui::localize($str,'en');
		$this->assertEqual($result,'<tag title="View" label="Edit" />');
		
		$result = In2iGui::localize($str,'da');
		$this->assertEqual($result,'<tag title="Vis:" label="Rediger mig" />');

		$str = '<tag>{ View ; da: Vis: }</tag>';
		$result = In2iGui::localize($str,'en');
		$this->assertEqual($result,'<tag>View</tag>');

	}
}