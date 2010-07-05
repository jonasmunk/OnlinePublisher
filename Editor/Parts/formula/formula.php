<?
/**
 * @package OnlinePublisher
 * @subpackage Parts.Html
 */
require_once($basePath.'Editor/Classes/Part.php');
require_once($basePath.'Editor/Classes/Request.php');

class PartFormula extends Part {
	
	function PartFormula($id=0) {
		parent::Part('formula');
		$this->id = $id;
	}
	
	function sub_display($context) {
		return $this->render();
	}
	
	function sub_editor($context) {
		$sql = "SELECT * from part_formula where part_id=".$this->id;
		$row = Database::selectFirst($sql);
		return 
			'<input type="hidden" name="receiverName" value="'.escapeHTML($row['receivername']).'"/>'.
			'<input type="hidden" name="receiverEmail" value="'.escapeHTML($row['receiveremail']).'"/>'.
			$this->render();
	}
	
	function sub_create() {
		$sql = "insert into part_formula (part_id) values (".$this->id.")";
		Database::insert($sql);
	}
	
	function sub_delete() {
		$sql = "delete from part_formula where part_id=".$this->id;
		Database::delete($sql);
	}
	
	function sub_update() {
		$name = Request::getString('receiverName');
		$email = Request::getString('receiverEmail');
		$sql = "update part_formula set receivername=".Database::text($name).",receiveremail=".Database::text($email)." where part_id=".$this->id;
		Database::update($sql);
	}
	
	function sub_build($context) {
		$sql = "select * from part_formula where part_id=".$this->id;
		if ($row = Database::selectFirst($sql)) {
			return 
			'<formula xmlns="'.$this->_buildnamespace('1.0').'"/>';
		} else {
			return '';
		}
	}
	
	// Toolbar stuff
	
	function isIn2iGuiEnabled() {
		return true;
	}
	
	function getMainToolbarBody() {
		return '
			<script source="../../Parts/formula/toolbar.js"/>
			<divider/>
			<grid>
				<row>
					<cell label="Modtager:" width="180">
						<textfield adaptive="true" name="receiverName"/>
					</cell>
				</row>
				<row>
					<cell label="E-mail:" width="180">
						<textfield adaptive="true" name="receiverEmail"/>
					</cell>
				</row>
			</grid>
		';
	}
}
?>