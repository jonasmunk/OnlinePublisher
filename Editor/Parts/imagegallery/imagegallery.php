<?
/**
 * @package OnlinePublisher
 * @subpackage Parts.Imagegallery
 */
require_once($basePath.'Editor/Classes/Part.php');
require_once($basePath.'Editor/Classes/Request.php');
require_once($basePath.'Editor/Classes/GuiUtils.php');

class PartImagegallery extends Part {
	
	function PartImagegallery($id=0) {
		parent::Part('imagegallery');
		$this->id = $id;
	}
	
	function sub_display($context) {
		return $this->render();
	}
	
	function sub_editor($context) {
		global $baseUrl;
		$out = '';
		$sql = "select * from part_imagegallery where part_id=".$this->id;
		if ($row = Database::selectFirst($sql)) {
			$out.=
		    '<input type="hidden" name="group" value="'.$row['imagegroup_id'].'"/>'.
		    '<input type="hidden" name="height" value="'.$row['height'].'"/>'.
		    '<input type="hidden" name="framed" value="'.($row['framed'] ? 'true' : 'false').'"/>'.
		    '<input type="hidden" name="showTitle" value="'.($row['show_title'] ? 'true' : 'false').'"/>'.
		    '<input type="hidden" name="variant" value="'.$row['variant'].'"/>'.
			'<script src="'.$baseUrl.'Editor/Parts/imagegallery/script.js" type="text/javascript" charset="utf-8"></script>'.
			'<div id="part_imagegallery_container">'.$this->render().'</div>';
		}
		return $out;
	}
	
	function sub_create() {
		$sql = "insert into part_imagegallery (part_id) values (".$this->id.")";
		Database::insert($sql);
	}
	
	function sub_delete() {
		$sql = "delete from part_imagegallery where part_id=".$this->id;
		Database::delete($sql);
	}
	
	function sub_update() {
		$height = Request::getInt('height',64);
		$group = Request::getInt('group');
		$framed = Request::getBoolean('framed');
		$showTitle = Request::getBoolean('showTitle');
		$variant = Request::getString('variant');
		$sql = "update part_imagegallery set".
		" imagegroup_id=".$group.
		",height=".$height.
		",framed=".Database::boolean($framed).
		",show_title=".Database::boolean($showTitle).
		",variant=".Database::text($variant).
		" where part_id=".$this->id;
		Database::update($sql);
		error_log($sql);
	}
	
	function sub_import(&$node) {
	}
	
	function sub_build($context) {
		$sql = "select height,imagegroup_id as `group`,framed,show_title,variant from part_imagegallery where part_id=".$this->id;
		if ($data = Database::selectFirst($sql)) {
			return $this->generate($data);
		} else {
			return '';
		}
	}
		
	function sub_preview() {
		$params = array(
			'height'=>Request::getInt('height'),
			'group'=>Request::getInt('group'),
			'framed'=>Request::getBoolean('framed'),
			'show_title'=>Request::getBoolean('showTitle'),
			'variant'=>Request::getString('variant')
		);
		return $this->generate($params);
	}
		
	function generate($params) {
		$data = '<imagegallery xmlns="'.$this->_buildnamespace('1.0').'">';
		$data.='<display height="'.$params['height'].'" variant="'.StringUtils::escapeXML($params['variant']).'" framed="'.($params['framed'] ? 'true' : 'false').'" show-title="'.($params['show_title'] ? 'true' : 'false').'"/>';
		$sql="select object.data from object,imagegroup_image where imagegroup_image.image_id = object.id and imagegroup_image.imagegroup_id=".$params['group']." order by object.title";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$data.=$row['data'];
		}
		Database::free($result);
		$data.='</imagegallery>';
		return $data;
	}
	
	
	// Toolbar stuff
	
	function isIn2iGuiEnabled() {
		return true;
	}
	
	function getToolbars() {
		return array(
			'Billedgalleri' =>
			'<script source="../../Parts/imagegallery/toolbar.js"/>
			<dropdown label="Billedgruppe" width="200" name="group">
			'.GuiUtils::buildObjectItems('imagegroup').'
			</dropdown>
			<number label="H&#248;jde" name="height"/>
			<divider/>
			<dropdown label="Variant" name="variant">
				<item value="floating" title="Flydende"/>
				<item value="changing" title="Skiftende"/>
			</dropdown>
			<grid>
				<row>
					<cell right="5"><checkbox name="showTitle"/><label>Vis titel</label></cell>
				</row>
				<row>
					<cell right="5"><checkbox name="framed"/><label>Indrammet</label></cell>
				</row>
			</grid>'
			);
	}
	
}
?>