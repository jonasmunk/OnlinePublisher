<?
/**
 * @package OnlinePublisher
 * @subpackage Parts.Html
 */
require_once($basePath.'Editor/Classes/Part.php');

class PartHorizontalrule extends Part {
	
	function PartHorizontalrule($id=0) {
		parent::Part('horizontalrule');
		$this->id = $id;
	}
	
	function sub_display($context) {
		$data='';
		$sql = "select * from part_horizontalrule where part_id=".$this->id;
		if ($row = Database::selectFirst($sql)) {
			$data='<hr class="part_horizontalrule"/>';
		}
		return $data;
	}
	
	function sub_editor($context) {
		$sql = "select * from part_horizontalrule where part_id=".$this->id;
		if ($row = Database::selectFirst($sql)) {
			return
			'<hr class="part_horizontalrule"/>';
		} else {
			return '';
		}
	}
	
	function sub_create() {
		$sql = "insert into part_horizontalrule (part_id) values (".$this->id.")";
		Database::insert($sql);
	}
	
	function sub_delete() {
		$sql = "delete from part_horizontalrule where part_id=".$this->id;
		Database::delete($sql);
	}
	
	function sub_update() {
		
	}
	
	function sub_build($context) {
		$sql = "select * from part_horizontalrule where part_id=".$this->id;
		if ($row = Database::selectFirst($sql)) {
			return 
			'<horizontalrule xmlns="'.$this->_buildnamespace('1.0').'"/>';
		} else {
			return '';
		}
	}
	
	// Toolbar stuff
	
	function isIn2iGuiEnabled() {
		return true;
	}
	
	function getToolbarTabs() {
		return array(
				 'horizontalrule' => array('title' => 'Adskiller')
			);
	}
	
	function getToolbarDefaultTab() {
		return 'horizontalrule';
	}
}
?>