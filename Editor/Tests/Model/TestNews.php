<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

class TestNews extends UnitTestCase {
    
    function testLoad() {
        $this->assertNull(News::load(0));
    }

    function testCreate() {
        $news = new News();
		$this->assertFalse($news->isPersistent());
		$news->save();
		$this->assertTrue($news->isPersistent());
		$id = $news->getId();
        $this->assertNotNull(News::load($id));
		$news->remove();
        $this->assertNull(News::load($id));
    }
}
?>