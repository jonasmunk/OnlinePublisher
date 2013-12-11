<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class PublishingService {
	
	static function publishPage($id) {
		
		$result = PublishingService::buildPage($id);
		if (!$result) {
			return;
		}
		
		$sql="update page set".
			" data=".Database::text($result['data']).
			",`index`=".Database::text($result['index']).
			",dynamic=".Database::boolean($result['dynamic']).
			",published=now()".
			" where id=".Database::int($id);
		Database::update($sql);
		$sql="insert into page_history (page_id,user_id,data,time) values (".$id.",".InternalSession::getUserId().",".Database::text($result['data']).",now())";
		Database::insert($sql);
		
		// Clear page cache
		CacheService::clearPageCache($id);
	}
	
	static function reIndexPage($id) {
		
		$result = PublishingService::buildPage($id);
		if (!$result) {
			return;
		}
		
		$sql="update page set `index`=".Database::text($result['index'])." where id=".Database::int($id);
		Database::update($sql);
	}
	
	static function buildPage($id) {
		$sql="select template.unique from page,template where page.template_id=template.id and page.id=".Database::int($id);
		if ($row = Database::selectFirst($sql)) {
			if ($controller = TemplateService::getController($row['unique'])) {
				if (method_exists($controller,'build')) {
			        $result = $controller->build($id);
			        return $result;
				}	
			}
		}
		return null;
	}

	static function publishAll() {

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
			if ($object) {
				$object->publish();
			}
		}
		
	}
	
	static function getTotalUnpublishedCount() {
		$count = 0;
		$sql = "select count(page.id) as `count`,'page' from page where page.changed>page.published
			union
			select count(hierarchy.id) as `count`,'hierarchy' from hierarchy where changed>published
			union
			select count(object.id) as `count`,'object' from object where updated>published";
		$rows = Database::selectAll($sql);
		foreach ($rows as $row) {
			$count+=intval($row['count']);
		}
		return $count;
	}
	
	static function getUnpublishedPages() {
		$sql="select page.id,page.title,template.unique as template from page,template where page.template_id=template.id and changed>published";
		return Database::selectAll($sql);
	}
	
	static function getUnpublishedHierarchies() {
		$sql="select id,name from hierarchy where changed>published";
		return Database::selectAll($sql);
	}
	
	static function getUnpublishedObjects() {
		$result = array();
		$sql = "select id from object where updated>published";
		$ids = Database::getIds($sql);
		$notFound = array();
		foreach ($ids as $id) {
			if ($object = Object::load($id)) {
				$result[] = $object;
			} else {
				$notFound[] = $id;
				Log::debug('Unable to load object: '.$id);
			}
			
		}
		if ($notFound) {
			Log::debug('Not found:'.join($notFound,','));
		}
		return $result;
	}
	
	static function publishFrame($id) {
		$sql="select * from frame where id=".$id;
		$row = Database::selectFirst($sql);
		$data='';
		$dynamic=0;
		if ($row['searchenabled']) {
			$data.='<search page="'.$row['searchpage_id'].'">'.
			'<button title="'.Strings::escapeEncodedXML($row['searchbuttontitle']).'"/>'.
			'<types>'.
			($row['searchpages'] ? '<type unique="page"/>' : '').
			($row['searchimages'] ? '<type unique="image"/>' : '').
			($row['searchfiles'] ? '<type unique="file"/>' : '').
			($row['searchnews'] ? '<type unique="news"/>' : '').
			($row['searchpersons'] ? '<type unique="person"/>' : '').
			($row['searchproducts'] ? '<type unique="product"/>' : '').
			'</types>'.
			'</search>';
		}
		if ($row['userstatusenabled']) {
			$data.='<userstatus page="'.$row['userstatuspage_id'].'"/>';
		}
		$data.=
		'<text>'.
		'<bottom>'.Strings::insertEmailLinks(Strings::escapeEncodedXML($row['bottomtext']),'link','email','').'</bottom>'.
		'</text>'.
		'<links>'.
		'<top>'.
		PublishingService::buildFrameLinks($id,'top').
		'</top>'.
		'<bottom>'.
		PublishingService::buildFrameLinks($id,'bottom').
		'</bottom>'.
		'</links>';
		$news=PublishingService::buildFrameNews($id);
		$data.=$news;
		if (strlen($news)>0) {
			$dynamic=1;
		}
		$sql="update frame set data=".Database::text($data).",published=now(),dynamic=".$dynamic." where id=".$id;
		Database::update($sql);
	
		// Clear page cache
		CacheService::clearPageCache($id);
	}
	
	static function buildFrameLinks($id,$position) {
		$out='';
		$sql="select * from frame_link where position='".$position."' and frame_id=".$id." order by `index`";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$out.='<link title="'.Strings::escapeEncodedXML($row['title']).'" alternative="'.Strings::escapeEncodedXML($row['alternative']).'"';
			if ($row['target_type']=='page') {
				$out.=' page="'.$row['target_id'].'"';
			}
			else if ($row['target_type']=='file') {
				$out.=' file="'.$row['target_id'].'" filename="'.Strings::escapeEncodedXML(FileService::getFileFilename($row['target_id'])).'"';
			}
			else if ($row['target_type']=='url') {
				$out.=' url="'.Strings::escapeEncodedXML($row['target_value']).'"';
			}
			else if ($row['target_type']=='email') {
				$out.=' email="'.Strings::escapeEncodedXML($row['target_value']).'"';
			}
			$out.='/>';
		}
		Database::free($result);
		return $out;
	}

	static function buildFrameNews($id) {
		$out='';
		$sql="select * from frame_newsblock where frame_id=".$id." order by `index`";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$out.='<newsblock title="'.Strings::escapeEncodedXML($row['title']).'">'.
			'<!--newsblock#'.$row['id'].'-->'.
			'</newsblock>';
		}
		Database::free($result);
		return $out;
	}
}