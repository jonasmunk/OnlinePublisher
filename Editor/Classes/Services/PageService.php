<?
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
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
	
	function isChanged($id) {
		$sql="select changed-published as delta from page where id=".Database::int($id);
		$row = Database::selectFirst($sql);
		if ($row['delta']>0) {
			return true;
		}
		return false;
	}
	
	function updateSecureStateOfAllPages() {
		$sql = "update page set secure=1";
		Database::update($sql);
		$sql = "update page left join securityzone_page on page.id=securityzone_page.page_id set page.secure=0 where securityzone_page.securityzone_id is null";
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
	
	function validate($page) {
		if (!$page) {
			return false;
		}
		if (StringUtils::isBlank($page->getTitle())) {
			return false;
		}
		if (!$page->getTemplateId()) {
			return false;
		}
		$sql = "select id from template where id=".Database::int($page->getTemplateId());
		if (Database::isEmpty($sql)) {
			return false;
		}
		$sql = "select id from frame where id=".Database::int($page->getFrameId());
		if (Database::isEmpty($sql)) {
			return false;
		}
		$design = Design::load($page->getDesignId());
		if (!$design) {
			return false;
		}
		return true;
	}
	
	function create($page) {
		if (!PageService::validate($page)) {
			return false;
		}
		
		$sql="insert into page (title,description,keywords,path,template_id,created,changed,published,frame_id,design_id,language,next_page,previous_page) values (".
		Database::text($page->getTitle()).
		",".Database::text($page->getDescription()).
		",".Database::text($page->getKeywords()).
		",".Database::text($page->getPath()).
		",".Database::int($page->getTemplateId()).
		",now()".
		",now()".
		",now()".
		",".Database::int($page->getFrameId()).
		",".Database::int($page->getDesignId()).
		",".Database::text($page->getLanguage()).
		",".Database::int($page->getNextPage()).
		",".Database::int($page->getPreviousPage()).
		")";
		$page->id=Database::insert($sql);
		PageService::_createTemplate($page);
		return true;
	}

	function _createTemplate($page) {
		$template = TemplateService::getTemplateById($page->getTemplateId());
		$page->templateUnique = $template->getUnique();
		$controller = TemplateService::getLegacyTemplateController($page);
		if ($controller && method_exists($controller,'create')) {
			$controller->create($page);
		}
	}
	
	function delete($page) {

		$controller = TemplateService::getLegacyTemplateController($page);
		if ($controller && method_exists($controller,'delete')) {
			$controller->delete();
		}
		
		$id = $page->getId();

		// Delete the page
		$sql="delete from page where id=".Database::int($id);
		Database::delete($sql);

		// Delete links
		$sql="delete from link where page_id=".Database::int($id);
		Database::delete($sql);

		// Delete translations
		$sql="delete from page_translation where page_id=".Database::int($id)." or translation_id=".Database::int($id);
		Database::delete($sql);

		// Delete security zone relations
		$sql="delete from securityzone_page where page_id=".Database::int($id);
		Database::delete($sql);

		EventManager::fireEvent('delete','page',$page->getTemplateUnique(),$id);
	}

    function load($id) {
		$sql = "select page.*,UNIX_TIMESTAMP(page.changed) as changed_unix,UNIX_TIMESTAMP(page.published) as published_unix,template.unique".
		" from page,template where template.id=page.template_id and page.id=".Database::int($id);
		if ($row = Database::selectFirst($sql)) {
			$output = new Page();
			$output->setId($row['id']);
			$output->setName($row['name']);
			$output->setPath($row['path']);
			$output->setTemplateId($row['template_id']);
			$output->templateUnique = $row['unique'];
			$output->setDesignId(intval($row['design_id']));
			$output->setFrameId(intval($row['frame_id']));
			$output->setTitle($row['title']);
			$output->setDescription($row['description']);
			$output->setKeywords($row['keywords']);
			$output->setLanguage($row['language']);
			$output->setData($row['data']);
			$output->setSearchable($row['searchable']==1);
			$output->setDisabled($row['disabled']==1);
			$output->setNextPage($row['next_page']);
			$output->setPreviousPage($row['previous_page']);
			$output->changed=intval($row['changed_unix']);
			$output->published=intval($row['published_unix']);
			return $output;
		}
		return null;
	}

	function save($page) {
		if ($page->getId()>0) {
			$sql="update page set".
			" title=".Database::text($page->getTitle()).
			",description=".Database::text($page->getDescription()).
			",path=".Database::text($page->getPath()).
			",keywords=".Database::text($page->getKeywords()).
			",language=".Database::text($page->getLanguage()).
			",searchable=".Database::boolean($page->getSearchable()).
			",disabled=".Database::boolean($page->getDisabled()).
			",design_id=".Database::int($page->getDesignId()).
			",frame_id=".Database::int($page->getFrameId()).
			" where id=".Database::int($page->getId());
			return Database::update($sql);
		} else {
			return PageService::create($page);
		}
	}

	
	function reconstruct($pageId,$historyId) {		
		$page = PageService::load($pageId);
		
		if ($controller = TemplateService::getController($page->getTemplateUnique())) {
			$sql = "select data from page_history where id=".Database::int($historyId);
			if ($row = Database::selectFirst($sql)) {
				if ($doc = DOMUtils::parse($row['data'])) {
					$controller->import($page->getId(),$doc);
				}
			}
		}
	}
}