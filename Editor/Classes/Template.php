<?
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Database.php');
require_once($basePath.'Editor/Classes/Object.php');
require_once($basePath.'Editor/Classes/Services/TemplateService.php');
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