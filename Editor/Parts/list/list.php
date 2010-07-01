<?
/**
 * @package OnlinePublisher
 * @subpackage Parts.List
 */
require_once($basePath.'Editor/Classes/Part.php');
require_once($basePath.'Editor/Classes/Request.php');
require_once($basePath.'Editor/Classes/Calendarsource.php');
require_once($basePath.'Editor/Classes/DateUtil.php');
require_once($basePath.'Editor/Classes/XmlUtils.php');
require_once($basePath.'Editor/Classes/In2iGui.php');
require_once($basePath.'Editor/Classes/News.php');

class PartList extends Part {
	
	function PartList($id=0) {
		parent::Part('list');
		$this->id = $id;
	}

	function sub_isDynamic() {
		return true;
	}
	
	function sub_display($context) {
		$sql = "select * from part_list where part_id=".$this->id;
		if ($row = Database::selectFirst($sql)) {
			return $this->render();
		}
		return '';
	}
	
	function sub_editor($context) {
		global $baseUrl;
		$sql = "select * from part_list where part_id=".$this->id;
		if ($row = Database::selectFirst($sql)) {
			$objects = Database::selectArray("select object_id from part_list_object where part_id=".$this->id);
			return 
		    '<input type="hidden" name="time_count" value="'.$row['time_count'].'"/>'.
		    '<input type="hidden" name="maxitems" value="'.$row['maxitems'].'"/>'.
		    '<input type="hidden" name="title" value="'.$row['title'].'"/>'.
		    '<input type="hidden" name="objects" value="'.implode(',',$objects).'"/>'.
		    '<input type="hidden" name="show_source" value="'.($row['show_source'] ? 'true' : 'false').'"/>'.
			'<script src="'.$baseUrl.'Editor/Parts/list/script.js" type="text/javascript" charset="utf-8"></script>'.
			'<script type="text/javascript">
			op.part.List.setData('.$this->buildData().');'.
			'</script>'.
			'<div id="part_list_container">'.$this->render().'</div>';
		} else {
			return '';
		}
	}
	
	function buildData() {
		$data = array();
		$data['calendarsourceValues'] = $this->buildValues('calendarsource');
		$data['calendarsourceOptions'] = $this->buildItems('calendarsource');
		$data['calendarValues'] = $this->buildValues('calendar');
		$data['calendarOptions'] = $this->buildItems('calendar');
		$data['newsgroupValues'] = $this->buildValues('newsgroup');
		$data['newsgroupOptions'] = $this->buildItems('newsgroup');
		return In2iGui::toJSON($data);
	}
	
	function buildValues($type) {
		return Database::selectIntArray("select object.id from part_list_object,object where part_list_object.object_id=object.id and object.type='".$type."' and part_id=".$this->id);
	}
	
	function buildItems($type) {
		$options = array();
		$sql = "select id as value,title from object where type='".$type."' order by title";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$options[] = array('value'=>intval($row['value']),'title'=>$row['title']);
		}
		Database::free($result);
		return $options;
	}
	
	function sub_create() {
		$sql = "insert into part_list (part_id) values (".$this->id.")";
		Database::insert($sql);
	}
	
	function sub_delete() {
		$sql = "delete from part_list where part_id=".$this->id;
		Database::delete($sql);
		$sql = "delete from part_list_object where part_id=".$this->id;
		Database::delete($sql);
	}
	
	function sub_update() {
		$title = Request::getString('title');
		$objects = Request::getString('objects');
		$timeCount = Request::getInt('time_count');
		$showSource = Request::getBoolean('show_source');
		$maxItems = Request::getInt('maxitems');
		$sql = "update part_list set".
			" title=".Database::text($title).
			",time_count=".Database::int($timeCount).
			",show_source=".Database::boolean($showSource).
			",maxitems=".Database::int($maxItems).
			" where part_id=".$this->id;
		Database::update($sql);		
		$sql = "delete from part_list_object where part_id=".$this->id;
		Database::delete($sql);
		if ($objects!='') {
			$ids = split(',',$objects);
			foreach ($ids as $id) {
				$sql = "insert into part_list_object (part_id,object_id) values (".$this->id.",".$id.")";
				Database::insert($sql);
			}
		}
	}
	
	function sub_build($context) {
		$sql = "select * from part_list where part_id=".$this->id;
		if ($row = Database::selectFirst($sql)) {
			$row['objects'] = Database::selectArray("select object_id from part_list_object where part_id=".$this->id);
			$row['show_source'] = $row['show_source']==1;
			return $this->generate($row);
		}
		return '';
	}
		
	function sub_preview() {
		$objects = Request::getIntArrayComma('objects');
		$params = array(
			'title'=>Request::getUnicodeString('title'),
			'time_count'=>Request::getInt('time_count'),
			'maxitems'=>Request::getInt('maxitems'),
			'show_source'=>Request::getBoolean('show_source'),
			'objects'=>$objects
		);
		return $this->generate($params);
	}
		
	function generate($params) {
		$data = '<list xmlns="'.$this->_buildnamespace('1.0').'">';
		if ($params['title']!='') {
			$data.='<title>'.encodeXML($params['title']).'</title>';
		}
		$items = array();
		if (count($params['objects'])>0) {
			error_log(print_r($params['objects'],true));
			$objects = Database::selectAll("select id,type from object where id in (".implode($params['objects'],',').")");
			$from = time();
			$to = DateUtil::addDays($from,$params['time_count']);
			foreach ($objects as $object) {
				$type = $object['type'];
				$id = $object['id'];
				if ($type=='calendarsource') {
					$source = Calendarsource::load($id);
					if ($source) {
						$source->synchronize();
						$sourceEvents = $source->getEvents(array('startDate'=>$from,'endDate'=>$to));
						foreach ($sourceEvents as $sourceEvent) {
							$item = new PartListItem();
							$item->setStartDate($sourceEvent['startDate']);
							$item->setEndDate($sourceEvent['endDate']);
							$item->setTitle($sourceEvent['summary']);
							$item->setText($sourceEvent['description']);
							if ($params['show_source']) {
								$item->setSource($sourceEvent['calendarDisplayTitle']);
							}
							$items[] = $item;
						}
					}
				} else if ($type=='newsgroup') {
					$newsItems = News::search(array('group'=>$id,'startDate'=>$from,'endDate'=>$to));
					foreach ($newsItems as $newsItem) {
						$item = new PartListItem();
						$item->setStartDate($newsItem->getStartDate());
						$item->setEndDate($newsItem->getEndDate());
						$item->setTitle($newsItem->getTitle());
						$item->setText($newsItem->getNote());
						$items[] = $item;
					}
				}
			}
		}
		$this->sortItems($items);
		$items = array_slice($items,0,$params['maxitems']);
		foreach ($items as $item) {
			$data.='<item>'.
			'<title>'.encodeXML($item->getTitle()).'</title>'.
			'<text>'.encodeXML($item->getText()).'</text>';
			if ($item->getSource()) {
				$data.='<source>'.encodeXML($item->getSource()).'</source>';
			}
			if ($item->getStartDate()) {
				$data.=XmlUtils::buildDate('date',$item->getStartDate());
			}
			if ($item->getEndDate()) {
				$data.=XmlUtils::buildDate('end-date',$item->getEndDate());
			}
			$data.='</item>';
		}
		$data.='</list>';
		return $data;
	}
	
	function sortItems(&$items) {
		usort($items,array('PartList','_startDateComparator'));
	}
	
	function _startDateComparator($a, $b) {
		$a = $a->getStartDate();
		$b = $b->getStartDate();
		if (!$a) $a=0;
		if (!$b) $b=0;
    	if ($a == $b) {
        	return 0;
    	}
    	return ($a < $b) ? -1 : 1;
	}
	
	// Toolbar stuff
	
	function isIn2iGuiEnabled() {
		return true;
	}
	
	function getToolbarTabs() {
		return array('list' => array('title' => 'Liste'));
	}
	
	function getToolbarDefaultTab() {
		return 'list';
	}
}

class PartListItem {
	
	var $title;
	var $text;
	var $startDate;
	var $endDate;
	var $source;
	
	function setTitle($title) {
	    $this->title = $title;
	}

	function getTitle() {
	    return $this->title;
	}
	
	function setText($text) {
	    $this->text = $text;
	}

	function getText() {
	    return $this->text;
	}
	
	function setStartDate($startDate) {
	    $this->startDate = $startDate;
	}

	function getStartDate() {
	    return $this->startDate;
	}
	
	function setEndDate($endDate) {
	    $this->endDate = $endDate;
	}

	function getEndDate() {
	    return $this->endDate;
	}
	
	function setSource($source) {
	    $this->source = $source;
	}

	function getSource() {
	    return $this->source;
	}
	
}
?>