<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.General
 */

class Testin2iGui extends UnitTestCase {

	function testLocalize() {
		$str = '<tag title="{ View ; da: Vis: }" label="{ en: Edit ; da: Rediger mig }" />';
		$result = In2iGui::localize($str,'en');
		$this->assertEqual($result,'<tag title="View" label="Edit" />');
		
		$result = In2iGui::localize($str,'da');
		$this->assertEqual($result,'<tag title="Vis:" label="Rediger mig" />');
	}
}