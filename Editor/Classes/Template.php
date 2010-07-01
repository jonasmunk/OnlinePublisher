<?
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Database.php');
require_once($basePath.'Editor/Classes/Object.php');
require_once($basePath.'Editor/Include/Session.php');

class Template {
        
	var $id;
	var $unique;

    function Frame() {
    }

	function setId($id) {
	    $this->id = $id;
	}

	function getId() {
	    return $this->id;
	}
	
	function setUnique($unique) {
	    $this->unique = $unique;
	}

	function getUnique() {
	    return $this->unique;
	}
	
	function getName() {
		$info = Template::getInfo($this->unique);
		return $info['name'];
	}
	
	
	/**
	 * @static
	 */
	function search() {
		$list = array();
		$sql = "select id,`unique` from template";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$template = new Template();
			$template->setId($row['id']);
			$template->setUnique($row['unique']);
			$list[] = $template;
		}
		Database::free($result);
		return $list;
	}
	
	/**
	 * @static
	 */
	function getInfo($unique) {
		global $basePath;
		if ($out = getSessionCacheVar('template.info.'.$unique)) {
			return $out;
		}
		else {
			$out = array('unique'=>$unique,'icon' => null,'name' => null,'description' => null);
			$filename = $basePath."Editor/Template/".$unique."/info.xml";
	
			$data = implode("", file($filename));

			$parser = xml_parser_create('ISO-8859-1');
			xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
			xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
			xml_parse_into_struct($parser, $data, $values, $tags);
			xml_parser_free($parser);
			foreach ($values as $key) {
				switch($key['tag']) {
					case 'icon' : $out['icon']=$key['value']; break;
					case 'name' : $out['name']=$key['value']; break;
					case 'status' : $out['status']=$key['value']; break;
					case 'description' : $out['description']=$key['value']; break;
				}
			}
			setSessionCacheVar('template.info.'.$unique,$out);
			return $out;
		}
	}
	
	
	/**
	 * @static
	 */
	function getTemplatesKeyed() {
		$output = array();
		$templates = Template::getInstalledTemplates();
		for ($i=0;$i<count($templates);$i++) {
			$unique = $templates[$i]['unique'];
			$info = Template::getInfo($unique);
			$info['id']=$templates[$i]['id'];
			$output[$unique]=$info;
		}
		return $output;
	}	
	
	/**
	 * @static
	 */
	function getInstalledTemplates() {
		$arr = array();
		$sql = "select id,`unique` from `template`";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$arr[] = array("id" => $row['id'],"unique" => $row['unique']);
		}
		Database::free($result);
		return $arr;
	}
}