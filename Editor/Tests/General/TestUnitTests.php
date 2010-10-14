<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.General
 */

class TestUnitTests extends UnitTestCase {
    
    function testIt() {
        $this->assertTrue(true);
        $this->assertTrue(1);
        $this->assertEqual(1,true);
        $this->assertEqual(2,true);
        $this->assertIdentical(2,2);
        $this->assertNotIdentical(1,true);
        $this->assertTrue(2==true);
        $this->assertTrue(2==true);

        $this->assertNull(null);
		$this->assertNotNull('');
		$this->assertNotNull(false);
		
        $this->assertEqual(null,false);
        $this->assertEqual(0,false);
        $this->assertEqual('',false);
    }
}
?>