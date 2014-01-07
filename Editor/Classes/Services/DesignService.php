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
	static function getAvailableDesigns() {
		global $basePath;
		$names = FileSystemService::listDirs($basePath."style/");
		$out = array();
		foreach ($names as $name) {
			$out[$name] = DesignService::getInfo($name);
		}
		return $out;
	}
	
	static function getInfo($name) {
		global $basePath;
		$path = $basePath."style/".$name."/info/info.json";
		$info = JsonService::readFile($path);
		return $info;
	}
    
    static function _getPartStyleFiles() {
		global $basePath;
        $files = array();
		$names = FileSystemService::listFiles($basePath."style/basic/css/");
        foreach ($names as $name) {
            if (Strings::startsWith($name,'part_')) {
                $files[] = "style/basic/css/".$name;
            }
        }
        return $files;
    }
  
	static function rebuild() {
		global $basePath;
        
        $designs = DesignService::getAvailableDesigns();
        foreach ($designs as $key => $info) {
            if (!isset($info->build)) {
                continue;
            }
            if (isset($info->build->css)) {
                
                
                $data = '/* '.Strings::toJSON($info->build->css).' */';
                
                foreach ($info->build->css as $file) {
                    if ($file[0]=='@') {
                        if ($file=='@parts') {
                            $data .= PHP_EOL . '/* style/basic/css/document.css */' . PHP_EOL;
        			        $data .= file_get_contents($basePath . 'style/basic/css/document.css');
                            
                            $partFiles = DesignService::_getPartStyleFiles();
                            foreach ($partFiles as $partFile) {
                                $data .= PHP_EOL . '/* '.$partFile.' */' . PHP_EOL;
            			        $data .= file_get_contents($basePath . $partFile);      
                            }
                        }
                    } else {
                        $data .= PHP_EOL . '/* '.$file.' */' . PHP_EOL;
            			$data .= file_get_contents($basePath.$file);                        
                    }
                }
                {
                    $cssFile = $basePath."style/".$key."/css/style.private.tmp.css";
                    FileSystemService::writeStringToFile($data,$cssFile);
                    DesignService::_compress($cssFile,$basePath."style/".$key."/css/style.private.css");
                }
                {
        			$huiCss = file_get_contents($basePath.'hui/bin/combined.site.css');
                    $huiCss = preg_replace("/(url\\(['\"]?)(..\/gfx\/)([^\\)]+\\))/u", "$1../../../hui/gfx/$3", $huiCss);
                    $data = $huiCss . $data;
                    $cssFile = $basePath."style/".$key."/css/style.tmp.css";
                    FileSystemService::writeStringToFile($data,$cssFile);
                    DesignService::_compress($cssFile,$basePath."style/".$key."/css/style.css");
                }
            }
            if (isset($info->build->js)) {
                $data = '/* '.Strings::toJSON($info->build->js).' */' . PHP_EOL . PHP_EOL;
                
                $data .= PHP_EOL . '/* style/basic/js/OnlinePublisher.js */' . PHP_EOL;
    			$data .= file_get_contents($basePath."style/basic/js/OnlinePublisher.js");
                
                
                foreach ($info->build->js as $file) {
                    $data .= PHP_EOL . '/* '.$file.' */' . PHP_EOL;
        			$data .= file_get_contents($basePath.$file);
                }
                {
                    $jsFile = $basePath."style/".$key."/js/script.private.tmp.js";
                    FileSystemService::writeStringToFile($data,$jsFile);
                    DesignService::_compress($jsFile,$basePath."style/".$key."/js/script.private.js");
                }
                {
                    $jsFile = $basePath."style/".$key."/js/script.tmp.js";
                    $data = file_get_contents($basePath.'hui/bin/combined.site.js') . $data;
                    FileSystemService::writeStringToFile($data,$jsFile);
                    DesignService::_compress($jsFile,$basePath."style/".$key."/js/script.js");
                }
            }
        }
	}
    
	static function _compress($in,$out) {
        global $basePath;
        $cmd = "java -jar ".$basePath."hui/tools/yuicompressor-2.2.4.jar ".$in." --charset UTF-8 -o ".$out;
        shell_exec($cmd);
    }

	static function loadParameters($id) {
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
	
	static function _getType($key,$info) {
		if ($info->parameters) {
			foreach ($info->parameters as $parameter) {
				if ($parameter->key == $key) {
					return $parameter->type;
				}
			}
		}
		return null;
	}
	
	static function saveParameters($id,$parameters) {
		$design = Design::load($id);
		$info = DesignService::getInfo($design->getUnique());
		$sql = "delete from design_parameter where design_id=".Database::int($id);
		Database::delete($sql);
		$xml = '';
		foreach ($parameters as $key => $value) {
			$type = DesignService::_getType($key,$info);
			$sql = "insert into design_parameter (design_id,`key`,`value`) values (".Database::int($id).",".Database::text($key).",".Database::text($value).")";
			Database::insert($sql);
			if (Strings::isNotBlank($value)) {				
				$xml.='<parameter key="'.$key.'">';
				if ($type=='image') {
					$image = Image::load($value);
					if ($image) {
						$xml.='<image id="'.$image->getId().'" width="'.$image->getWidth().'" height="'.$image->getHeight().'"/>';
					}
				} else {
					$xml.=Strings::escapeXML($value);
				}
				$xml.='</parameter>';
			}
		}
		
		$design->setParameters($xml);
		$design->save();
		$design->publish();
		Log::debug($design);
	}
	
	static function validate($name) {
		global $basePath;
		$valid = true;
		$info = DesignService::getInfo($name);
		if ($info!==null) {
			$valid = $valid && Strings::isNotBlank($info->name);
			$valid = $valid && Strings::isNotBlank($info->description);
			$valid = $valid && Strings::isNotBlank($info->owner);
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