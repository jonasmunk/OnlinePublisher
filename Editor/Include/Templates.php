<?php
/**
 * @package OnlinePublisher
 * @subpackage Include
 */
require_once($basePath.'Editor/Classes/FileSystemUtil.php');

// returns all available templates
function getAvailableTemplates() {
	global $basePath;
	$arr = FileSystemUtil::listDirs($basePath."Editor/Template/");
	for ($i=0;$i<count($arr);$i++) {
		if (substr($arr[$i],0,3)=='CVS') {
			unset($arr[$i]);
		}
	}
	return $arr;
}

// returns all installed templates
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


// returns all installed templates sorted by name
function getTemplatesSorted() {
	$output = array();
	$templates = getInstalledTemplates();
	for ($i=0;$i<count($templates);$i++) {
		$unique = $templates[$i]['unique'];
		$info = getTemplateInfo($unique);
		$info['id']=$templates[$i]['id'];
		$output[]=$info;
	}
	usort($output,'compareTemplates');
	return $output;
}

// returns all installed templates with unique name as key
function getTemplatesKeyed() {
	$output = array();
	$templates = getInstalledTemplates();
	for ($i=0;$i<count($templates);$i++) {
		$unique = $templates[$i]['unique'];
		$info = getTemplateInfo($unique);
		$info['id']=$templates[$i]['id'];
		$output[$unique]=$info;
	}
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

require_once($basePath.'Editor/Include/Session.php');

function getTemplateInfo($unique) {
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
?>