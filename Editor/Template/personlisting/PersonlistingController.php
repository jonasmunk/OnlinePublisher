<?
/**
 * @package OnlinePublisher
 * @subpackage Templates.Search
 */
require_once($basePath.'Editor/Classes/TemplateController.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class PersonlistingController extends TemplateController {
    
    function PersonlistingController($id) {
        parent::TemplateController($id);
    }

	function create($page) {
		$sql="insert into personlisting (page_id,title) values (".$page->getId().",".Database::text($page->getTitle()).")";
		Database::insert($sql);
	}
	
	function delete() {
		$sql="delete from personlisting where page_id=".$this->id;
		Database::delete($sql);
		$sql="delete from personlisting_persongroup where page_id=".$this->id;
		Database::delete($sql);
	}
    
    function build() {
		$data='<personlisting xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/personlisting/1.0/">';
		$sql="select * from personlisting where page_id=".$this->id;
		$row = Database::selectFirst($sql);
		$data.=
		'<title>'.StringUtils::escapeXML($row['title']).'</title>'.
		'<text>'.StringUtils::escapeXMLBreak($row['text'],'<break/>').'</text>'.
		'<!--dynamic-->'.
		'</personlisting>';
        return array('data' => $data, 'dynamic' => true, 'index' => '');
    }
    
	function dynamic(&$state) {
		$sql="select object.data from persongroup_person,object,personlisting_persongroup where object.id=persongroup_person.person_id and personlisting_persongroup.persongroup_id=persongroup_person.persongroup_id and personlisting_persongroup.page_id=".$this->id." order by object.title";
		$result = Database::select($sql);
		$xml='<persons>';
		while ($row = Database::next($result)) {
			$xml.=$row['data'];
		}
		$xml.='</persons>';
		Database::free($result);
		$state['data']=str_replace('<!--dynamic-->', $xml, $state['data']);
	}
}
?>