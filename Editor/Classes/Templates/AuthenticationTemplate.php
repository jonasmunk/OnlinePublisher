<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Templates
 */

class AuthenticationTemplate {
	
	var $id;
	var $title;
	
	function setId($id) {
	    $this->id = $id;
	}

	function getId() {
	    return $this->id;
	}
	
	function setTitle($title) {
	    $this->title = $title;
	}

	function getTitle() {
	    return $this->title;
	}
		
	function save() {
		$sql = "update authentication set title=".Database::text($this->title)." where page_id=".Database::int($this->id);
		Database::update($sql);
		PageService::markChanged($this->id);
	}
	
	function load($id) {
		$sql="select title,page_id from authentication where page_id=".Database::int($id);
		if ($row = Database::getRow($sql)) {
			$obj = new AuthenticationTemplate();
			$obj->setId(intval($row['page_id']));
			$obj->setTitle($row['title']);			
			return $obj;
		}
		return null;
	}
}