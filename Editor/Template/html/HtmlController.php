<?
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/LegacyTemplateController.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class HtmlController extends LegacyTemplateController {
    
    function HtmlController($id) {
        parent::LegacyTemplateController($id);
    }

	function create($page) {
		$sql="insert into html (page_id,html,valid) values (".$page->getId().",".Database::text('<h1>'.StringUtils::escapeXML($page->getTitle()).'</h1>').",1)";
		Database::insert($sql);
	}
	
	function delete() {
		$sql="delete from html where page_id=".$this->id;
		Database::delete($sql);
	}
    
    function build() {
		$sql="select html,valid,title from html where page_id=".$this->id;
		$row = Database::selectFirst($sql);
		$data = '<html xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/html/1.0/">';
		if (strlen($row['title'])>0) {
			$data.='<title>'.StringUtils::escapeXML($row['title']).'</title>';
		}
		if ($row['valid']) {
			$data.='<content valid="true">'.$row['html'].'</content>';
		} else {
			$data.='<content valid="false"><![CDATA['.$row['html'].']]></content>';
		}
		$data.= '</html>';
        return array('data' => $data, 'dynamic' => false, 'index' => '');
    }

    function import(&$node) {
    }
	
	function dynamic($state) {
	}
    
}
?>