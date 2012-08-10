<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Modules.Links
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
class LinkService {
	
	static $types = array(
		'url' => array('da' => 'Internet adresse', 'en' => 'Internet address'),
		'page' => array('da' => 'Side', 'en' => 'Page'),
		'file' => array('da' => 'Fil', 'en' => 'File'),
		'email' => array('da' => 'E-mail-adresse', 'en' => 'E-mail address')
	);
	
	function getLinkInfo($linkId) {
		$sql = "select link.*,page.title as page_title,object.title as object_title from link left join page on link.target_id=page.id left join object on link.target_id=object.id where link.id=".Database::int($linkId);
		if ($row = Database::selectFirst($sql)) {
			return LinkService::_rowToInfo($row);
		}
		return null;
	}

	function getPageLinks($pageId) {
		$list = array();
		$sql = "select link.*,page.title as page_title,object.title as object_title from link left join page on link.target_id=page.id left join object on link.target_id=object.id where page_id=".Database::int($pageId)." order by link.source_text";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$link = LinkService::_rowToInfo($row);
			$list[] = $link;
		}
		Database::free($result);
		return $list;
	}
	
	function _rowToInfo($row) {
		$link = new LinkInfo();
		$link->setId(intval($row['id']));
		$link->setSourceType($row['source_type']);
		$link->setSourceText($row['source_text']);
		$link->setTargetType($row['target_type']);
		$link->setTargetValue($row['target_value']);
		$link->setTargetId(intval($row['target_id']));
		$link->setPartId(intval($row['part_id']));
		if ($row['target_type']=='page') {
			$link->setTargetTitle($row['page_title']);
			$link->setTargetIcon('common/page');
		} else if ($row['target_type']=='file') {
			$link->setTargetTitle($row['object_title']);
			$link->setTargetIcon('file/generic');
		} else if ($row['target_type']=='email') {
			$link->setTargetTitle($row['target_value']);
			$link->setTargetIcon('common/email');
		} else {
			$link->setTargetTitle($row['target_value']);
			$link->setTargetIcon('common/internet');
		}
		return $link;
	}
	
	function translateLinkType($type) {
		return GuiUtils::getTranslated(LinkService::$types[$type]);
	}
	
	function load($id) {
		$sql = "select * from link where id=".Database::int($id);
        if ($row = Database::selectFirst($sql)) {
			$link = new Link();
			$link->setId(intval($id));
			$link->setText($row['source_text']);
			$link->setAlternative($row['alternative']);
			$link->targetType=$row['target_type'];
			$link->targetValue=$row['target_value'];
			$link->targetId=intVal($row['target_id']);
			$link->partId=intVal($row['part_id']);
			$link->pageId=intVal($row['page_id']);
			return $link;
		}
		return null;
	}

	function remove($link) {
		$sql="delete from link where id=".Database::int($this->getId());
		return Database::delete($sql);
	}

	function save($link) {
		if (StringUtils::isBlank($link->getText())) {
			return;
		}
		if ($link->id) {
			$sql="update link set ".
			"part_id=".Database::int($link->partId).
			",page_id=".Database::int($link->pageId).
			",source_text=".Database::text($link->text).
			",target_type=".Database::text($link->targetType).
			",target_value=".Database::text($link->targetValue).
			",target_id=".Database::int($link->targetId).
			",target=".Database::text($link->target).
			",alternative=".Database::text($link->alternative).
			" where id=".Database::int($link->id);
			Database::update($sql);
		} else {
			$sql="insert into link (page_id,part_id,source_type,source_text,target_type,target_value,target_id,alternative
				) values (".
				Database::int($link->pageId).
				",".Database::int($link->partId).
				",'text',".
				Database::text($link->text).",".
				Database::text($link->targetType).",".
				Database::text($link->targetValue).",".
				Database::int($link->targetId).",".
				Database::text($link->alternative).
			")";
			$this->id = Database::insert($sql);
		}
	}
	
	function getSourceIcon($view) {
		$icons = array(
			'hierarchy' => 'monochrome/hierarchy',
			'file' => 'monochrome/file',
			'url' => 'monochrome/globe',
			'email' => 'monochrome/email',
			'page' => 'common/page',
			'news' => 'common/news'
		);
		return $icons[$view->getSourceType()];
	}

	function getTargetIcon($view) {
		$icons = array(
			'hierarchy' => 'monochrome/hierarchy',
			'file' => 'monochrome/file',
			'url' => 'monochrome/globe',
			'email' => 'monochrome/email',
			'page' => 'common/page',
			'news' => 'common/news'
		);
		return $icons[$view->getTargetType()];
	}
	
	function search($query) {
		$unions = array();
		$pageId = $query->getSourcePage();
		if (!$pageId) {
			$unions[] = "select 
				object_link.id as id,
				
				object.type as source_type,
				object_link.object_id as source_id,
				object.title as source_title,
				'' as source_sub_type,
				NULL as source_sub_id,
				object_link.title as source_text,
				object_link.alternative as source_description,

				object_link.target_type,
				object_link.target_value,

				page.id as target_page_id,
				page.title as target_page_title,
				file.id as target_file_id,
				file.title as target_file_title 

				from object_link 
				left join page on object_link.target_value=page.id and object_link.target_type='page'
				left join object as file on object_link.target_value=file.id and object_link.target_type='file'
				,object where object_link.object_id = object.id";
		}
		$unions[] = "select 

			link.id as id,
			
			'page' as source_type,
			page.id as source_id,
			page.title as source_title,
			link.source_type as source_sub_type,
			link.part_id as source_sub_id,

			link.source_text as source_text,
			link.alternative as source_description,

			target_type as target_type,
			link.target_value,

			target_page.id as target_page_id,
			target_page.title as target_page_title,
			file.id as target_file_id,
			file.title as target_file_title

			 from link
			 left join page as target_page on link.target_id=target_page.id and link.target_type='page'
			 left join object as file on link.target_id=file.id and link.target_type='file'
			 , page where link.page_id = page.id".($pageId ? " and page.id=".Database::int($pageId) : "");
		
		$unions[] = "select
			part_link.id as id,
			
			'page' as source_type,
			page.id as source_id,
			page.title as source_title,
			part_link.source_type as source_sub_type,
			part_link.part_id as source_sub_id,

			source_text,
			part_link.alternative as source_description,

			target_type as target_type,
			part_link.target_value,

			target_page.id as target_page_id,
			target_page.title as target_page_title,
			file.id as target_file_id,
			file.title as target_file_title

			 from part_link
			
			left join page as target_page on part_link.target_value=target_page.id and part_link.target_type='page'
			left join object as file on part_link.target_value=file.id and part_link.target_type='file'
			,part,page,document_section where part_link.part_id = part.id and part.id=document_section.part_id and page.id=document_section.page_id
 			and target_type!='sameimage'".($pageId ? " and page.id=".Database::int($pageId) : "");
		if (!$pageId) {
		$unions[] = "select
			hierarchy_item.id as id,
			
			'hierarchy' as source_type,
			hierarchy.id as source_id,
			hierarchy.name as source_title,
			null as source_sub_type,
			null as source_sub_id,

			hierarchy_item.title as source_text,
 			hierarchy_item.alternative as source_description,

			target_type as target_type,
			hierarchy_item.target_value,

			target_page.id as target_page_id,
			target_page.title as target_page_title,
			file.id as target_file_id,
			file.title as target_file_title

			 from hierarchy_item
			left join page as target_page on hierarchy_item.target_id=target_page.id and (hierarchy_item.target_type='page' or hierarchy_item.target_type='pageref')
			left join object as file on hierarchy_item.target_id=file.id and hierarchy_item.target_type='file'
			,hierarchy where hierarchy_item.`hierarchy_id`=hierarchy.id";
		}
		$sql = join(' union ',$unions);
		$list = array();
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			if (!$query->getTargetType() || $query->getTargetType()==$row['target_type']) {
				if (!$query->getSourceType() || $query->getSourceType()==$row['source_type']) {
					$view = LinkService::_buildView($row);
					if ($query->getTextCheck()) {
						LinkService::_checkText($view);
					}
					if (!$query->getOnlyWarnings() || ($query->getOnlyWarnings() && count($view->getErrors())>0)) {
						$list[] = $view;
					}
				}
			}
		}
		Database::free($result);
		return $list;
	}
	
	function _checkText($view) {
		if ($view->getSourceType()=='page' && $view->getSourceSubType()=='text') {
			$text = '';
			if ($view->getSourceSubId()) {
				$text = PartService::getLinkText($view->getSourceSubId());
			} else {
				$text = PageService::getLinkText($view->getSourceId());
			}
			$found = strpos($text,$view->getSourceText())!==false;
			if (!$found) {
				$view->addError(LinkView::$TEXT_NOT_FOUND,array('The link text was not found','da'=>'Link-teksten kunne ikke findes'));
			}
		}
	}
	
	function _buildView($row) {
		$view = new LinkView();
		$view->setId(intval($row['id']));
		$view->setSourceType($row['source_type']);
		$view->setSourceId(intval($row['source_id']));
		if ($row['source_sub_id']) {
			$view->setSourceSubId(intval($row['source_sub_id']));
		}
		$view->setSourceSubType($row['source_sub_type']);
		$view->setSourceTitle($row['source_title']);
		$view->setSourceText($row['source_text']);
		$view->setSourceDescription($row['source_description']);
		if ($row['source_sub_type']=='entireimage') {
			$view->setSourceText('*billede*');
		}
		$view->setTargetType($row['target_type']);
		if ($row['target_type']=='pageref') {
			$view->setTargetType('page');
			if (!$row['target_page_id']) {
				$view->addError(LinkView::$TARGET_NOT_FOUND,array('The target page does not exist','da'=>'Siden findes ikke'));
				$view->setTargetId(-1);
				$view->setTargetTitle('?');
			} else {
				$view->setTargetId(intval($row['target_page_id']));
				$view->setTargetTitle($row['target_page_title']);
			}
			
		} else if ($row['target_type']=='page') {
			if (!$row['target_page_id']) {
				$view->addError(LinkView::$TARGET_NOT_FOUND,array('The target page does not exist','da'=>'Siden findes ikke'));
				$view->setTargetId(-1);
				$view->setTargetTitle('?');
			} else {
				$view->setTargetId(intval($row['target_page_id']));
				$view->setTargetTitle($row['target_page_title']);
			}
		}
		else if ($row['target_type']=='file') {
			if (!$row['target_file_id']) {
				$view->addError(LinkView::$TARGET_NOT_FOUND,array('The target file does not exist','da'=>'Filen findes ikke'));
				$view->setTargetId(-1);
				$view->setTargetTitle('?');
			} else {
				$view->setTargetId(intval($row['target_file_id']));
				$view->setTargetTitle($row['target_file_title']);
			}
		} else if ($row['target_type']=='email') {
			$view->setTargetId($row['target_value']);
			$view->setTargetTitle($row['target_value']);
			if (!ValidateUtils::validateEmail($row['target_value'])) {
				$view->addError(LinkView::$INVALID_ADDRESS,array('The e-mail is invalid','da'=>'E-post-adressen er ikke valid'));
			}
		} else if ($row['target_type']=='url') {
			$view->setTargetId($row['target_value']);
			$view->setTargetTitle($row['target_value']);
			if (!ValidateUtils::validateHref($row['target_value'])) {
				$view->addError(LinkView::$INVALID_ADDRESS,array('The address is invalid','da'=>'Adressen er ikke valid'));
			}
		}
		return $view;
	}
}