<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */

require_once($basePath.'Editor/Classes/InternalSession.php');
require_once($basePath.'Editor/Classes/Database.php');
require_once($basePath.'Editor/Classes/LegacyTemplateController.php');

class PublishingService {
	
	function publishPage($id) {
		global $basePath;
		require_once($basePath.'Editor/Classes/Page.php');
		
		$dynamic=false;
		$data='';
		$index='';
		$sql="select template.unique from page,template where page.template_id=template.id and page.id=".$id;
		if ($row = Database::selectFirst($sql)) {
		    if ($controller = LegacyTemplateController::getController($row['unique'],$id)) {
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
		
		// Clear page cache
		CacheService::clearPageCache($id);
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
			if ($object) {
				$object->publish();
			}
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
			if ($object = Object::load($id)) {
				$result[] = $object;
			} else {
				Log::debug('Unable to load object: '.$id);
			}
			
		}
		return $result;
	}
	
	function publishFrame($id) {
		$sql="select * from frame where id=".$id;
		$row = Database::selectFirst($sql);
		$data='';
		$dynamic=0;
		if ($row['searchenabled']) {
			$data.='<search page="'.$row['searchpage_id'].'">'.
			'<button title="'.StringUtils::escapeXML($row['searchbuttontitle']).'"/>'.
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
		'<bottom>'.StringUtils::insertEmailLinks(StringUtils::escapeXML($row['bottomtext']),'link','email','').'</bottom>'.
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
	
	function buildFrameLinks($id,$position) {
		$out='';
		$sql="select * from frame_link where position='".$position."' and frame_id=".$id." order by `index`";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$out.='<link title="'.StringUtils::escapeXML($row['title']).'" alternative="'.StringUtils::escapeXML($row['alternative']).'"';
			if ($row['target_type']=='page') {
				$out.=' page="'.$row['target_id'].'"';
			}
			else if ($row['target_type']=='file') {
				$out.=' file="'.$row['target_id'].'" filename="'.StringUtils::escapeXML(FileService::getFileFilename($row['target_id'])).'"';
			}
			else if ($row['target_type']=='url') {
				$out.=' url="'.StringUtils::escapeXML($row['target_value']).'"';
			}
			else if ($row['target_type']=='email') {
				$out.=' email="'.StringUtils::escapeXML($row['target_value']).'"';
			}
			$out.='/>';
		}
		Database::free($result);
		return $out;
	}

	function buildFrameNews($id) {
		$out='';
		$sql="select * from frame_newsblock where frame_id=".$id." order by `index`";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$out.='<newsblock title="'.StringUtils::escapeXML($row['title']).'">'.
			'<!--newsblock#'.$row['id'].'-->'.
			'</newsblock>';
		}
		Database::free($result);
		return $out;
	}
}