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

  static $useYUI = false;

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

    static function _inlineJS() {
        $files = FileSystemService::find([
            'dir' => FileService::getPath('style'),
            'extension' => 'xsl'
        ]);
        foreach ($files as $xslFile) {
            $xsl = file_get_contents($xslFile);
            $callTemplate = Strings::extract($xsl,'<xsl:call-template name="util:script-inline">','</xsl:call-template>');
            foreach ($callTemplate as $template) {
                $minified = null;
                if (preg_match("/<xsl:with-param name=\"file\" select=\"'([^']+)'\"/", $template, $found)) {
                	$jsFile = FileService::getPath($found[1]);
                    $minified = DesignService::_compressToString($jsFile);
                } else {
                    continue;
                }

                $compiledParam = Strings::extract($template,'<xsl:with-param name="compiled">','</xsl:with-param>');
                if (count($compiledParam)==1) {
                    $compiledParam = $compiledParam[0];
                    $new = '<xsl:with-param name="compiled"><![CDATA[' . $minified . ']]></xsl:with-param>';
                    $replacement = str_replace($compiledParam,$new,$template);
                    $xsl = str_replace($template,$replacement,$xsl);
                }
            }
            FileSystemService::writeStringToFile($xsl,$xslFile);
        }
    }
    
    static function _getFileParameter($xsl,$default='inline.css') {
        if (preg_match("/<xsl:with-param[\\W]+name=\"file\"[\\W]+select=\"'([^']+)'\"/uim", $xsl,$matches)) {
            return $matches[1];
        }
        return $default;
    }

    static function _embedInlineCSS($design) {
		global $basePath;

        Log::info('Embedding inline css for: ' . $design);
        $inlineFileMinified = $basePath."style/".$design."/css/inline.min.css";
        $xslFile = $basePath.'style/' .$design . '/xslt/main.xsl';
        DesignService::_compress($inlineFile,$inlineFileMinified);
        $xsl = file_get_contents($xslFile);
        $callTemplate = Strings::extract($xsl,'<xsl:call-template name="util:style-inline">','</xsl:call-template>');
        foreach ($callTemplate as $template) {
            $compiledParam = Strings::extract($template,'<xsl:with-param name="compiled">','</xsl:with-param>');
            if (count($compiledParam)==1) {
                $compiledParam = $compiledParam[0];

                $cssFileName = DesignService::_getFileParameter($template);
                $cssFile = $basePath."style/".$design."/css/".$cssFileName;
                if (file_exists($cssFile)) {
                    $cssMin = DesignService::_compressToString($cssFile);
					$cssMin = DesignService::adjustURLs($cssMin,$design);
                    $new = '<xsl:with-param name="compiled">' . $cssMin . '</xsl:with-param>';
                    $replacement = str_replace($compiledParam,$new,$template);
                    $xsl = str_replace($template,$replacement,$xsl);
                } else {
                    Log::warn('Inline file not found: ' . $cssFile);
                }
            }
        }
        unlink($inlineFileMinified);
        FileSystemService::writeStringToFile($xsl,$xslFile);
    }
	
	static function adjustURLs($css,$design) {
		return preg_replace("/url\((['\"]{0,1})..\//um", 'url($1<xsl:value-of select="\$path"/><xsl:value-of select="\$timestamp-url"/>style/'.$design.'/', $css);
	}

	static function rebuild($design) {
		global $basePath;

        $designs = DesignService::getAvailableDesigns();
        foreach ($designs as $key => $info) {
            if ($design!==null && $design!==$key) {
                continue;
            }
            if (isset($info->build)) {
                if (isset($info->build->css)) {

                    $imports = array();

                    $data = '/* '.Strings::toJSON($info->build->css).' */';

                    foreach ($info->build->css as $file) {
                        if ($file[0]=='@') {
                            if ($file=='@parts') {
            			        $data .= DesignService::_read('style/basic/css/document.css');
                                $imports[] = 'style/basic/css/document.css';

                                $partFiles = DesignService::_getPartStyleFiles();
                                foreach ($partFiles as $partFile) {
                			        $data .= DesignService::_read($partFile);
                                    $imports[] = $partFile;
                                }
                            }
                        } else {
                			$data .= DesignService::_read($file);
                            $imports[] = $file;
                        }
                    }
                    {
                        $cssFile = $basePath."style/".$key."/css/style.private.tmp.css";
                        FileSystemService::writeStringToFile($data,$cssFile);
                        DesignService::_compress($cssFile,$basePath."style/".$key."/css/style.private.css");
                        unlink($cssFile);
                    }
                    {
            			$huiCss = DesignService::_read('hui/bin/joined.site.css');
                        $huiCss = preg_replace("/(url\\(['\"]?)(..\/gfx\/)([^\\)]+\\))/u", "$1../../../hui/gfx/$3", $huiCss);
                        $data = $huiCss . $data;
                        $cssFile = $basePath."style/".$key."/css/style.tmp.css";
                        FileSystemService::writeStringToFile($data,$cssFile);
                        DesignService::_compress($cssFile,$basePath."style/".$key."/css/style.css");
                        unlink($cssFile);
                    }
                    {
                        $data = '';
                        foreach ($imports as $path) {
                            $data .= '@import url(../../../' . $path . ');' . PHP_EOL;
                        }
                        $cssFile = $basePath."style/".$key."/css/style.dev.css";
                        FileSystemService::writeStringToFile($data,$cssFile);
                    }
                }
                if (isset($info->build->js)) {

                    $imports = array();
                    $data = '/* '.Strings::toJSON($info->build->js).' */' . PHP_EOL . PHP_EOL;

                    $data .= DesignService::_read('style/basic/js/OnlinePublisher.js');

                    foreach ($info->build->js as $file) {
                        $data .= DesignService::_read($file);
                        $imports[] = $file;
                    }
                    {
                        $jsFile = $basePath."style/".$key."/js/script.private.tmp.js";
                        FileSystemService::writeStringToFile($data,$jsFile);
                        DesignService::_compress($jsFile,$basePath."style/".$key."/js/script.private.js");
                    }
                    {
                        $jsFile = $basePath."style/".$key."/js/script.tmp.js";
                        $data = DesignService::_read('hui/bin/joined.site.js') . $data;
                        FileSystemService::writeStringToFile($data,$jsFile);
                        DesignService::_compress($jsFile,$basePath."style/".$key."/js/script.js");
                    }
                    {
                        $data = '';
                        $jsFile = $basePath."style/".$key."/js/script.dev.js";
                        foreach ($imports as $path) {
                            $data.= 'document.write(\'<script type="text/javascript" src="\' + _editor.context + \'' . $path . '"></script>\');';
                        }
                        FileSystemService::writeStringToFile($data,$jsFile);
                    }
                }
            }
            DesignService::_embedInlineCSS($key);
        }
        DesignService::_inlineJS();
	}

    static function _read($path) {
        global $basePath;
        $data = PHP_EOL . '/* '.$path.' */' . PHP_EOL;
		$data .= file_get_contents($basePath.$path);
        return $data;
    }

	static function _compress($in,$out) {
        global $basePath;
        if (DesignService::$useYUI) {
          $cmd = "java -jar ".$basePath."hui/tools/yuicompressor-2.4.8.jar ".$in." --charset UTF-8 -o ".$out;
        } else {
          $cmd = "minify ".$in." -o ".$out;
        }
        shell_exec($cmd);
    }

	static function _compressToString($in) {
        global $basePath;
        $out = $in . '.tmp';
        DesignService::_compress($in,$out);
        $str = file_get_contents($out);
        unlink($out);
        return $str;
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
	}



	static function getFrameOptions() {
		return '
			<item title="{None; da:Ingen}" value=""/>
			<item title="{Light; da:Let}" value="light"/>
			<item title="Elegant" value="elegant"/>
			<item title="{Shaddow; da:Skygge}" value="shadow_slant"/>
			<item title="Polaroid" value="polaroid"/>';
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
		if ($info!==null && !isset($info->build)) {
		    $valid = $valid && file_exists($basePath."style/".$name."/css/style.php");
        } else {
            $valid = $valid && !file_exists($basePath."style/".$name."/css/style.php");
        }
        // TODO (jm)
		$valid = $valid && file_exists($basePath."style/".$name."/css/editor.css");
		return $valid;
	}
}