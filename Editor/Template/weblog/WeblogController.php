<?
/**
 * @package OnlinePublisher
 * @subpackage Templates.Weblog
 */
require_once($basePath.'Editor/Classes/TemplateController.php');
require_once $basePath.'Editor/Classes/Part.php';
require_once $basePath.'Editor/Classes/Weblogentry.php';
require_once($basePath.'Editor/Classes/Request.php');
require_once($basePath.'Editor/Classes/Page.php');
require_once($basePath.'Editor/Classes/Pageblueprint.php');
require_once($basePath.'Editor/Classes/Webloggroup.php');
require_once($basePath.'Editor/Classes/In2iGui.php');

class WeblogController extends TemplateController {
    
    function WeblogController($id) {
        parent::TemplateController($id);
    }

	function create($page) {
		$sql="insert into weblog (page_id) values (".$page->getId().")";
		Database::insert($sql);
	}
	
	function delete() {
		$sql="delete from weblog where page_id=".$this->id;
		Database::delete($sql);
		$sql="delete from weblog_webloggroup where page_id=".$this->id;
		Database::delete($sql);
	}
	
	function ajax() {
		$action = Request::getString('action');
		if ($action=='createEntry') {
			$this->createEntry();
		} else if ($action=='updateEntry') {
			$this->updateEntry();
		} else if ($action=='deleteEntry') {
			$this->deleteEntry();
		} else if ($action=='loadEntry') {
			$id = Request::getInt('entryId');
			$entry = Weblogentry::load($id);
			$entry->loadGroups();
			$entry->toUnicode();
			In2iGui::sendObject($entry);
		}
	}
	
	function dynamic(&$state) {
		$xml=$this->listEntries();
		$state['data'] = str_replace("<!--dynamic-->", $xml, $state['data']);
	}

	function listEntries() {
		$xml='';
		$sql="select webloggroup_id as id from weblog_webloggroup where page_id=".getPageId();
		$selectedGroups = Database::getIds($sql);
		
		$groups = WeblogGroup::search(array('page'=>$this->id));
		foreach ($groups as $group) {
			$xml.='<group id="'.$group->getId().'" title="'.encodeXML($group->getTitle()).'" />';
		}
		$xml .= '<list>';
		
		$sql="select distinct object.id,object.data as object_data,page.data as page_data,page.id as page_id,page.path from object,webloggroup_weblogentry,weblog_webloggroup,weblogentry left join page on weblogentry.page_id=page.id where weblog_webloggroup.page_id=".$this->id." and weblog_webloggroup.webloggroup_id=webloggroup_weblogentry.webloggroup_id and webloggroup_weblogentry.weblogentry_id=weblogentry.object_id and object.id=weblogentry.object_id order by weblogentry.date desc";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$xml.='<entry';
			if ($row['path']!='') {
				$xml.=' page-path="'.$row['path'].'"';
			} else if ($row['page_id']!='') {
				$xml.=' page-id="'.$row['page_id'].'"';
			}
			$xml.='>';
			$sql = "select object.title,object.id from webloggroup_weblogentry,object where webloggroup_weblogentry.webloggroup_id=object.id and weblogentry_id=".$row['id']." order by object.title";
			$subResult = Database::select($sql);
			while ($subRow = Database::next($subResult)) {
				$xml.='<group id="'.$subRow['id'].'" title="'.encodeXML($subRow['title']).'"/>';
			}
			Database::free($subResult);
			$xml.=$row['object_data'];
			$xml.=$row['page_data'];
			$xml.='</entry>';
		}
		Database::free($result);
		
		$xml .= '</list>';
		return $xml;
	}

	function deleteEntry() {
		$id = Request::getInt('entryId');
		$entry = Weblogentry::load($id);
		if ($entry) {
			if ($page = Page::load($entry->getPageId())) {
				$page->delete();
			}
			$entry->remove();
		}
	}

	function createEntry() {
		$title = Request::getUnicodeString('title');
		$text = Request::getUnicodeString('text');
		$groups = Request::getPostArray('group');
		$date = Request::getInt('date');
		if ($title=='' && count($groups)==0) {
			return false;
		}
		$entry = new Weblogentry();
		$entry->setTitle($title);
		$entry->setText($text);
		$entry->setDate($date);
		
		if ($blueprint = $this->getBlueprint()) {
			$page = new Page();
			$page->setTitle($title);
			$page->setTemplateId($blueprint->getTemplateId());
			$page->setDesignId($blueprint->getDesignId());
			$page->setFrameId($blueprint->getFrameId());
			$page->create();
			if ($page->getTemplateUnique()=='html') {
				$sql = "update html set html=".sqlText($text).",title=".sqlText($title).",valid=0 where page_id=".$page->getId();
				Database::update($sql);
			}
			$page->publish();
			$entry->setPageId($page->getId());
		}
		$entry->create();
		$entry->publish();
		$entry->changeGroups($groups);
	}
	
	function getBlueprint() {
		$sql = "select pageblueprint_id from weblog where page_id = ".$this->id;
		if ($row = Database::selectFirst($sql)) {
			$blueprint = PageBlueprint::load($row['pageblueprint_id']);
			return $blueprint;
		} else {
			return false;
		}
	}

	function updateEntry() {
		$id = Request::getPostInt('entryId');
		$title = Request::getUnicodeString('title');
		$text = Request::getUnicodeString('text');
		$groups = Request::getPostArray('group');
		$date = Request::getPostInt('date');
		if ($title!='' && count($groups)>0) {
			$entry = Weblogentry::load($id);
			$entry->setTitle($title);
			$entry->setText($text);
			$entry->setDate($date);
			$entry->update();
			$entry->changeGroups($groups);
			$entry->publish();
			
			if ($page = Page::load($entry->getPageId())) {
				if ($page->getTemplateUnique()=='html') {
					$sql = "update html set html=".sqlText($text).",title=".sqlText($title).",valid=0 where page_id=".$page->getId();
					Database::update($sql);
					$page->publish();
				}
			}
		}
	}

    
    function build() {
		$sql="select * from weblog where page_id=".$this->id;
		$row = Database::selectFirst($sql);
		$data = '<weblog xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/weblog/1.0/">';
		$data .= '<title>'.encodeXML($row['title']).'</title>';
		$data.= '<!--dynamic--></weblog>';
        return array('data' => $data, 'dynamic' => true, 'index' => '');
    }

    function import(&$node) {
    }
    
}
?>