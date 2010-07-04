<?
/**
 * @package OnlinePublisher
 * @subpackage Parts.Html
 */
require_once($basePath.'Editor/Classes/Part.php');

class PartHtml extends Part {
	
	function PartHtml($id=0) {
		parent::Part('html');
		$this->id = $id;
	}
	
	function sub_display($context) {
		$data='';
		$sql = "select * from part_html where part_id=".$this->id;
		if ($row = Database::selectFirst($sql)) {
			$data=
			'<div class="Part-html">'.
			$row['html'].
			'</div>';
		}
		return $data;
	}
	
	function sub_editor($context) {
		$sql = "select * from part_html where part_id=".$this->id;
		if ($row = Database::selectFirst($sql)) {
			return
			'<textarea class="Part-html" id="PartHtmlTextarea" name="html" style="width: 100%; height: 300px; border: none;">'.
			encodeXML($row['html']).
			'</textarea>'.
			'<script type="text/javascript">'.
			'document.getElementById("PartHtmlTextarea").focus();'.
			'document.getElementById("PartHtmlTextarea").select();'.
			'</script>';
		} else {
			return '';
		}
	}
	
	function sub_create() {
		$sql = "insert into part_html (part_id) values (".$this->id.")";
		Database::insert($sql);
	}
	
	function sub_delete() {
		$sql = "delete from part_html where part_id=".$this->id;
		Database::delete($sql);
	}
	
	function sub_update() {
		$html = requestPostText('html');
		$sql = "update part_html set html=".Database::text($html)." where part_id=".$this->id;
		Database::update($sql);
	}
	
	function sub_import(&$node) {
		$html = $node->getText();
		
		$sql = "update part_html set".
		" html=".Database::text($html).
		" where part_id=".$this->id;
		Database::update($sql);
	}
	
	function sub_build($context) {
		$sql = "select * from part_html where part_id=".$this->id;
		if ($row = Database::selectFirst($sql)) {
			return 
			'<html xmlns="'.$this->_buildnamespace('1.0').'">'.
			'<![CDATA['.
			$row['html'].
			']]>'.
			'</html>';
		} else {
			return '';
		}
	}
	
	function isIn2iGuiEnabled() {
		return true;
	}
}
?>