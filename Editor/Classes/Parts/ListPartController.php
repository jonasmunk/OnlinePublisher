<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

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
		$part->setSortDirection('descending');
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
		$part->setTitle(Request::getString('title'));
		$part->setSortDirection(Request::getString('sort_direction'));
		$part->setTimeCount(Request::getInt('time_count'));
		$part->setMaxItems(Request::getInt('maxitems'));
		$part->setMaxTextLength(Request::getInt('maxtextlength'));
		$part->setShowSource(Request::getBoolean('show_source'));
		$part->setShowText(Request::getBoolean('show_text'));
		$part->setShowTimezone(Request::getBoolean('show_timezone'));
		return $part;
	}
	
	function editor($part,$context) {
		return 
	    '<input type="hidden" name="time_count" value="'.$part->getTimeCount().'"/>'.
	    '<input type="hidden" name="maxitems" value="'.$part->getMaxItems().'"/>'.
	    '<input type="hidden" name="maxtextlength" value="'.$part->getMaxTextLength().'"/>'.
	    '<input type="hidden" name="title" value="'.Strings::escapeEncodedXML($part->getTitle()).'"/>'.
	    '<input type="hidden" name="sort_direction" value="'.Strings::escapeXML($part->getSortDirection()).'"/>'.
	    '<input type="hidden" name="objects" value="'.implode(',',$part->getObjectIds()).'"/>'.
	    '<input type="hidden" name="show_source" value="'.($part->getShowSource() ? 'true' : 'false').'"/>'.
	    '<input type="hidden" name="show_text" value="'.($part->getShowText() ? 'true' : 'false').'"/>'.
	    '<input type="hidden" name="show_timezone" value="'.($part->getShowTimezone() ? 'true' : 'false').'"/>'.
		'<script src="'.ConfigurationService::getBaseUrl().'Editor/Parts/list/script.js" type="text/javascript" charset="utf-8"></script>'.
		'<script type="text/javascript">
		op.part.List.setData('.$this->buildData($part).');'.
		'</script>'.
		'<div id="part_list_container">'.
		$this->render($part,$context).
		'</div>';
	}
	
	function editorGui($part,$context) {
		$zoneItems = '';
		foreach (Dates::getTimeZones() as $zone) {
			$zoneItems.='<item title="'.$zone.'" value="'.$zone.'"/>';
		}
		$gui='
		<window title="Liste" name="listWindow" width="300" close="false">
			<tabs small="true" centered="true">
				<tab title="{Settings; da:Indstillinger}" padding="10">
					<formula name="formula">
						<fields labels="above">
							<field label="{Title; da:Titel}">
								<text-input value="'.Strings::escapeEncodedXML($part->getTitle()).'" key="title"/>
							</field>
						</fields>
						<fieldset legend="{Limitation; da:Begrænsning}">
							<fields labels="before">
								<field label="{Days; da:Dage}">
									<number-input value="'.$part->getTimeCount().'" key="time_count" max="1000"/>
								</field>
								<field label="{Maximum count; da:Maksimalt antal}">
									<number-input value="'.$part->getMaxItems().'" key="maxitems" min="0" max="100"/>
								</field>
							</fields>
						</fieldset>
						<space height="10"/>
						<fieldset legend="{Appearance; da:Visning}">
							<fields>
								<field label="{Direction; da:Retning}">
									<radiobuttons key="sort_direction" value="'.Strings::escapeEncodedXML($part->getSortDirection()).'">
										<item value="descending" text="{Descending; da:Faldende}"/>
										<item value="ascending" text="{Ascending; da:Stigende}"/>
									</radiobuttons>
								</field>
								<field label="{Show text; da:Vis tekst}">
									<checkbox value="'.($part->getShowText() ? 'true' : 'false').'" key="show_text"/>
								</field>
								<field label="{Text length; da:Tekstlængde}">
									<number-input value="'.$part->getMaxTextLength().'" key="maxtextlength" min="0" max="2000"/>
								</field>
								<field label="{Show source; da:Vis kilde}">
									<checkbox value="'.($part->getShowSource() ? 'true' : 'false').'" key="show_source"/>
								</field>
								<field label="{Show time zone; da:Vis tidszone}">
									<checkbox value="'.($part->getShowTimezone() ? 'true' : 'false').'" key="show_timezone"/>
								</field>
								<field label="{Time zone; da:Tidszone}">
									<dropdown>
										<item value="" text="Standard"/>
										'.$zoneItems.'
									</dropdown>
								</field>
							</fields>
						</fieldset>
					</formula>
				</tab>
				<tab title="Data">
					<overflow max-height="300">
					<formula padding="10" name="dataFormula">
						<fieldset legend="{News; da:Nyheder}">
							<fields labels="above">
								<field label="{Groups; da:Grupper}">
									<checkboxes key="newsGroups">'.GuiUtils::buildObjectItems('newsgroup').'</checkboxes>
								</field>
								<field label="{Sources; da:Kilder}">
									<checkboxes key="newsSources">'.GuiUtils::buildObjectItems('newssource').'</checkboxes>							
								</field>
							</fields>
						</fieldset>
						<space height="10"/>
						<fieldset legend="{Events; da:Begivenheder}">
							<fields labels="above">
								<field label="{Calendars; da:Kalendere}">
									<checkboxes key="calendars">'.GuiUtils::buildObjectItems('calendar').'</checkboxes>
								</field>
								<field label="{Sources; da:Kilder}">
									<checkboxes key="calendarSources">'.GuiUtils::buildObjectItems('calendarsource').'</checkboxes>
								</field>
							</fields>
						</fieldset>
					</formula>
					</overflow>
				</tab>
			</tabs>
		</window>';
		return UI::renderFragment($gui);
	}
	
	function buildData($part) {
		$data = array();
		$data['calendarSources'] = $this->buildValues('calendarsource',$part);
		$data['calendars'] = $this->buildValues('calendar',$part);
		$data['newsGroups'] = $this->buildValues('newsgroup',$part);
		$data['newsSources'] = $this->buildValues('newssource',$part);
		return Strings::toJSON($data);
	}
	
	function buildValues($type,$part) {
		return Database::selectIntArray("select object.id from part_list_object,object where part_list_object.object_id=object.id and object.type='".$type."' and part_id=".$part->getId());
	}
	
	function buildSub($part,$context) {
		$dirty = false;
		$items = array();
		if (count($part->getObjectIds())>0) {
			$objects = Database::selectAll("select id,type from object where id in (".implode($part->getObjectIds(),',').")");
			$from = time();
			$to = Dates::addDays($from,$part->getTimeCount());
			foreach ($objects as $object) {
				$type = $object['type'];
				$id = $object['id'];
				if ($type=='calendarsource') {
					$source = Calendarsource::load($id);
					if ($source) {
						if ($context->getSynchronize()) {
							$source->synchronize();
						}
						if (!$source->isInSync()) {
							//Log::debug('Calendarsource is dirty: '.$id);
							$dirty = true;
						}
						$sourceEvents = $source->getEvents(array('startDate'=>$from,'endDate'=>$to));
						foreach ($sourceEvents as $sourceEvent) {
							$item = new PartListItem();
							$item->setStartDate($sourceEvent['startDate']);
							$item->setEndDate($sourceEvent['endDate']);
							$item->setTitle($sourceEvent['summary']);
							if ($part->getShowText()) {
								$item->setText($sourceEvent['description']);
							}
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
						if ($part->getShowText()) {
							$item->setText($newsItem->getNote());
						}
						$items[] = $item;
					}
				} else if ($type=='newssource') {
					if ($source = Newssource::load($id)) {
						if ($context->getSynchronize()) {
							$source->synchronize();
						}
						if (!$source->isInSync()) {
							//Log::debug('Newssource is dirty: '.$id);
							$dirty = true;
						}
						$newsItems = Query::after('newssourceitem')->withProperty('newssource_id',$id)->withCustom('minDate',Dates::addDays(time(),$part->getTimeCount()*-1))->orderBy('date')->descending()->get();
						foreach ($newsItems as $newsItem) {
							$item = new PartListItem();
							$item->setDate($newsItem->getDate());
							$item->setTitle($newsItem->getTitle());
							if ($part->getShowText()) {
								$item->setText($newsItem->getText());
							}
							if ($part->getShowSource()) {
								$item->setSource($source->getTitle());
							}
							$items[] = $item;
						}
					}
				}
			}
		}

		$data = '<list xmlns="'.$this->getNamespace().'" dirty="'.($dirty ? 'true' : 'false').'">';
		$data.='<settings show-timezone="'.($part->getShowTimeZone() ? 'true' : 'false').'"/>';
		if (Strings::isNotBlank($part->getTitle())) {
			$data.='<title>'.Strings::escapeEncodedXML($part->getTitle()).'</title>';
		}
		$this->sortItems($items);
		if ($part->getSortDirection()=='ascending') {
			$items = array_reverse($items);
		}
		$items = array_slice($items,0,$part->getMaxItems());
		foreach ($items as $item) {
			$data.='<item>'.
			'<title>'.Strings::escapeEncodedXML($item->getTitle()).'</title>';
			if (Strings::isNotBlank($item->getText())) {
				$text = Strings::removeTags($item->getText());
				if ($part->getMaxTextLength()) {
					$text = Strings::shortenString($text,$part->getMaxTextLength());
				}
				$data.='<text>'.Strings::escapeXMLBreak($text,'<break/>').'</text>';
			}
			if (Strings::isNotBlank($item->getUrl())) {
				$data.='<url>'.Strings::escapeEncodedXML($item->getUrl()).'</url>';
			}
			if ($item->getSource()) {
				$data.='<source>'.Strings::escapeEncodedXML($item->getSource()).'</source>';
			}
			if ($item->getDate()) {
				$data.=Dates::buildTag('date',$item->getDate());
			}
			if ($item->getStartDate()) {
				$data.=Dates::buildTag('start-date',$item->getStartDate());
			}
			if ($item->getEndDate()) {
				$data.=Dates::buildTag('end-date',$item->getEndDate());
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
		$date1 = $a->getStartDate();
		if (!$date1) {
			$date1 = $a->getDate();
		}
		$date2 = $b->getStartDate();
		if (!$date2) {
			$date2 = $b->getDate();
		}
		if (!$date1) $date1=0;
		if (!$date2) $date2=0;
    	if ($date1 == $date2) {
        	return 0;
    	}
    	return ($date1 < $date2) ? -1 : 1;
	}
}
	

class PartListItem {

	var $title;
	var $text;
	var $date;
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
	
	function setDate($date) {
	    $this->date = $date;
	}

	function getDate() {
	    return $this->date;
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