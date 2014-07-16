<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Templates
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class WeblogTemplateController extends TemplateController
{
	function WeblogTemplateController() {
		parent::TemplateController('weblog');
	}

	function create($page) {
		$sql="insert into weblog (page_id) values (".Database::int($page->getId()).")";
		Database::insert($sql);
	}

	function delete($page) {
		$sql="delete from weblog where page_id=".Database::int($page->getId());
		Database::delete($sql);
		$sql="delete from weblog_webloggroup where page_id=".Database::int($page->getId());
		Database::delete($sql);
	}

    function build($id) {
		$sql="select * from weblog where page_id=".Database::int($id);
		$row = Database::selectFirst($sql);
		$data = '<weblog xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/weblog/1.0/">';
		$data .= '<title>'.Strings::escapeXML($row['title']).'</title>';
		$data.= '<!--dynamic--></weblog>';
        return array('data' => $data, 'dynamic' => true, 'index' => '');
    }

	function dynamic($id,&$state) {
		$xml = $this->_listEntries($id);
		$state['data'] = str_replace("<!--dynamic-->", $xml, $state['data']);
	}
	
	function _listEntries($id) {
		$xml='';
		$sql="select webloggroup_id as id from weblog_webloggroup where page_id=".Database::int($id);
		$selectedGroups = Database::getIds($sql);
		
		$groups = Webloggroup::search(array('page'=>$id));
		foreach ($groups as $group) {
			$xml.='<group id="'.$group->getId().'" title="'.Strings::escapeXML($group->getTitle()).'" />';
		}
		$xml .= '<list>';
		
		$sql="select distinct object.id,object.data as object_data,page.data as page_data,page.id as page_id,page.path from object,webloggroup_weblogentry,weblog_webloggroup,weblogentry left join page on weblogentry.page_id=page.id where weblog_webloggroup.page_id=".Database::int($id)." and weblog_webloggroup.webloggroup_id=webloggroup_weblogentry.webloggroup_id and webloggroup_weblogentry.weblogentry_id=weblogentry.object_id and object.id=weblogentry.object_id order by weblogentry.date desc";
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
				$xml.='<group id="'.$subRow['id'].'" title="'.Strings::escapeXML($subRow['title']).'"/>';
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

	function ajax($id) {
		$action = Request::getString('action');
		if ($action=='createEntry') {
			$this->createEntry($id);
		} else if ($action=='updateEntry') {
			$this->updateEntry();
		} else if ($action=='deleteEntry') {
			$this->deleteEntry();
		} else if ($action=='loadEntry') {
			$entryId = Request::getInt('entryId');
			if ($entry = Weblogentry::load($entryId)) {
				$entry->loadGroups();
				Response::sendObject($entry);
			} else {
				Response::badRequest();
			}
		}
	}

	function deleteEntry() {
		$entryId = Request::getInt('entryId');
		$entry = Weblogentry::load($entryId);
		if ($entry) {
			if ($page = Page::load($entry->getPageId())) {
				$page->delete();
			}
			$entry->remove();
		}
	}

	function createEntry($id) {
		$title = Request::getString('title');
		$text = Request::getString('text');
		$groups = Request::getIntArrayComma('groups');
		$date = Request::getInt('date');
		if ($title=='' && count($groups)==0) {
			return false;
		}
		$entry = new Weblogentry();
		$entry->setTitle($title);
		$entry->setText($text);
		$entry->setDate($date);
		
		if ($blueprint = $this->getBlueprint($id)) {
			$page = new Page();
			$page->setTitle($title);
			$page->setTemplateId($blueprint->getTemplateId());
			$page->setDesignId($blueprint->getDesignId());
			$page->setFrameId($blueprint->getFrameId());
			$page->create();
			if ($page->getTemplateUnique()=='html') {
				$sql = "update html set html=".Database::text($text).",title=".Database::text($title).",valid=0 where page_id=".$page->getId();
				Database::update($sql);
			}
			$page->publish();
			$entry->setPageId($page->getId());
		}
		$entry->create();
		$entry->publish();
		$entry->changeGroups($groups);
	}
	
	function getBlueprint($id) {
		$sql = "select pageblueprint_id from weblog where pageblueprint_id>0 and page_id = ".Database::int($id);
		if ($row = Database::selectFirst($sql)) {
			return Pageblueprint::load($row['pageblueprint_id']);
		}
		return null;
	}

	function updateEntry() {
		$id = Request::getInt('entryId');
		$title = Request::getString('title');
		$text = Request::getString('text');
		$groups = Request::getIntArrayComma('groups');
		$date = Request::getInt('date');
		if ($title!='' && count($groups)>0) {
			$entry = Weblogentry::load($id);
			$entry->setTitle($title);
			$entry->setText($text);
			$entry->setDate($date);
			$entry->save();
			$entry->changeGroups($groups);
			$entry->publish();
			
			if ($page = Page::load($entry->getPageId())) {
				if ($page->getTemplateUnique()=='html') {
					$sql = "update html set html=".Database::text($text).",title=".Database::text($title).",valid=0 where page_id=".$page->getId();
					Database::update($sql);
					$page->publish();
				}
			}
		}
	}
}