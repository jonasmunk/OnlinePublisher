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
		$obj = new $class();
        $this->assertNull($obj->load(0));
    }

    function testCreate() {
		$class = ucfirst($this->type);
        $obj = new $class();
		$this->assertFalse($obj->isPersistent());
		$obj->save();
		$this->assertTrue($obj->isPersistent());
		$id = $obj->getId();
        $this->assertNotNull($obj->load($id));
		$obj->remove();
		$loaded = $obj->load($id);
        $this->assertNull($loaded);
		if ($loaded) {
			Log::debug($loaded);
		}
    }

}