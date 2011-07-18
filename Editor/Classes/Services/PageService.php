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
	
	function getBlueprintsByTemplate($template) {
		$sql = "select object_id as id from pageblueprint,template where pageblueprint.template_id = template.`id` and template.`unique`=".Database::text($template);
		$ids = Database::getIds($sql);
		if (count($ids)>0) {
			return Query::after('pageblueprint')->withIds($ids)->orderBy('title')->get();
		}
		return array();
	}
	
	function getPageTranslationList($id) {
		$sql="select page_translation.id,page.title,page.language from page,page_translation where page.id=page_translation.translation_id and page_translation.page_id=".Database::int($id);
		return Database::selectAll($sql);
	}
	
	function addPageTranslation($page,$translation) {
		$sql = "insert into page_translation (page_id,translation_id) values (".Database::int($page).",".Database::int($translation).")";
		Database::insert($sql);
		PageService::markChanged($page);
	}
	
	function removePageTranslation($id) {
		$sql = "select * from page_translation where id=".Database::int($id);
		if ($row = Database::selectFirst($sql)) {
			$sql = "delete from page_translation where id=".Database::int($id);
			Database::delete($sql);
			PageService::markChanged($row['page_id']);
		}
	}
	
	function markChanged($id) {
	    $sql = "update page set changed=now() where id=".Database::int($id);
		Database::update($sql);
	}
	
	function search($query) {
		$countSql ="select count(page.id) as total";

		$sql = "select page.id,page.secure,page.path,page.title,template.unique,
			date_format(page.changed,'%d/%m-%Y') as changed,
			date_format(page.changed,'%Y%m%d%h%i%s') as changedindex,
			(page.changed-page.published) as publishdelta,page.language";

		$sqlLimits = " from page,template";
		$sqlLimits .= " where page.template_id=template.id ";
		$text = $query->getText();
		if (StringUtils::isNotBlank($text)) {
			$sqlLimits.=" and (page.title like ".Database::search($text).
				" or page.`index` like ".Database::search($text).
				" or page.description like ".Database::search($text).
				" or page.keywords like ".Database::search($text).")";
		}
		$ordering = $query->getOrdering();
		for ($i=0; $i < count($ordering); $i++) { 
			if ($i==0) {
				$sqlLimits.=" order by ".$ordering[$i];
			} else {
				$sqlLimits.=",".$ordering[$i];
			}
		}
		if (count($ordering)>0) {
			$sqlLimits.= $query->getDirection()=='ascending' ? ' asc' : ' desc';
		}

		$windowPage = $query->getWindowPage();
		$windowSize = $query->getWindowSize();

		$listSql = $sql.$sqlLimits." limit ".($windowPage*$windowSize).",".(($windowPage+1)*$windowSize);
		
		$count = Database::selectFirst($countSql.$sqlLimits);
		$list = Database::selectAll($listSql);
		$result = new SearchResult();
		
		$result->setTotal(intval($count['total']));
		$result->setList($list);
		
		return $result;
		
	}
}