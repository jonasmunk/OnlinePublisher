<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class Template extends Entity {
        
	var $unique;

    function Template() {
    }
	
	function setUnique($unique) {
	    $this->unique = $unique;
	}

	function getUnique() {
	    return $this->unique;
	}
	
	function getName() {
		$info = TemplateService::getTemplateInfo($this->unique);
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
}