<?
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once($basePath.'Editor/Classes/TemplateController.php');

class HtmlController extends TemplateController {
    
    function HtmlController($id) {
        parent::TemplateController($id);
    }

	function create($page) {
		$sql="insert into html (page_id,html,valid) values (".$page->getId().",".Database::text('<h1>'.encodeXML($page->getTitle()).'</h1>').",1)";
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
			$data.='<title>'.encodeXML($row['title']).'</title>';
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
		$html = '';
		$valid = true;
		$root =& $node->documentElement;
		if ($root->getAttribute('valid')=='false') {
			$valid = false;
			$html = $root->getText();
		} else {
			$children =& $root->childNodes;
			for ($i=0;$i<count($children);$i++) {
				$html.=$children[$i]->toString();
			}
		}
		
		$sql = "update html set".
		" html=".Database::text($html).
		",valid=".Database::text($valid).
		" where page_id=".$this->id;
		Database::update($sql);
    }
	
	function dynamic($state) {
	}
    
}
?>