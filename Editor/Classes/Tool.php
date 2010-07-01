<?
require_once($basePath.'Editor/Classes/FileSystemUtil.php');

class Tool {
	function getAvailableTools() {
		global $basePath;
		$arr = FileSystemUtil::listDirs($basePath."Editor/Tools/");
		for ($i=0;$i<count($arr);$i++) {
			if (substr($arr[$i],0,3)=='CVS') {
				unset($arr[$i]);
			}
		}
		return $arr;
	}
	
	function getInstalledTools() {
		$arr = array();
		$sql = "select id,`unique` from `tool`";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$arr[] = array("id" => $row['id'],"unique" => $row['unique']);
		}
		Database::free($result);
		return $arr;
	}
	
	function getToolInfo($unique,$id=-1) {
		$xml_parser = new ToolInfoParser();
		$xml_parser->parse($unique);
		$parsed = $xml_parser->parsed;
		$parsed['id'] = $id;
		return $parsed;
	}
	
	function getTools() {
		$output = array();
		$tools = Tool::getInstalledTools();
		for ($i=0;$i<count($tools);$i++) {
			$unique = $tools[$i]['unique'];
	    	$id = $tools[$i]['id'];
			$output[]=Tool::getToolInfo($unique,$id);
		}
		usort($output,'compareTools');
		return $output;
	}

	function getToolsByCategory($cat) {
		$output = array();
		$tools = Tool::getTools();
		for ($i=0;$i<count($tools);$i++) {
			if ($tools[$i]['category']==$cat) {
				$output[]=$tools[$i];
			}
		}
		usort($output,'compareTools');
		return $output;
	}
}

// Used to sort arrays of tools
function compareTools($toolA, $toolB) {
	$a = $toolA['priority'];
	$b = $toolB['priority'];
	if ($a == $b) {
		return 0;
	}
	return ($a < $b) ? -1 : 1;
}

class ToolInfoParser {
	var $parser;
	var $currenttag;
	var $parsed;
	var $isActions;
	var $currAction;
	
	function toolInfoParser() 
	{
		$this->parsed = array();
		$this->currAction = array();
		$this->isActions = FALSE;
	}

	function parse($unique) 
	{
		global $basePath;
		$file = $basePath."Editor/Tools/".$unique."/info.xml";
		$this->parser = xml_parser_create("ISO-8859-1");
		$this->parsed['unique'] = $unique;
		$this->parsed['actions'] = array();
		
		xml_set_object($this->parser, $this);
		xml_set_element_handler($this->parser, "tag_open", "tag_close");
		xml_set_character_data_handler($this->parser, "cdata");
		xml_parser_set_option($this->parser, XML_OPTION_TARGET_ENCODING, "ISO-8859-1");
		if (!($fp = @fopen($file, "r"))) {
			return null;
			die("could not open XML input: ".$unique);
		}

		while ($data = fread($fp, 4096)) {
			if (!xml_parse($this->parser, $data, feof($fp))) {
				echo $unique;
				die(sprintf("XML error: %s at line %d",
					xml_error_string(xml_get_error_code($this->parser)),
					xml_get_current_line_number($this->parser)));
			}
		}
		xml_parser_free($this->parser);
		return $this->parsed;
	}

	function tag_open($parser, $tag, $attributes) 
	{
		$this->currentTag=$tag; 
		if ($tag=='ACTIONS') {
			$this->isActions = TRUE;
		}
		else if ($tag=='ACTION') {
			$this->currAction = array();
		}
	}

	function tag_close($parser, $tag) 
	{
		$this->currentTag=NULL;
		if ($tag=='ACTIONS') {
			$this->isActions = FALSE;
		}
		else if ($tag=='ACTION') {
			$this->parsed['actions'][] = $this->currAction;
		}
	}

	function cdata($parser, $cdata) 
	{
		if ($this->isActions) {
			switch ($this->currentTag) {
			case 'UNIQUE':
				$this->currAction['unique']=$cdata;
				break;
			case 'NAME':
				$this->currAction['name']=$cdata;
				break;
			case 'DESCRIPTION':
				if (isset($this->currAction['description'])) {
					$this->currAction['description'].=$cdata;
				}
				else {
					$this->currAction['description']=$cdata;
				}
				break;
			case 'ICON':
				$this->currAction['icon']=$cdata;
				break;
			case 'OVERLAY':
				$this->currAction['overlay']=$cdata;
				break;
			}
		}
		else {
			switch ($this->currentTag) {
			case 'NAME':
				$this->parsed['name']=$cdata;
				break;
			case 'ICON':
				$this->parsed['icon']=$cdata;
				break;
			case 'CATEGORY':
				$this->parsed['category']=$cdata;
				break;
			case 'PRIORITY':
				$this->parsed['priority']=intval($cdata);
				break;
			case 'DESCRIPTION':
				if (isset($this->parsed['description'])) {
					$this->parsed['description'].=$cdata;
				}
				else {
					$this->parsed['description']=$cdata;
				}
				break;
			}
		}
	}

}
?>