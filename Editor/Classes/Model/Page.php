<?php
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/Services/PublishingService.php');
require_once($basePath.'Editor/Classes/Services/PageService.php');
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
    var $published;
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

    function getPublished() {
        return $this->published;
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
	
	function getIn2iGuiIcon() {
	    return 'common/page';
	}
    
///////////////////////// Persistence /////////////////////////
    
    function load($id) {
		return PageService::load($id);
    }

	function create() {
		return PageService::create($this);
	}
	
	function save() {
		return PageService::save($this);
	}

	function publish() {
		PublishingService::publishPage($this->id);
	}
	
	function remove() {
		$this->delete();
	}

	function delete() {
		return PageService::delete($this);
	}
}
?>