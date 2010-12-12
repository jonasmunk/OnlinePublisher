<?
require_once($basePath.'Editor/Classes/Page.php');
require_once($basePath.'Editor/Classes/LegacyTemplateController.php');

class PageService {
	
	function createPageHistory($id,$data) {
		$sql="insert into page_history (page_id,user_id,data,time) values (".$id.",".InternalSession::getUserId().",".Database::text($data).",now())";
		Database::insert($sql);
	}
	
	function exists($id) {
		return !Database::isEmpty("select id from page where id=".Database::int($id));
	}
	
	function getPagePreview($id,$template) {
		$data = '';
	    if ($controller = LegacyTemplateController::getController($template,$id)) {
	        $result = $controller->build($id);
	        $data = $result['data'];
	    }
		return $data;
	}
	
	function saveSnapshot($id) {
		$page = Page::load($id);
		if ($page) {
			$template = $page->getTemplateUnique();
			$data = PageService::getPagePreview($id,$template);
			PageService::createPageHistory($id,$data);
		}
	}
}