<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Tests
 */

class AbstractObjectTest extends UnitTestCase {
	
	private $type;
	
	function AbstractObjectTest($type) {
		$this->type = $type;
	}
    
    function testLoad() {
		Log::debug('Testing load!');
		$class = ucfirst($this->type);
        $this->assertNull($class::load(0));
    }

    function testCreate() {
		$class = ucfirst($this->type);
        $obj = new $class();
		$this->assertFalse($obj->isPersistent());
		$obj->save();
		$this->assertTrue($obj->isPersistent());
		$id = $obj->getId();
        $this->assertNotNull($class::load($id));
		$obj->remove();
        $this->assertNull($class::load($id));
    }

}