<?
require_once($basePath.'Editor/Classes/Page.php');
require_once($basePath.'Editor/Classes/TemplateController.php');

class PageService {
	
	function createPageHistory($id,$data) {
		$sql="insert into page_history (page_id,user_id,data,time) values (".$id.",".InternalSession::getUserId().",".Database::text($data).",now())";
		Database::insert($sql);
	}
	
	function getPagePreview($id,$template) {
		$data = '';
	    if ($controller = TemplateController::getController($template,$id)) {
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