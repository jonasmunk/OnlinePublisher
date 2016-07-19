<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Parts
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class TestCustomPart extends UnitTestCase {

  function testLoad() {
    $this->assertNull(CustomPart::load(0));
  }

  function testCreate() {
    $obj = new CustomPart();
    $this->assertFalse($obj->isPersistent());
    $obj->save();
    $this->assertTrue($obj->isPersistent());
    $id = $obj->getId();
    $this->assertNotNull(CustomPart::load($id));
    $obj->remove();
    $this->assertNull(CustomPart::load($id));
  }

  function testProperties() {
    $obj = new CustomPart();
    $obj->setWorkflowId(10);
    $obj->setViewId(11);
    $obj->save();

    $obj2 = CustomPart::load($obj->getId());
    $this->assertEqual(10, $obj2->getWorkflowId());
    $this->assertEqual(11, $obj2->getViewId());

    $obj2->remove();
  }

  function testImport() {
    // TODO!
  }

}
?>