<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */
require_once('../../Config/Setup.php');
require_once('../Include/Security.php');

require_once('../Classes/News.php');

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