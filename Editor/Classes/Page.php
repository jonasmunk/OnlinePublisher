<?
require_once($basePath.'Editor/Classes/Services/PublishingService.php');
require_once($basePath.'Editor/Classes/Services/FileSystemService.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');
		
class Page {

    var $id;
    var $title;
    var $description;
    var $keywords;
    var $templateId;
    var $templateUnique;
    var $frameId;
    var $designId;
    var $language;
    var $searchable;
    var $disabled;
    var $changed;
    var $data;
	var $name;
	var $path;
	var $nextPage;
	var $previousPage;
    
    function Page() {
    }
	
	function toUnicode() {
		$this->title = mb_convert_encoding($this->title, "UTF-8","ISO-8859-1");
		$this->description = mb_convert_encoding($this->description, "UTF-8","ISO-8859-1");
		$this->keywords = mb_convert_encoding($this->keywords, "UTF-8","ISO-8859-1");
		$this->path = mb_convert_encoding($this->path, "UTF-8","ISO-8859-1");
	}
    
    function setId($id) {
        $this->id = $id;
    }
    
    function getId() {
        return $this->id;
    }
    
    function setName($name) {
        $this->name = $name;
    }
    
    function getName() {
        return $this->name;
    }

	function setPath($path) {
	    $this->path = $path;
	}

	function getPath() {
	    return $this->path;
	}
	

    function setTitle($title) {
        $this->title = $title;
    }

    function getTitle() {
        return $this->title;
    }
    
    function setDescription($description) {
        $this->description = $description;
    }

    function getDescription() {
        return $this->description;
    }
    
    function setKeywords($keywords) {
        $this->keywords = $keywords;
    }

    function getKeywords() {
        return $this->keywords;
    }
    
    function setData($data) {
        $this->data = $data;
    }

    function getData() {
        return $this->data;
    }
    
    function setTemplateId($templateId) {
        $this->templateId = $templateId;
    }

    function getTemplateId() {
        return $this->templateId;
    }
    
    function setFrameId($frameId) {
        $this->frameId = $frameId;
    }

    function getFrameId() {
        return $this->frameId;
    }
    
    function setDesignId($designId) {
        $this->designId = $designId;
    }

    function getDesignId() {
        return $this->designId;
    }

    function setLanguage($language) {
        $this->language = $language;
    }

    function getLanguage() {
        return $this->language;
    }
    
    function setSearchable($searchable) {
        $this->searchable = $searchable;
    }

    function getSearchable() {
        return $this->searchable;
    }

    function setDisabled($disabled) {
        $this->disabled = $disabled;
    }

    function getDisabled() {
        return $this->disabled;
    }

    function getChanged() {
        return $this->changed;
    }
    
	function setNextPage($nextPage) {
	    $this->nextPage = $nextPage;
	}

	function getNextPage() {
	    return $this->nextPage;
	}
	
	function setPreviousPage($previousPage) {
	    $this->previousPage = $previousPage;
	}

	function getPreviousPage() {
	    return $this->previousPage;
	}
	

/////////////////////////// Special ///////////////////////////

	/**
	 * WARNING: Only for persistent pages
	 */
    function getTemplateUnique() {
        return $this->templateUnique;
    }
    
    function getHierarchyItem() {
        $sql="select * from hierarchy_item where target_type='page' and target_id=".$this->id;
        return Database::selectFirst($sql);
    }
	
	function getTemplateController() {
		// TODO: use method on TemplateController
		global $basePath;
		$unique = $this->templateUnique;
	    $controllerPath = $basePath.'Editor/Template/'.$unique.'/'.ucfirst($unique).'Controller.php';
	    if (file_exists($controllerPath)) {
		    require_once $controllerPath;
		    $controllerClassName = ucfirst($unique).'Controller';
	        $controller = new $controllerClassName($this->id);
	        return $controller;
	    } else {
	        return false;
	    }
	}
	
	function getIn2iGuiIcon() {
	    return 'common/page';
	}

///////////////////////////// Export //////////////////////////

	function export() {
		global $baseUrl;
		$xml = '<package>'.
		'<info>'.
		'<origin site="'.$baseUrl.'"/>'.
		'</info>'.
		'<part type="page" subtype="'.$this->templateUnique.'">'.
		'<parameter key="title">'.StringUtils::escapeXML($this->title).'</parameter>'.
		'<parameter key="language">'.StringUtils::escapeXML($this->language).'</parameter>'.
		'<parameter key="description">'.StringUtils::escapeXML($this->description).'</parameter>'.
		'<parameter key="keywords">'.StringUtils::escapeXML($this->keywords).'</parameter>'.
		'<sub>'.
		$this->data.
		'</sub>'.
		'</part>'.
		'</package>';
		return $xml;
	}
	
	function reconstruct($historyId) {
		global $basePath;
		require_once($basePath.'Editor/Libraries/domit/xml_domit_include.php');
		
		$success = false;
		if ($controller = TemplateService::getController($this->templateUnique)) {
			$sql = "select data from page_history where id=".$historyId;
			if ($row = Database::selectFirst($sql)) {
				if ($doc = DOMUtils::parse($row['data'])) {
					$controller->import($this->id,$doc);
				}
			}
		} else if ($controller = $this->getTemplateController()) {
			$sql = "select data from page_history where id=".$historyId;
			if ($row = Database::selectFirst($sql)) {
				$document = new DOMIT_Document();
				$doc &= $document;
				if ($doc->parseXML($row['data'])) {
					$controller->import($doc);
				}
			}
			
	    	$sql = "update page set changed=now() where id=".$this->id;
			Database::update($sql);
		}
		return $success;
	}
    
///////////////////////// Persistence /////////////////////////
    
    function load($id) {
        $output = false;
        $sql="select page.*,UNIX_TIMESTAMP(page.changed) as changed_unix,template.unique from page,template where template.id=page.template_id and page.id=".$id;
        if ($row = Database::selectFirst($sql)) {
            $output = new Page();
            $output->setId($row['id']);
            $output->setName($row['name']);
            $output->setPath($row['path']);
            $output->setTemplateId($row['template_id']);
            $output->templateUnique = $row['unique'];
            $output->setDesignId($row['design_id']);
            $output->setFrameId($row['frame_id']);
            $output->setTitle($row['title']);
            $output->setDescription($row['description']);
            $output->setKeywords($row['keywords']);
            $output->setLanguage($row['language']);
            $output->setData($row['data']);
            $output->setSearchable($row['searchable']==1);
            $output->setDisabled($row['disabled']==1);
            $output->setNextPage($row['next_page']);
            $output->setPreviousPage($row['previous_page']);
			$output->changed=$row['changed_unix'];
        }
        return $output;
    }

	function create() {
		$sql="insert into page (title,description,keywords,path,template_id,created,changed,published,frame_id,design_id,language,next_page,previous_page) values (".
		Database::text($this->title).
		",".Database::text($this->description).
		",".Database::text($this->keywords).
		",".Database::text($this->path).
		",".$this->templateId.
		",now()".
		",now()".
		",now()".
		",".$this->frameId.
		",".$this->designId.
		",".Database::text($this->language).
		",".Database::int($this->nextPage).
		",".Database::int($this->previousPage).
		")";
		$this->id=Database::insert($sql);
		$this->_createTemplate();
	}
	
	function save() {
		if ($this->id>0) {
			$sql="update page set".
			" title=".Database::text($this->title).
			",description=".Database::text($this->description).
			",path=".Database::text($this->path).
			",keywords=".Database::text($this->keywords).
			",language=".Database::text($this->language).
			",searchable=".Database::boolean($this->searchable).
			",disabled=".Database::boolean($this->disabled).
			" where id=".$this->id;
			Database::update($sql);
		}
	}

	function _createTemplate() {
		global $basePath;
		$template = Page::translateToTemplateUnique($this->templateId);
		$this->templateUnique = $template;
		$pageId = $this->id;		
		$controller = $this->getTemplateController();
		if ($controller && method_exists($controller,'create')) {
			$controller->create($this);
		}
	}

	function publish() {
		PublishingService::publishPage($this->id);
	}

	function delete() {
		global $basePath;
		require_once($basePath.'Editor/Classes/EventManager.php');
		$this->_deleteTemplate();

		// Delete the page
		$sql="delete from page where id=".$this->id;
		Database::delete($sql);

		// Delete links
		$sql="delete from link where page_id=".$this->id;
		Database::delete($sql);

		// Delete translations
		$sql="delete from page_translation where page_id=".$this->id." or translation_id=".$this->id;
		Database::delete($sql);

		// Delete security zone relations
		$sql="delete from securityzone_page where page_id=".$this->id;
		Database::delete($sql);

		EventManager::fireEvent('delete','page',$this->templateUnique,$this->id);
	}
	
	function _deleteTemplate() {
		global $basePath;
		$controller = $this->getTemplateController();
		if ($controller && method_exists($controller,'delete')) {
			$controller->delete();
		}
	}

///////////////////////////// Previews //////////////////////////

    function clearPreviews() {
        global $basePath;
        require_once($basePath.'Editor/Classes/FileSystemUtil.php');
        $dir = $basePath.'local/cache/pagepreview/';
        $files = FileSystemService::listFiles($dir);
        foreach ($files as $file) {
            if (preg_match('/'.$this->id.'[a-z_\.]+/i',$file)) {
                @unlink($dir.$file);
            }
        }
    }

//////////////////////// Static functions ///////////////////////


	function translateToTemplateId($unique) {
		$out = false;
		$sql = "select id from template where `unique`=".Database::text($unique);
		if ($row = Database::selectFirst($sql)) {
			$out = $row['id'];
		}
		return $out;
	}

	function translateToTemplateUnique($id) {
		$out = false;
		$sql = "select `unique` from template where id=".$id;
		if ($row = Database::selectFirst($sql)) {
			$out = $row['unique'];
		}
		return $out;
	}
	
	function updateSecureStateOfAllPages() {
		$sql = "update page set secure=1";
		Database::update($sql);
		$sql = "update page left join securityzone_page on page.id=securityzone_page.page_id set page.secure=0 where securityzone_page.securityzone_id is null";
		Database::update($sql);
	}
	
	function markChanged($id) {
	    $sql = "update page set changed=now() where id=".$id;
		Database::update($sql);
	}
	
	function isChanged($id) {
		$sql="select changed-published as delta from page where id=".$id;
		$row = Database::selectFirst($sql);
		if ($row['delta']>0) {
			return true;
		}
		else {
			return false;
		}
	}
}
?>