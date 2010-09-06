<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */

require_once($basePath.'Editor/Classes/InternalSession.php');
require_once($basePath.'Editor/Classes/Database.php');
require_once($basePath.'Editor/Classes/TemplateController.php');

class PublishingService {
	
	function publishPage($id) {
		global $basePath;
		require_once($basePath.'Editor/Classes/Page.php');
		
		$dynamic=false;
		$data='';
		$index='';
		$sql="select template.unique from page,template where page.template_id=template.id and page.id=".$id;
		if ($row = Database::selectFirst($sql)) {
		    if ($controller = TemplateController::getController($row['unique'],$id)) {
		        $result = $controller->build();
		        $data = $result['data'];
		        $index = $result['index'];
		        $dynamic = $result['dynamic'];
		    }
		}
		$sql="update page set".
			" data=".Database::text($data).
			",`index`=".Database::text($index).
			",dynamic=".Database::boolean($dynamic).
			",published=now()".
			" where id=".$id;
		Database::update($sql);
		$sql="insert into page_history (page_id,user_id,data,time) values (".$id.",".InternalSession::getUserId().",".Database::text($data).",now())";
		Database::insert($sql);

		// Clear page previews
		$page = Page::load($id);
		$page->clearPreviews();
	}

	function publishAll() {
		global $basePath;
		require_once($basePath.'Editor/Classes/Hierarchy.php');
		require_once($basePath.'Editor/Classes/Object.php');

		$pages = PublishingService::getUnpublishedPages();
		foreach ($pages as $page) {
			PublishingService::publishPage($page['id']);
		}
		
		$hierarchies = PublishingService::getUnpublishedHierarchies();
		foreach ($hierarchies as $hierarchy) {
			$obj = Hierarchy::load($hierarchy['id']);
			if ($obj) {
				$obj->publish();
			}
		}
		
		$objects = PublishingService::getUnpublishedObjects();
		foreach ($objects as $object) {
			$object->publish();
		}
		
	}
	
	function getUnpublishedPages() {
		$sql="select page.id,page.title,template.unique as template from page,template where page.template_id=template.id and changed>published";
		return Database::selectAll($sql);
	}
	
	function getUnpublishedHierarchies() {
		$sql="select id,name from hierarchy where changed>published";
		return Database::selectAll($sql);
	}
	
	function getUnpublishedObjects() {
		$result = array();
		$sql = "select id from object where updated>published";
		$ids = Database::getIds($sql);
		foreach ($ids as $id) {
			$result[] = Object::load($id);
		}
		return $result;
	}
}