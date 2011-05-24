<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
require_once($basePath.'Editor/Classes/Parts/PartController.php');
require_once($basePath.'Editor/Classes/Parts/ListPart.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');
require_once($basePath.'Editor/Classes/In2iGui.php');
require_once($basePath.'Editor/Classes/Utilities/DateUtils.php');
require_once($basePath.'Editor/Classes/Objects/Calendarsource.php');
require_once($basePath.'Editor/Classes/Objects/News.php');
require_once($basePath.'Editor/Classes/Objects/Newssource.php');
require_once($basePath.'Editor/Classes/Model/Query.php');

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
		$part->setTitle(Request::getUnicodeString('title'));
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
		'<div id="part_list_container">'.
		$this->render($part,$context).
		'</div>';
	}
	
	function editorGui($part,$context) {
		$gui='
		<window title="Nyheder" name="listWindow" width="300">
			<tabs small="true" centered="true">
				<tab title="Indstillinger" padding="10">
					<formula name="formula">
						<group labels="above">
							<text label="Titel" value="'.StringUtils::escapeXML($part->getTitle()).'" key="title"/>
							<number label="Dage" value="'.$part->getTimeCount().'" key="time_count"/>
							<number label="Maksimalt antal" value="'.$part->getMaxItems().'" key="maxitems"/>
							<checkbox label="Vis kilde" value="'.$part->getShowSource().'" key="show_source"/>
						</group>
					</formula>
				</tab>
				<tab title="Data">
					<overflow max-height="300">
					<formula padding="10" name="dataFormula">
						<fieldset legend="Nyheder">
							<group labels="above">
								<checkboxes label="Grupper" key="newsGroups">
								'.GuiUtils::buildObjectItems('newsgroup').'
								</checkboxes>
								<checkboxes label="Kilder" key="newsSources">
								'.GuiUtils::buildObjectItems('newssource').'
								</checkboxes>
							</group>
						</fieldset>
						<space height="10"/>
						<fieldset legend="Begivenheder">
							<group labels="above">
								<checkboxes label="Kalendere" key="calendars">
								'.GuiUtils::buildObjectItems('calendar').'
								</checkboxes>
								<checkboxes label="Kilder" key="calendarSources">
								'.GuiUtils::buildObjectItems('calendarsource').'
								</checkboxes>
							</group>
						</fieldset>
					</formula>
					</overflow>
				</tab>
			</tabs>
		</window>';
		return In2iGui::renderFragment($gui);
	}
	
	function buildData($part) {
		$data = array();
		$data['calendarSources'] = $this->buildValues('calendarsource',$part);
		$data['calendars'] = $this->buildValues('calendar',$part);
		$data['newsGroups'] = $this->buildValues('newsgroup',$part);
		$data['newsSources'] = $this->buildValues('newssource',$part);
		return In2iGui::toJSON($data);
	}
	
	function buildValues($type,$part) {
		return Database::selectIntArray("select object.id from part_list_object,object where part_list_object.object_id=object.id and object.type='".$type."' and part_id=".$part->getId());
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
			$to = DateUtils::addDays($from,$part->getTimeCount());
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
							$item->setUrl($sourceEvent['url']);
							if ($part->getShowSource()) {
								$item->setSource($sourceEvent['calendarDisplayTitle']);
							}
							$items[] = $item;
						}
					}
				} else if ($type=='newsgroup') {
					$newsItems = Query::after('news')->withCustom('group',$id)->withCustom('startdate',$from)->withCustom('enddate',$to)->orderBy('startdate')->get();
					foreach ($newsItems as $newsItem) {
						$item = new PartListItem();
						$item->setStartDate($newsItem->getStartDate());
						$item->setEndDate($newsItem->getEndDate());
						$item->setTitle($newsItem->getTitle());
						$item->setText($newsItem->getNote());
						$items[] = $item;
					}
				} else if ($type=='newssource') {
					if ($source = Newssource::load($id)) {
						$newsItems = Query::after('newssourceitem')->withProperty('newssource_id',$id)->withCustom('minDate',DateUtils::addDays(time(),$part->getTimeCount()*-1))->orderBy('date')->descending()->get();
						foreach ($newsItems as $newsItem) {
							$item = new PartListItem();
							$item->setStartDate($newsItem->getDate());
							$item->setTitle($newsItem->getTitle());
							$item->setText($newsItem->getText());
							if ($part->getShowSource()) {
								$item->setSource($source->getTitle());
							}
							$items[] = $item;
						}
					}
				}
			}
		}
		$items = array_slice($items,0,$part->getMaxItems());
		$this->sortItems($items);
		$items = array_reverse($items);
		foreach ($items as $item) {
			$data.='<item>'.
			'<title>'.StringUtils::escapeXML($item->getTitle()).'</title>'.
			'<text>'.StringUtils::escapeXML($item->getText()).'</text>';
			if (StringUtils::isNotBlank($item->getUrl())) {
				'<url>'.StringUtils::escapeXML($item->getUrl()).'</url>';
			}
			if ($item->getSource()) {
				$data.='<source>'.StringUtils::escapeXML($item->getSource()).'</source>';
			}
			if ($item->getStartDate()) {
				$data.=DateUtils::buildTag('date',$item->getStartDate());
			}
			if ($item->getEndDate()) {
				$data.=DateUtils::buildTag('end-date',$item->getEndDate());
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
	

	class PartListItem {
	
		var $title;
		var $text;
		var $startDate;
		var $endDate;
		var $source;
		var $url;
	
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
		
		function setUrl($url) {
		    $this->url = $url;
		}

		function getUrl() {
		    return $this->url;
		}
		
	}