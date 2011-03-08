<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
global $basePath;
require_once($basePath.'Editor/Classes/Object.php');
require_once($basePath.'Editor/Classes/Utilities/DateUtils.php');
require_once($basePath.'Editor/Classes/Services/ObjectService.php');

Object::$schema['news'] = array(
	'imageId'   => array('type'=>'int','column'=>'image_id'),
	'startdate'  => array('type'=>'datetime'),
	'enddate'  => array('type'=>'datetime')
);

class News extends Object {
	var $startdate;
	var $enddate;
	var $imageId;
	
	function News() {
		parent::Object('news');
	}
	
	function setStartdate($stamp) {
		$this->startdate = $stamp;
	}
	
	function getStartdate() {
		return $this->startdate;
	}
	
	function setEnddate($stamp) {
		$this->enddate = $stamp;
	}
	
	function getEnddate() {
		return $this->enddate;
	}
	
	function setImageId($id) {
		$this->imageId = $id;
	}
	
	function getImageId() {
		return $this->imageId;
	}
	
	////////////////////////////////// Groups //////////////////////////////
	
	function getGroupIds() {
		global $basePath;
		require_once($basePath.'Editor/Classes/Database.php');
		$sql="select newsgroup_id as id from newsgroup_news where news_id=".$this->id;
		return Database::getIds($sql);
	}
	
	function updateGroupIds($ids) {
		$sql="delete from newsgroup_news where news_id=".$this->id;
		Database::delete($sql);
		if (is_array($ids)) {
			foreach ($ids as $id) {
				$sql="insert into newsgroup_news (news_id, newsgroup_id) values (".$this->id.",".$id.")";
				Database::insert($sql);
			}
		}
	}
	
	function addGroupId($id) {
		$sql = "delete from newsgroup_news where news_id=".Database::int($this->id)." and newsgroup_id=".Database::int($id);
		Database::delete($sql);
		$sql = "insert into newsgroup_news (newsgroup_id,news_id) values (".Database::int($id).",".Database::int($this->id).")";
		Database::insert($sql);
	}
	
	///////////////////////////////// Search //////////////////////////
	
	function search($query = array()) {
		$start = $query['startDate'];
		$end = $query['endDate'];
		$sql = "select news.object_id as id from news,newsgroup_news where news.object_id=newsgroup_news.news_id and newsgroup_news.newsgroup_id=".$query['group'];
		$sql.=" and ((news.startdate is null and news.enddate is null) or (news.startdate>=".Database::datetime($start)." and news.startdate<=".Database::datetime($end).") or (news.enddate>=".Database::datetime($start)." and news.enddate<=".Database::datetime($end).") or (news.enddate>=".Database::datetime($start)." and news.startdate is null) or (news.startdate<=".Database::datetime($end)." and news.enddate is null))";
		$sql.=" order by news.startdate";
		$out = array();
		$ids = Database::getIds($sql);
		foreach ($ids as $id) {
			$out[] = News::load($id);
		}
		return $out;
	}
	
	function search2($query=array()) {
		$query['type']='news';
		$query['limits']=array();
		$query['tables']=array();
		if (isset($query['group'])) {
			$query['tables'][] = 'newsgroup_news';
			$query['limits'][] = 'newsgroup_news.news_id=object.id';
			$query['limits'][] = 'newsgroup_news.newsgroup_id='.$query['group'];
		}
		if (isset($query['linkType'])) {
			$query['tables'][] = 'object_link';
			$query['limits'][] = 'object_link.object_id=object.id';
			$query['limits'][] = 'object_link.target_type='.Database::text($query['linkType']);
		}
		if (isset($query['active'])) {
			if ($query['active']) {
				$query['limits'][] = '((news.startdate is null and news.enddate is null) or (news.startdate<now() and news.enddate>now()) or (news.startdate is null and news.enddate>now()) or (news.startdate<now() and news.enddate is null))';
			} else {
				$query['limits'][] = 'not ((news.startdate is null and news.enddate is null) or (news.startdate<now() and news.enddate>now()) or (news.startdate is null and news.enddate>now()) or (news.startdate<now() and news.enddate is null))';
			}
		}
		return ObjectService::find($query);
	}
	
	////////////////////////////// Persistence ////////////////////////
	
	function load($id) {
		return Object::get($id,'news');
	}
	
	function sub_publish() {
		$data = '<news xmlns="'.parent::_buildnamespace('1.0').'">';
		if (isset($this->startdate)) {
			$data.=DateUtils::buildTag('startdate',$this->startdate);
		}
		if (isset($this->enddate)) {
			$data.=DateUtils::buildTag('enddate',$this->enddate);
		}
		if ($this->imageId) {
			$data.=Object::getObjectData($this->imageId);
		}
		$data.='</news>';
		return $data;
	}
	
	function removeMore() {
		$sql="delete from newsgroup_news where news_id=".$this->id;
		Database::delete($sql);
	}
	
	/////////////////////////// GUI /////////////////////////
	
	function getIcon() {
        return "Part/News";
	}
	
	function getIn2iGuiIcon() {
        return "common/news";
	}
}
?>