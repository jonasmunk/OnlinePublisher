<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class DesignService {
	
	/**
	 * Finds all available designs
	 * @return array An array of the unique names of all available designs
	 * @static
	 */
	function getAvailableDesigns() {
		global $basePath;
		$names = FileSystemService::listDirs($basePath."style/");
		$out = array();
		foreach ($names as $name) {
			$out[$name] = DesignService::getInfo($name);
		}
		return $out;
	}
	
	function getInfo($name) {
		global $basePath;
		$path = $basePath."style/".$name."/info/info.json";
		$info = JsonService::readFile($path);
		return $info;
	}

	function loadParameters($id) {
		$out = array();
		$design = Design::load($id);
		$info = DesignService::getInfo($design->getUnique());
		if ($info->parameters) {
			$sql = "select * from design_parameter where design_id=".Database::int($id);
			$rows = Database::selectAll($sql);
			foreach ($info->parameters as $parameter) {
				$arr = get_object_vars($parameter);
				foreach ($rows as $row) {
					if ($row['key']==$arr['key']) {
						$arr['value'] = $row['value'];
						break;
					}
				}
				$out[] = $arr;
			}
		}
		return $out;
	}
	
	function _getType($key,$info) {
		if ($info->parameters) {
			foreach ($info->parameters as $parameter) {
				if ($parameter->key == $key) {
					return $parameter->type;
				}
			}
		}
		return null;
	}
	
	function saveParameters($id,$parameters) {
		$design = Design::load($id);
		$info = DesignService::getInfo($design->getUnique());
		$sql = "delete from design_parameter where design_id=".Database::int($id);
		Database::delete($sql);
		$xml = '';
		foreach ($parameters as $key => $value) {
			$type = DesignService::_getType($key,$info);
			$sql = "insert into design_parameter (design_id,`key`,`value`) values (".Database::int($id).",".Database::text($key).",".Database::text($value).")";
			Database::insert($sql);
			if (StringUtils::isNotBlank($value)) {				
				$xml.='<parameter key="'.$key.'">';
				if ($type=='image') {
					$image = Image::load($value);
					if ($image) {
						$xml.='<image id="'.$image->getId().'" width="'.$image->getWidth().'" height="'.$image->getHeight().'"/>';
					}
				} else {
					$xml.=StringUtils::escapeXML($value);
				}
				$xml.='</parameter>';
			}
		}
		
		$design->setParameters($xml);
		$design->save();
		$design->publish();
		Log::debug($design);
	}
	
	function validate($name) {
		global $basePath;
		$valid = true;
		$info = DesignService::getInfo($name);
		if ($info!==null) {
			$valid = $valid && StringUtils::isNotBlank($info->name);
			$valid = $valid && StringUtils::isNotBlank($info->description);
			$valid = $valid && StringUtils::isNotBlank($info->owner);
		} else {
			$valid = false;
		}
		$valid = $valid && !file_exists($basePath."style/".$name."/info/info.xml");
		$valid = $valid && file_exists($basePath."style/".$name."/info/Preview128.png");
		$valid = $valid && file_exists($basePath."style/".$name."/info/Preview64.png");
		$valid = $valid && file_exists($basePath."style/".$name."/xslt/main.xsl");
		$valid = $valid && file_exists($basePath."style/".$name."/css/style.php");
		$valid = $valid && file_exists($basePath."style/".$name."/css/overwrite.css");
		return $valid;
	}
}