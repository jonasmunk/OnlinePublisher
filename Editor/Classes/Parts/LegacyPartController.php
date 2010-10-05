<?
require_once($basePath.'Editor/Include/Session.php');
require_once($basePath.'Editor/Include/Functions.php');
require_once($basePath.'Editor/Classes/FileSystemUtil.php');
require_once($basePath.'Editor/Classes/XmlUtils.php');
require_once($basePath.'Editor/Classes/Services/XslService.php');
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */

class LegacyPartController {
	var $id;
	var $type;
	
	function LegacyPartController($type) {
		$this->type = $type;
	}
	
	function getId() {
		return $this->id;
	}

	function display($context) {
		if (method_exists($this,'sub_display')) {
			return $this->sub_display($context);
		} else {
			return '';
		}
	}
	
	function getSectionClass() {
		if (method_exists($this,'sub_getSectionClass')) {
			return $this->sub_getSectionClass();
		} else {
			return '';
		}
	}

	function editor($context) {
		if (method_exists($this,'sub_editor')) {
			return $this->sub_editor($context);
		} else {
			return '';
		}
	}

	function editorExtra($context,$part) {
		if (method_exists($this,'sub_editor_extra')) {
			return $this->sub_editor_extra($context,$part);
		} else {
			return '';
		}
	}
	
	function create() {
		$sql = "insert into part (type,created,updated,dynamic) values ('".$this->type."',now(),now(),".Database::boolean($this->isDynamic()).")";
		$this->id = Database::insert($sql);
		if (method_exists($this,'sub_create')) {
			$this->sub_create();
		}
	}
	
	function delete() {
		$sql = "delete from part where id=".$this->id;
		Database::delete($sql);
		if (method_exists($this,'sub_delete')) {
			$this->sub_delete();
		}
	}
	
	function update() {
		$sql = "update part set updated=now(),dynamic=".Database::boolean($this->isDynamic())." where id=".$this->id;
		Database::update($sql);
		if (method_exists($this,'sub_update')) {
			$this->sub_update();
		}
	}
	
	function import(&$node) {
		if (method_exists($this,'sub_import')) {
			$this->sub_import($node);
		}
	}
	
	function build($context) {
		$xml = '<part xmlns="http://uri.in2isoft.com/onlinepublisher/part/1.0/" type="'.$this->type.'" id="'.$this->id.'">'.
		'<sub>';
		if (method_exists($this,'sub_build')) {
			$xml.=$this->sub_build($context);
		}
		$xml.=
		'</sub>'.
		'</part>';
		return $xml;
	}
	
	function buildPreview() {
		$xml = '<part xmlns="http://uri.in2isoft.com/onlinepublisher/part/1.0/" type="'.$this->type.'" id="'.$this->id.'">'.
		'<sub>';
		if (method_exists($this,'sub_preview')) {
			$xml.=$this->sub_preview();
		}
		$xml.=
		'</sub>'.
		'</part>';
		return $xml;
	}

	function isDynamic() {
		if (method_exists($this,'sub_isDynamic')) {
			return $this->sub_isDynamic();
		} else {
			return false;
		}
	}

	function index() {
		$text = '';
		if (method_exists($this,'sub_index')) {
			$text.=$this->sub_index();
		}
		return $text;
	}
	
	function getToolbarTabs() {
		return array();
	}
	
	function getToolbarDefaultTab() {
		return '';
	}
	
	function getToolbarContent($tab) {
		return '';
	}

////////////////////////// In2iGui ////////////////////////
	
	function isIn2iGuiEnabled() {
		return false;
	}
	
	function getToolbars() {
		return array();
	}
	
////////////////////////// Static ////////////////////////
	
	function getNewPart($unique) {
		global $basePath;
		require_once($basePath.'Editor/Parts/'.$unique.'/'.$unique.'.php');
		$partClass = 'Part'.ucfirst($unique);
		$part = new $partClass ();
		return $part;
	}
	
	function load($unique,$id) {
		global $basePath;
		require_once($basePath.'Editor/Parts/'.$unique.'/'.$unique.'.php');
		$partClass = 'Part'.ucfirst($unique);
		$part = new $partClass ($id);
		return $part;
	}

	
	function getAvailableParts() {
		global $basePath;
		$arr = FileSystemUtil::listDirs($basePath."Editor/Parts/");
		for ($i=0;$i<count($arr);$i++) {
			if (substr($arr[$i],0,3)=='CVS') {
				unset($arr[$i]);
			}
		}
		return $arr;
	}

	function getParts() {
		$out = null;//getSessionCacheVar('parts');
		if ($out == null) {
			$out = array();	
			$parts = LegacyPartController::getAvailableParts();
			foreach ($parts as $part) {
				$info = LegacyPartController::getPartInfo($part);
				if ($info) {
					$out[$part] = $info;
				}
			}
			uasort($out,array("LegacyPartController", "compareParts"));
			setSessionCacheVar('parts',$out);
		}
		return $out;
	}

	/**
	 * Used to sort arrays of tools
	 */
	function compareParts($partA, $partB) {
		$a = $partA['priority'];
		$b = $partB['priority'];
		if ($a == $b) {
			return 0;
		}
		return ($a < $b) ? -1 : 1;
	}


	function getPartInfo($unique) {
		global $basePath;
		$file = $basePath."Editor/Parts/".$unique."/info.xml";
		if (file_exists($file)) {
			$info = array();
			$doc = new DOMDocument();
			if ($doc->load($file)) {
				$info['name'] = XmlUtils::getPathText($doc->documentElement,"/part/name");
				$info['description'] = XmlUtils::getPathText($doc->documentElement,"/part/description");
				$info['priority'] = XmlUtils::getPathText($doc->documentElement,"/part/priority");
			}
			else {
				error_log('getPartInfo: '.$doc->getErrorString());
			}
			return $info;
		}
		else {
			return false;
		}
	}

///////////////////////// Support ////////////////////////

	
	function render($context=null) {
		global $basePath;
		if ($context==null) {
			$context = new PartContext();
		}
		$xmlData = '<?xml version="1.0" encoding="ISO-8859-1"?>'.$this->build($context);
		
		$xslData='<?xml version="1.0" encoding="ISO-8859-1"?>'.
		'<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">'.
		'<xsl:output method="html" indent="no" encoding="ISO-8859-1"/>'.
		'<xsl:include href="'.$basePath.'style/basic/xslt/util.xsl"/>'.
		'<xsl:include href="'.$basePath.'style/basic/xslt/part_'.$this->type.'.xsl"/>'.
		'<xsl:variable name="design"></xsl:variable>'.
		'<xsl:variable name="path">../../../</xsl:variable>'.
		'<xsl:variable name="navigation-path"></xsl:variable>'.
		'<xsl:variable name="page-path"></xsl:variable>'.
		'<xsl:variable name="template"></xsl:variable>'.
		'<xsl:variable name="agent">'.encodeXML($_SERVER['HTTP_USER_AGENT']).'</xsl:variable>'.
		'<xsl:variable name="userid"></xsl:variable>'.
		'<xsl:variable name="username"></xsl:variable>'.
		'<xsl:variable name="usertitle"></xsl:variable>'.
		'<xsl:variable name="preview"></xsl:variable>'.
		'<xsl:variable name="editor">true</xsl:variable>'.
		'<xsl:variable name="highquality">false</xsl:variable>'.
		'<xsl:template match="/"><xsl:apply-templates/></xsl:template>'.
		'</xsl:stylesheet>';
		return XslService::transform($xmlData,$xslData);
	}

	
	function preview() {
		global $basePath;
		$xmlData = '<?xml version="1.0" encoding="UTF-8"?>'.$this->buildPreview();
		
		$xslData='<?xml version="1.0" encoding="UTF-8"?>'.
		'<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">'.
		'<xsl:output method="html" indent="no" encoding="UTF-8"/>'.
		'<xsl:include href="'.$basePath.'style/basic/xslt/util.xsl"/>'.
		'<xsl:include href="'.$basePath.'style/basic/xslt/part_'.$this->type.'.xsl"/>'.
		'<xsl:variable name="design"></xsl:variable>'.
		'<xsl:variable name="path">../../../</xsl:variable>'.
		'<xsl:variable name="navigation-path"></xsl:variable>'.
		'<xsl:variable name="page-path"></xsl:variable>'.
		'<xsl:variable name="template"></xsl:variable>'.
		'<xsl:variable name="agent">'.encodeXML($_SERVER['HTTP_USER_AGENT']).'</xsl:variable>'.
		'<xsl:variable name="userid"></xsl:variable>'.
		'<xsl:variable name="username"></xsl:variable>'.
		'<xsl:variable name="usertitle"></xsl:variable>'.
		'<xsl:variable name="preview"></xsl:variable>'.
		'<xsl:variable name="editor">true</xsl:variable>'.
		'<xsl:variable name="highquality">false</xsl:variable>'.
		'<xsl:template match="/"><xsl:apply-templates/></xsl:template>'.
		'</xsl:stylesheet>';
		return XslService::transform($xmlData,$xslData);
	}
	
	function getSingleLink($sourceType=null) {
	    $sql = "select part_link.*,page.path from part_link left join page on page.id=part_link.target_value and part_link.target_type='page' where part_id=".$this->id;
	    if (!is_null($sourceType)) {
	        $sql.=" and source_type=".Database::text($sourceType); 
	    }
	    if ($row = Database::selectFirst($sql)) {
	        return $row;
	    } else {
	        return false;
	    }
	}

	
	function _builddate($tag,$stamp) {
		return '<'.$tag.' unix="'.$stamp.'" day="'.date('d',$stamp).'" weekday="'.date('d',$stamp).'" yearday="'.date('z',$stamp).'" month="'.date('m',$stamp).'" year="'.date('Y',$stamp).'" hour="'.date('H',$stamp).'" minute="'.date('i',$stamp).'" second="'.date('s',$stamp).'" offset="'.date('Z',$stamp).'" timezone="'.date('T',$stamp).'"/>';
	}
	
	function _buildnamespace($version) {
		return 'http://uri.in2isoft.com/onlinepublisher/part/'.$this->type.'/'.$version.'/';
	}
	
	function _buildXMLStyle($row) {
		$style = '<style';
		if ($row['fontsize']!='') {
			$style.=' font-size="'.$row['fontsize'].'"';
		}
		if ($row['fontfamily']!='') {
			$style.=' font-family="'.$row['fontfamily'].'"';
		}
		if ($row['textalign']!='') {
			$style.=' text-align="'.$row['textalign'].'"';
		}
		if ($row['lineheight']!='') {
			$style.=' line-height="'.$row['lineheight'].'"';
		}
		if ($row['color']!='') {
			$style.=' color="'.$row['color'].'"';
		}
		if ($row['fontweight']!='') {
			$style.=' font-weight="'.$row['fontweight'].'"';
		}
		if ($row['fontstyle']!='') {
			$style.=' font-style="'.$row['fontstyle'].'"';
		}
		if ($row['wordspacing']!='') {
			$style.=' word-spacing="'.$row['wordspacing'].'"';
		}
		if ($row['letterspacing']!='') {
			$style.=' letter-spacing="'.$row['letterspacing'].'"';
		}
		if ($row['textindent']!='') {
			$style.=' text-indent="'.$row['textindent'].'"';
		}
		if ($row['texttransform']!='') {
			$style.=' text-transform="'.$row['texttransform'].'"';
		}
		if ($row['fontvariant']!='') {
			$style.=' font-variant="'.$row['fontvariant'].'"';
		}
		if ($row['textdecoration']!='') {
			$style.=' text-decoration="'.$row['textdecoration'].'"';
		}
		return $style.'/>';
	}
	
	/**
	 * Send parent node of the style node!
	 */
	function _parseXMLStyle(&$node) {
		$p = array(
			'textalign'=>'',
			'fontsize'=>'',
			'fontfamily'=>'',
			'lineheight'=>'',
			'fontweight'=>'',
			'wordspacing'=>'',
			'letterspacing'=>'',
			'textdecoration'=>'',
			'textindent'=>'',
			'texttransform'=>'',
			'fontstyle'=>'',
			'fontvariant'=>'',
			'color'=>''
			);
		if ($node) {
			$p['textalign'] = $node->getAttribute('text-align');
			$p['fontsize'] = $node->getAttribute('font-size');
			$p['fontfamily'] = $node->getAttribute('font-family');
			$p['lineheight'] = $node->getAttribute('line-height');
			$p['fontweight'] = $node->getAttribute('font-weight');
			$p['wordspacing'] = $node->getAttribute('word-spacing');
			$p['letterspacing'] = $node->getAttribute('letter-spacing');
			$p['textdecoration'] = $node->getAttribute('text-decoration');
			$p['textindent'] = $node->getAttribute('text-indent');
			$p['texttransform'] = $node->getAttribute('text-transform');
			$p['fontstyle'] = $node->getAttribute('font-style');
			$p['fontvariant'] = $node->getAttribute('font-variant');
			$p['color'] = $node->getAttribute('color');
		}
		return $p;
	}
	
	function _buildCSSStyle($row) {
		$style = '';
		if ($row['fontsize']!='') {
			$style.=' font-size: '.$row['fontsize'].';';
		}
		if ($row['fontfamily']!='') {
			$style.=' font-family: '.$row['fontfamily'].';';
		}
		if ($row['textalign']!='') {
			$style.=' text-align: '.$row['textalign'].';';
		}
		if ($row['lineheight']!='') {
			$style.=' line-height: '.$row['lineheight'].';';
		}
		if ($row['color']!='') {
			$style.=' color: '.$row['color'].';';
		}
		if ($row['fontweight']!='') {
			$style.=' font-weight: '.$row['fontweight'].';';
		}
		if ($row['fontstyle']!='') {
			$style.=' font-style: '.$row['fontstyle'].';';
		}
		if ($row['wordspacing']!='') {
			$style.=' word-spacing: '.$row['wordspacing'].';';
		}
		if ($row['letterspacing']!='') {
			$style.=' letter-spacing: '.$row['letterspacing'].';';
		}
		if ($row['textindent']!='') {
			$style.=' text-indent: '.$row['textindent'].';';
		}
		if ($row['texttransform']!='') {
			$style.=' text-transform: '.$row['texttransform'].';';
		}
		if ($row['fontvariant']!='') {
			$style.=' font-variant: '.$row['fontvariant'].';';
		}
		if ($row['textdecoration']!='') {
			$style.=' text-decoration: '.$row['textdecoration'].';';
		}
		return $style;
	}

}
?>