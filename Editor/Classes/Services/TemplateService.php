<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
require_once($basePath.'Editor/Classes/Database.php');
require_once($basePath.'Editor/Classes/Template.php');
require_once($basePath.'Editor/Classes/Log.php');
require_once($basePath.'Editor/Classes/Services/FileSystemService.php');
require_once($basePath.'Editor/Classes/InternalSession.php');

class TemplateService {
	
	function getTemplateByUnique($unique) {
		$sql = "select id,`unique` from `template` where `unique`=".Database::text($unique);
		if ($row = Database::selectFirst($sql)) {
			$template = new Template();
			$template->setId(intval($row['id']));
			$template->setUnique($row['unique']);
			return $template;
		}
		return null;
	}
	
	function getTemplateById($id) {
		$sql = "select id,`unique` from `template` where id=".Database::int($id);
		if ($row = Database::selectFirst($sql)) {
			$template = new Template();
			$template->setId(intval($row['id']));
			$template->setUnique($row['unique']);
			return $template;
		}
		return null;
	}
	
	function getController($type) {
		global $basePath;
		$class = ucfirst($type).'TemplateController';
		$path = $basePath.'Editor/Classes/Templates/'.$class.'.php';
		if (!file_exists($path)) {
			return null;
		}
		require_once $path;
		return new $class;
	}
	
	function getAvailableTemplates() {
		global $basePath;
		$arr = FileSystemService::listDirs($basePath."Editor/Template/");
		for ($i=0;$i<count($arr);$i++) {
			if (substr($arr[$i],0,3)=='CVS') {
				unset($arr[$i]);
			}
		}
		return $arr;
	}
	
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
	
	/**
	 * @static
	 */
	function getTemplatesKeyed() {
		$output = array();
		$templates = TemplateService::getInstalledTemplates();
		for ($i=0;$i<count($templates);$i++) {
			$unique = $templates[$i]['unique'];
			$info = TemplateService::getTemplateInfo($unique);
			$info['id']=$templates[$i]['id'];
			$output[$unique]=$info;
		}
		return $output;
	}

	// returns all installed templates sorted by name
	function getTemplatesSorted() {
		$output = array();
		$templates = TemplateService::getInstalledTemplates();
		for ($i=0;$i<count($templates);$i++) {
			$unique = $templates[$i]['unique'];
			$info = TemplateService::getTemplateInfo($unique);
			$info['id']=$templates[$i]['id'];
			$output[]=$info;
		}
		usort($output,array('TemplateService','compareTemplates'));
		return $output;
	}

	// Used to sort arrays of tools
	function compareTemplates($templateA, $templateB) {
		$a = $templateA['name'];
		$b = $templateB['name'];
		if ($a == $b) {
			return 0;
		}
		return ($a < $b) ? -1 : 1;
	}
	
	function getTemplateInfo($unique) {
		global $basePath;
		if ($out = InternalSession::getSessionCacheVar('template.info.'.$unique)) {
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
			InternalSession::setSessionCacheVar('template.info.'.$unique,$out);
			return $out;
		}
	}
}