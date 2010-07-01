<?
/**
 * @package OnlinePublisher
 * @subpackage Templates.Search
 */
require_once($basePath.'Editor/Classes/TemplateController.php');

class QuicktimeplayerController extends TemplateController {
    
    function QuicktimeplayerController($id) {
        parent::TemplateController($id);
    }

	function create($page) {
		$sql="insert into quicktimeplayer (page_id,title) values (".$page->getId().",".sqlText($page->getTitle()).")";
		Database::insert($sql);
	}
	
	function delete() {
		$sql="delete from quicktimeplayer where page_id=".$this->id;
		Database::delete($sql);
	}
    
    function build() {
		$data = '<quicktimeplayer xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/quicktimeplayer/1.0/">';
		$sql="select * from quicktimeplayer where page_id=".$this->id;
		if ($row = Database::selectFirst($sql)) {
			if ($row['title']!='') {
				$data.='<title>'.encodeXML($row['title']).'</title>';
			}
			if ($row['text']!='') {
				$data.='<text>'.encodeXMLBreak($row['text'],'<break/>').'</text>';
			}
			$data.='<display width="'.$row['width'].'" height="'.$row['height'].'"/>';
			$sql="select data from object where id=".$row['file_id'];
			if ($row = Database::selectFirst($sql)) {
				$data.=$row['data'];
			}
		}
		$data.= '</quicktimeplayer>';
        return array('data' => $data, 'dynamic' => false, 'index' => '');
    }

	function dynamic(&$state) {
		
	}
}
?>