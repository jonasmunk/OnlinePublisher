<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
require_once($basePath.'Editor/Classes/Parts/PartController.php');
require_once($basePath.'Editor/Classes/Parts/ListPart.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');
require_once($basePath.'Editor/Classes/DateUtil.php');
require_once($basePath.'Editor/Classes/Calendarsource.php');
require_once($basePath.'Editor/Classes/News.php');

class ListPartController extends PartController
{
	function ListPartController() {
		parent::PartController('list');
	}
	
	function createPart() {
		$part = new ListPart();
		$part->setTitle("Min liste");
		$part->setTimeType('days');
		$part->setVariant('box');
		$part->setMaxItems(10);
		$part->save();
		return $part;
	}
	
	function display($part,$context) {
		return $this->render($part,$context);
	}
	
	function isDynamic($part) {
		return true;
	}
		
	function getFromRequest($id) {
		$part = ListPart::load($id);
		$part->setObjectIds(Request::getIntArrayComma('objects'));
		$part->setTitle(Request::getEncodedString('title'));
		$part->setTimeCount(Request::getInt('time_count'));
		$part->setMaxItems(Request::getInt('maxitems'));
		$part->setShowSource(Request::getBoolean('show_source'));
		return $part;
	}
	
	function editor($part,$context) {
		global $baseUrl;
		return 
	    '<input type="hidden" name="time_count" value="'.$part->getTimeCount().'"/>'.
	    '<input type="hidden" name="maxitems" value="'.$part->getMaxItems().'"/>'.
	    '<input type="hidden" name="title" value="'.StringUtils::escapeXML($part->getTitle()).'"/>'.
	    '<input type="hidden" name="objects" value="'.implode(',',$part->getObjectIds()).'"/>'.
	    '<input type="hidden" name="show_source" value="'.($part->getShowSource() ? 'true' : 'false').'"/>'.
		'<script src="'.$baseUrl.'Editor/Parts/list/script.js" type="text/javascript" charset="utf-8"></script>'.
		'<script type="text/javascript">
		op.part.List.setData('.$this->buildData($part).');'.
		'</script>'.
		'<div id="part_list_container">'.$this->render($part,$context).'</div>';
	}
	
	function buildData($part) {
		$data = array();
		$data['calendarsourceValues'] = $this->buildValues('calendarsource',$part);
		$data['calendarsourceOptions'] = $this->buildItems('calendarsource');
		$data['calendarValues'] = $this->buildValues('calendar',$part);
		$data['calendarOptions'] = $this->buildItems('calendar');
		$data['newsgroupValues'] = $this->buildValues('newsgroup',$part);
		$data['newsgroupOptions'] = $this->buildItems('newsgroup');
		return In2iGui::toJSON($data);
	}
	
	function buildValues($type,$part) {
		return Database::selectIntArray("select object.id from part_list_object,object where part_list_object.object_id=object.id and object.type='".$type."' and part_id=".$part->getId());
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
	
	function buildSub($part,$context) {
		$data = '<list xmlns="'.$this->getNamespace().'">';
		if (StringUtils::isNotBlank($part->getTitle())) {
			$data.='<title>'.StringUtils::escapeXML($part->getTitle()).'</title>';
		}
		$items = array();
		if (count($part->getObjectIds())>0) {
			$objects = Database::selectAll("select id,type from object where id in (".implode($part->getObjectIds(),',').")");
			$from = time();
			$to = DateUtil::addDays($from,$part->getTimeCount());
			foreach ($objects as $object) {
				$type = $object['type'];
				$id = $object['id'];
				if ($type=='calendarsource') {
					$source = Calendarsource::load($id);
					if ($source) {
						$source->synchronize();
						$sourceEvents = $source->getEvents(array('startDate'=>$from,'endDate'=>$to));
						foreach ($sourceEvents as $sourceEvent) {
							$item = new PartListItem2();
							$item->setStartDate($sourceEvent['startDate']);
							$item->setEndDate($sourceEvent['endDate']);
							$item->setTitle($sourceEvent['summary']);
							$item->setText($sourceEvent['description']);
							if ($part->getShowSource()) {
								$item->setSource($sourceEvent['calendarDisplayTitle']);
							}
							$items[] = $item;
						}
					}
				} else if ($type=='newsgroup') {
					$newsItems = News::search(array('group'=>$id,'startDate'=>$from,'endDate'=>$to));
					foreach ($newsItems as $newsItem) {
						$item = new PartListItem2();
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
		$items = array_slice($items,0,$part->getMaxItems());
		foreach ($items as $item) {
			$data.='<item>'.
			'<title>'.StringUtils::escapeXML($item->getTitle()).'</title>'.
			'<text>'.StringUtils::escapeXML($item->getText()).'</text>';
			if ($item->getSource()) {
				$data.='<source>'.StringUtils::escapeXML($item->getSource()).'</source>';
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
		usort($items,array('ListPartController','_startDateComparator'));
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
}
	

	class PartListItem2 {
	
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