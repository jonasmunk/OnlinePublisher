<?php
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class PageService {
	
	static function createPageHistory($id,$data) {
		$sql="insert into page_history (page_id,user_id,data,time) values (".$id.",".InternalSession::getUserId().",".Database::text($data).",now())";
		Database::insert($sql);
	}
	
	static function exists($id) {
		return !Database::isEmpty("SELECT id from page where id=".Database::int($id));
	}

	static function getLatestPageId() {
		$sql="SELECT id from page order by changed desc limit 1";
		$row = Database::selectFirst($sql);
		if ($row) {
			return intval($row['id']);
		}
		return null;
	}
	
	static function getLanguage($id) {
		$sql = "SELECT language from page where id=".Database::int($id);
		if ($row = Database::selectFirst($sql)) {
			return strtolower($row['language']);
		}
		return null;
	}
	
	static function getPath($id) {
		$sql = "SELECT path from page where id=@int(id)";
		if ($row = Database::selectFirst($sql,['id'=>$id])) {
			return trim($row['path']);
		}
		return null;
	}
	
	static function getTotalPageCount() {
		$sql = "SELECT count(id) as count from page";
		$row = Database::selectFirst($sql);
		return intval($row['count']);
	}
	
	static function getChangedPageCount() {
		$sql = "SELECT count(id) as count from page where page.changed>page.published";
		$row = Database::selectFirst($sql);
		return intval($row['count']);
	}
	
	static function getLatestPageCount() {
		$sql = "SELECT count(id) as count from page	where page.changed>".Database::datetime(Dates::addDays(time(),-1));
		$row = Database::selectFirst($sql);
		return intval($row['count']);
	}
	
	static function getNewsPageCount() {
		$sql = "SELECT count(page.id) as total from page,template,news, object_link where page.template_id=template.id  and object_link.object_id=news.object_id and object_link.target_value=page.ID and object_link.target_type='page'";
		$row = Database::selectFirst($sql);
		return intval($row['total']);
	}
	
	static function getWarningsPageCount() {
		$sql = "SELECT count(page.id) as total from page,template where page.template_id=template.id  and (page.changed>page.published or page.path is null or page.path='')";
		$row = Database::selectFirst($sql);
		return intval($row['total']);
	}
	
	static function getNoItemPageCount() {
		$sql = "SELECT count(page.id) as total from page,template where page.template_id=template.id  and page.id not in (select target_id from `hierarchy_item` where `target_type`='page')";
		$row = Database::selectFirst($sql);
		return intval($row['total']);
	}
	
	static function getReviewPageCount() {
		$sql = "SELECT count(page.id) as total
			from page,relation as page_review,relation as review_user,review,object as user 
			where page_review.from_type='page' and page_review.from_object_id=page.id
			and page_review.to_type='object' and page_review.to_object_id=review.object_id
			and review_user.from_type='object' and review_user.from_object_id=review.object_id
			and review_user.to_type='object' and review_user.to_object_id=user.id
			and review.accepted = 0";
		$row = Database::selectFirst($sql);
		return intval($row['total']);
	}

	
	static function getLanguageCounts() {
		$sql="SELECT language,count(id) as count from page group by language order by language";
		return Database::selectAll($sql);
	}
	
	static function getPagePreview($id,$template) {
		$data = '';
		if ($controller = TemplateService::getController($template)) {
			if (method_exists($controller,'build')) {
				$result = $controller->build($id);
	        	return $result['data'];
			}
		}
		return $data;
	}
	
	static function saveSnapshot($id) {
		$page = Page::load($id);
		if ($page) {
			$template = $page->getTemplateUnique();
			$data = PageService::getPagePreview($id,$template);
			PageService::createPageHistory($id,$data);
		}
	}
	
	static function getBlueprintsByTemplate($template) {
		$sql = "SELECT object_id as id from pageblueprint,template where pageblueprint.template_id = template.`id` and template.`unique`=".Database::text($template);
		$ids = Database::getIds($sql);
		if (count($ids)>0) {
			return Query::after('pageblueprint')->withIds($ids)->orderBy('title')->get();
		}
		return array();
	}
	
	static function getPageTranslationList($id) {
		$sql="SELECT page_translation.id,page.title,page.language from page,page_translation where page.id=page_translation.translation_id and page_translation.page_id=".Database::int($id);
		return Database::selectAll($sql);
	}
	
	static function addPageTranslation($page,$translation) {
		$sql = "INSERT into page_translation (page_id,translation_id) values (".Database::int($page).",".Database::int($translation).")";
		Database::insert($sql);
		PageService::markChanged($page);
	}
	
	static function removePageTranslation($id) {
		$sql = "SELECT * from page_translation where id=".Database::int($id);
		if ($row = Database::selectFirst($sql)) {
			$sql = "delete from page_translation where id=".Database::int($id);
			Database::delete($sql);
			PageService::markChanged($row['page_id']);
		}
	}
	
	static function markChanged($id) {
	    $sql = "UPDATE page set changed=now() where id=".Database::int($id);
		Database::update($sql);
	}
	
	static function isChanged($id) {
		$sql="SELECT changed-published as delta from page where id=".Database::int($id);
		$row = Database::selectFirst($sql);
		if ($row['delta']>0) {
			return true;
		}
		return false;
	}
	
	static function getIndex($pageId) {
		$sql = "SELECT `index` from page where id=".Database::int($pageId);
		$row = Database::selectFirst($sql);
		if ($row) {
			return $row['index'];
		}
		return null;
	}

	static function getLinkText($pageId) {
		$text = '';
		$sql = "SELECT text,document_section.page_id from part_text,document_section where document_section.part_id=part_text.part_id and page_id=".Database::int($pageId)."
			union select text,document_section.page_id from part_header,document_section where document_section.part_id=part_header.part_id and page_id=".Database::int($pageId)."
			union select text,document_section.page_id from part_listing,document_section where document_section.part_id=part_listing.part_id and page_id=".Database::int($pageId)."
			union select html as text,document_section.page_id from part_table,document_section where document_section.part_id=part_table.part_id and page_id=".Database::int($pageId);
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$text.=' '.$row['text'];
		}
		Database::free($result);
		return $text;
	}
	
	static function updateSecureStateOfAllPages() {
		$sql = "UPDATE page set secure=1";
		Database::update($sql);
		$sql = "UPDATE page left join securityzone_page on page.id=securityzone_page.page_id set page.secure=0 where securityzone_page.securityzone_id is null";
		Database::update($sql);
	}
    
    static function addPageToSecurityZone($pageId,$zoneId) {
        if (!Securityzone::load($zoneId)) {
            Log::warn('Zone not found: ' . $zoneId);
            return;
        }
        if (!PageService::exists($pageId)) {
            Log::warn('Page not found: ' . $pageId);
            return;
        }
        $parameters = ['pageId'=>$pageId,'zoneId'=>$zoneId]; 
        $sql = "DELETE from securityzone_page where page_id=@int(pageId) and securityzone_id=@int(zoneId)";
        Database::delete($sql,$parameters);
        $sql = "INSERT into securityzone_page (page_id,securityzone_id) values (@int(pageId),@int(zoneId))";
        Database::insert($sql,$parameters);
        PageService::updateSecureStateOfAllPages();
    }
    
    static function removePageFromSecurityZone($pageId,$zoneId) {
        if (!Securityzone::load($zoneId)) {
            Log::warn('Zone not found: ' . $zoneId);
            return;
        }
        if (!PageService::exists($pageId)) {
            Log::warn('Page not found: ' . $pageId);
            return;
        }
        $parameters = ['pageId'=>$pageId,'zoneId'=>$zoneId]; 
        $sql = "DELETE from securityzone_page where page_id=@int(pageId) and securityzone_id=@int(zoneId)";
        Database::delete($sql,$parameters);
        PageService::updateSecureStateOfAllPages();
    }
    
    static function addUserToSecurityZone($userId,$zoneId) {
        if (!User::load($userId)) {
            Log::warn('User not found: ' . $userId);
            return;
        }
        if (!Securityzone::load($zoneId)) {
            Log::warn('Zone not found: ' . $zoneId);
            return;
        }
        $parameters = ['userId'=>$userId,'zoneId'=>$zoneId]; 
        $sql = "DELETE from securityzone_user where user_id=@int(userId) and securityzone_id=@int(zoneId)";
        Database::delete($sql,$parameters);
        $sql = "INSERT into securityzone_user (user_id,securityzone_id) values (@int(userId),@int(zoneId))";
        Database::insert($sql,$parameters);
    }
    
    static function removeUserFromSecurityZone($userId,$zoneId) {
        if (!User::load($userId)) {
            Log::warn('User not found: ' . $userId);
            return;
        }
        if (!Securityzone::load($zoneId)) {
            Log::warn('Zone not found: ' . $zoneId);
            return;
        }
        $parameters = ['userId'=>$userId,'zoneId'=>$zoneId]; 
        $sql = "DELETE from securityzone_user where user_id=@int(userId) and securityzone_id=@int(zoneId)";
        Database::delete($sql,$parameters);
    }
	
	static function search($query) {

		$select = new SelectBuilder();
		
		$select->addTable('page')->addTable('template');
		
		$select->addColumns(array(
			"page.id",
			"page.secure",
			"page.path",
			"page.title",
			"template.unique",
			"date_format(page.changed,'%d/%m-%Y') as changed",
			"date_format(page.changed,'%Y%m%d%h%i%s') as changedindex",
			"(page.changed-page.published) as publishdelta",
			"page.language"
		));
			
		$select->addLimit("page.template_id=template.id");
		
		// Free text search...
		$text = $query->getText();
		if (Strings::isNotBlank($text)) {
			$select->addLimit(
				"(page.title like ".Database::search($text).
				" or page.`index` like ".Database::search($text).
				" or page.description like ".Database::search($text).
				" or page.keywords like ".Database::search($text).")"
			);
		}
		// Relations...
		if (count($query->getRelationsFrom())>0) {
			$relations = $query->getRelationsFrom();
			for ($i=0; $i < count($relations); $i++) { 
				$relation = $relations[$i];
				$select->addTable('relation as relation_from_'.$i);
				$select->addLimit('relation_from_'.$i.'.to_object_id=page.id');
				$select->addLimit("relation_from_".$i.".to_type='page'");
				$select->addLimit("relation_from_".$i.".from_type=".Database::text($relation['fromType']));
				$select->addLimit('relation_from_'.$i.'.from_object_id='.Database::int($relation['id']));
				if ($relation['kind']) {
					$select->addLimit("relation_from_".$i.".kind=".Database::text($relation['kind']));
				}
			}
		}
		$ordering = $query->getOrdering();
		for ($i=0; $i < count($ordering); $i++) { 
			$select->addOrdering($ordering,$query->getDirection()=='descending');
		}

		$windowPage = $query->getWindowPage();
		$windowSize = $query->getWindowSize();

		$select->setFrom($windowPage*$windowSize)->setTo(($windowPage+1)*$windowSize);
		
		$result = new SearchResult();

		$list = Database::selectAll($select->toSQL());
		$result->setList($list);
		
		$select->clearFromAndTo()->clearColumns();
		$select->addColumn("count(page.id) as total");
		
		$count = Database::selectFirst($select->toSQL());
		$result->setTotal(intval($count['total']));
		
		return $result;
	}
	
	static function validate($page) {
		if (!$page) {
			return false;
		}
		if (Strings::isBlank($page->getTitle())) {
			return false;
		}
		if (!$page->getTemplateId()) {
			return false;
		}
		$sql = "SELECT id from template where id=".Database::int($page->getTemplateId());
		if (Database::isEmpty($sql)) {
			return false;
		}
		$sql = "SELECT id from frame where id=".Database::int($page->getFrameId());
		if (Database::isEmpty($sql)) {
			return false;
		}
		$design = Design::load($page->getDesignId());
		if (!$design) {
			return false;
		}
		return true;
	}
	
	static function create($page) {
		if (!PageService::validate($page)) {
			return false;
		}
		
		$sql="INSERT into page (title,description,keywords,path,template_id,created,changed,published,frame_id,design_id,language,next_page,previous_page) values (".
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

	static function _createTemplate($page) {
		$template = TemplateService::getTemplateById($page->getTemplateId());
		$page->templateUnique = $template->getUnique();
		if ($controller = TemplateService::getController($page->getTemplateUnique())) {
			if (method_exists($controller,'create')) {
				$controller->create($page);
			}
		}
	}
	
	static function delete($page) {
		if ($controller = TemplateService::getController($page->getTemplateUnique())) {
			if (method_exists($controller,'delete')) {
				$controller->delete($page);
			}	
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

		EventService::fireEvent('delete','page',$page->getTemplateUnique(),$id);
	}

    static function load($id) {
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

	static function save($page) {
		$success = false;
		if ($page->getId()>0) {
			$existing = PageService::load($page->getId());
			
			if ($existing && Strings::isNotBlank($existing->getPath())) {
				if ($page->getPath()!==$existing->getPath()) {
					Log::debug('The path has changed!');
					$path = Query::after('path')->withProperty('path',$existing->getPath())->first();
					if ($path) {
						Log::debug('Found existing path...');
						Log::debug($path);
						Log::debug('Point it at the new page...');
						$path->setPageId($page->getId());
					} else {
						Log::debug('Creating new path...');
						$path = new Path();
						$path->setPath($existing->getPath());
						$path->setPageId($page->getId());
					}
					$path->save();
					$path->publish();
				}
			}
			
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
			$success = Database::update($sql);
			
			if ($success) {
				PageService::markChanged($page->getId());
				EventService::fireEvent('update','page',$page->getTemplateUnique(),$page->getId());
			}			
		} else {
			$success = PageService::create($page);
		}
		if ($success) {
			if (Strings::isNotBlank($page->getPath())) {
				$paths = Query::after('path')->withProperty('path',$page->getPath())->get();
				foreach ($paths as $path) {
					$path->remove();
				}
			}
		}
	}

	
	static function reconstruct($pageId,$historyId) {		
		$page = PageService::load($pageId);
		if (!$page) {
			Log::debug('Page not found: '.$pageId);
		} else if ($controller = TemplateService::getController($page->getTemplateUnique())) {
			$sql = "select data from page_history where id=".Database::int($historyId);
			if ($row = Database::selectFirst($sql)) {
				if ($doc = DOMUtils::parse(Strings::toUnicode($row['data']))) {
					$controller->import($page->getId(),$doc);
					PageService::markChanged($page->getId());
					return true;
				} else {
					Log::debug('Unable to parse data: '.Strings::shortenString($row['data'],100));
					Log::debug('Valid: '.(DOMUtils::isValid($row['data']) ? 'true' : 'false'));
					
				}
			} else {
				Log::debug('History not found: '.$historyId);
			}
		} else {
			Log::debug('No controller found for...');
			Log::debug($page);
		}
		return false;
	}
	
	/**
	 * Creates a new page using the document template
	 * @param $pageId The ID of the page to create the new page in relation to
	 * @param $title The title of the page
	 * @param $placement If the new page should be placed 'below', 'before' or 'after'
	 */
	static function createPageContextually($pageId,$title,$placement) {
		if (!in_array($placement,['below', 'before', 'after'])) {
	        Log::debug('Unsupported placement');
	        return false;
		}
	    $context_page = Page::load($pageId);
	    if (!$context_page) {
	        Log::debug('No page');
	        return false;
	    }
	    $context_item = HierarchyItem::loadByPageId($context_page->getId());
	    $template = TemplateService::getTemplateByUnique('document');
	    if ($context_item && $template) {
	        $page = new Page();
	        $page->setTitle($title);
	        $page->setTemplateId($template->getId());
	        $page->setDesignId($context_page->getDesignId());
	        $page->setFrameId($context_page->getFrameId());
	        $page->setLanguage($context_page->getLanguage());
	        if ($page->create()) {
	            $hierarchy = Hierarchy::load($context_item->getHierarchyId());
	            if (!$hierarchy) {
	                Log::debug('No hierarchy');
	                return false;
	            }

				$recipe = array(
	        		'title' => $title,
	        		'targetType' => 'page',
	                'hidden' => false,
	        		'targetValue' => $page->getId()
	        	);
				if ($placement=='before') {
					$recipe['parent'] = $context_item->getParent();
					$recipe['index'] = $context_item->getIndex();
				} else if ($placement=='after') {
					$recipe['parent'] = $context_item->getParent();
					$recipe['index'] = $context_item->getIndex() + 1;
				} else { // below
					$recipe['parent'] = $context_item->getId();
				}
	        	$success = $hierarchy->createItem($recipe); // TODO What if this fails
	            return $page;            
	        }
	    }
	    return false;    
	}
}