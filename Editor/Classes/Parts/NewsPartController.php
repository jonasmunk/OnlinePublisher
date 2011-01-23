<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
require_once($basePath.'Editor/Classes/Parts/PartController.php');
require_once($basePath.'Editor/Classes/Parts/NewsPart.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class NewsPartController extends PartController
{
	function NewsPartController() {
		parent::PartController('news');
	}
	
	function createPart() {
		$part = new NewsPart();
		$part->setTitle('Seneste nyt');
		$part->setVariant('box');
		$part->setMode('single');
		$part->setSortDir('ascending');
		$part->setSortBy('startdate');
		$part->setTimeType('always');
		$part->save();
		return $part;
	}
	
	function buildSub($part,$context) {
		$data='<news xmlns="'.$this->getNamespace().'">';
		if (StringUtils::isNotBlank($part->getVariant())) {
			$data.='<'.$part->getVariant().'>';
			if (StringUtils::isNotBlank($part->getTitle())) {
				$data.='<title>'.StringUtils::escapeXML($part->getTitle()).'</title>';
			}
			$maxitems = $part->getMaxItems(); // TODO: Build this into sql PERFORMANCE!
			$sql = $this->buildSql($part);
			if ($sql!='') {
				$result = Database::select($sql);
				while ($newsRow = Database::next($result)) {
					$data.=$newsRow['data'];
					$maxitems--;
					if ($maxitems==0) break;
				}
				Database::free($result);
			}

			$data.='</'.$part->getVariant().'>';
		}
		$data.='</news>';
		return $data;
	}
	
	function isDynamic($part) {
		return true;
	}
	
	function getFromRequest($id) {
		$part = NewsPart::load($id);
		$part->setTitle(Request::getUnicodeString('title'));
		$part->setVariant(Request::getString('variant'));
		$part->setMode(Request::getString('mode'));
		$part->setSortBy(Request::getString('sortby'));
		$part->setSortDir(Request::getString('sortdir'));
		$part->setTimeType(Request::getString('timetype'));
		$part->setTimeCount(Request::getInt('timecount'));
		$part->setMaxItems(Request::getInt('maxitems'));
		$part->setNewsId(Request::getInt('news'));
		$part->setNewsGroupIds(Request::getIntArrayComma('groups'));
		return $part;
	}
	
	function display($part,$context) {
		return $this->render($part,$context);
	}
	
	function getIndex($part) {
		return $part->getTitle();
	}
	
	function editor($part,$context) {
		global $baseUrl;

		$groups = $part->getNewsGroupIds();

		return
		'<input type="hidden" name="title" value="'.StringUtils::escapeXML($part->getTitle()).'"/>'.
		'<input type="hidden" name="mode" value="'.$part->getMode().'"/>'.
		'<input type="hidden" name="news" value="'.$part->getNewsId().'"/>'.
		'<input type="hidden" name="groups" value="'.implode(',',$groups).'"/>'.
		'<input type="hidden" name="align" value="'.$part->getAlign().'"/>'.
		'<input type="hidden" name="sortby" value="'.$part->getSortBy().'"/>'.
		'<input type="hidden" name="sortdir" value="'.$part->getSortDir().'"/>'.
		'<input type="hidden" name="maxitems" value="'.$part->getMaxItems().'"/>'.
		'<input type="hidden" name="timetype" value="'.$part->getTimeType().'"/>'.
		'<input type="hidden" name="timecount" value="'.$part->getTimeCount().'"/>'.
		'<input type="hidden" name="variant" value="'.$part->getVariant().'"/>'.
		'<div id="part_news_preview">'.
		$this->render($part,$context).
		'</div>'.
		'<script src="'.$baseUrl.'Editor/Parts/news/Script.js"></script>';
	}
	
	function editorGui($part,$context) {
		$gui='
		<window title="Nyheder" name="newsWindow" width="300">
			<tabs small="true" centered="true">
				<tab title="Indstillinger" padding="10">
					<formula>
						<group labels="above">
							<text label="Titel" value="'.StringUtils::escapeXML($part->getTitle()).'" name="newsTitle"/>
							<radiobuttons label="Variant" value="'.$part->getVariant().'" name="newsVariant">
								<item label="Liste" value="list"/>
								<item label="Boks" value="box"/>
							</radiobuttons>
							<radiobuttons label="Justering" value="'.$part->getAlign().'" name="newsAlign">
								<item label="Venstre" value="left"/>
								<item label="Midte" value="center"/>
								<item label="Hoejre" value="right"/>
							</radiobuttons>
						</group>
						<fieldset legend="Nyheder">
							<group labels="above">
								<checkboxes label="Grupper" name="newsGroups">
								'.GuiUtils::buildObjectItems('newsgroup').'
								</checkboxes>
								<dropdown label="Nyheder" name="newsNews">
								'.GuiUtils::buildObjectItems('news').'
								</dropdown>
							</group>
						</fieldset>
					</formula>
				</tab>
				<tab title="Visning" padding="10">
					<formula>
						<group labels="above">
							<radiobuttons label="Retning" value="'.$part->getSortDir().'" name="newsSortDir">
								<item label="Faldende" value="descending"/>
								<item label="Stigende" value="ascending"/>
							</radiobuttons>
							<radiobuttons label="Sorter efter" value="'.$part->getSortBy().'" name="newsSortBy">
								<item label="Startdato" value="startdate"/>
								<item label="Slutdato" value="enddate"/>
								<item label="Titel" value="title"/>
							</radiobuttons>
							<number label="Maksimalt antal" name="newsMaxItems" value="'.$part->getMaxItems().'"/>
							<dropdown label="Tid" name="newsTimeType" value="'.$part->getTimeType().'">
								<item label="Altid" value="always"/>
								<item label="Lige nu" value="now"/>
								<item label="Seneste timer..." value="hours"/>
								<item label="Seneste dage..." value="days"/>
								<item label="Seneste uger..." value="weeks"/>
								<item label="Seneste m&#229;neder..." value="months"/>
								<item label="Seneste &#229;r..." value="years"/>
							</dropdown>
							<number label="Antal" name="newsTimeCount" value="'.$part->getTimeCount().'"/>
						</group>
					</formula>
				</tab>
			</tabs>
		</window>
		';
		return In2iGui::renderFragment($gui);
	}
	
	function buildSql($part) {
		$sql = '';
		if ($part->getMode() == 'single' && $part->getNewsId()!='') {
			$sql="select * from object where id=".$part->getNewsId();
		}
		else if ($part->getMode() == 'groups') {
			$sortBy = $part->getSortBy();
			// Find sort direction
			if ($part->getSortDir()=='descending') {
				$sortDir = 'DESC';
			}
			else {
				$sortDir = 'ASC';
			}
			$timetype = $part->getTimeType();
			if ($timetype=='always') {
				$timeSql=''; // no time managing for always
			}
			else if ($timetype=='now') {
				// Create sql for active news
				$timeSql=" and ((news.startdate is null and news.enddate is null) or (news.startdate<=now() and news.enddate>=now()) or (news.startdate<=now() and news.enddate is null) or (news.startdate is null and news.enddate>=now()))";
			}
			else {
				$count=$part->getTimeCount();
				if ($timetype=='interval') {
					$start = intval($part->getStartdate());
					$end = intval($part->getEnddate());
				}
				else if ($timetype=='hours') {
					$start = mktime(date("H")-$count,date("i"),date("s"),date("m"),date("d"),date("Y"));
					$end = mktime();
				}
				else if ($timetype=='days') {
					$start = mktime(date("H"),date("i"),date("s"),date("m"),date("d")-$count,date("Y"));
					$end = mktime();
				}
				else if ($timetype=='weeks') {
					$start = mktime(date("H"),date("i"),date("s"),date("m"),date("d")-($count*7),date("Y"));
					$end = mktime();
				}
				else if ($timetype=='months') {
					$start = mktime(date("H"),date("i"),date("s"),date("m")-$count,date("d"),date("Y"));
					$end = mktime();
				}
				else if ($timetype=='years') {
					$start = mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")-$count);
					$end = mktime();
				}
				$timeSql=" and ((news.startdate is null and news.enddate is null) or (news.startdate>=".Database::datetime($start)." and news.startdate<=".Database::datetime($end).") or (news.enddate>=".Database::datetime($start)." and news.enddate<=".Database::datetime($end).") or (news.enddate>=".Database::datetime($start)." and news.startdate is null) or (news.startdate<=".Database::datetime($end)." and news.enddate is null))";
			}
			$groups = $part->getNewsGroupIds();
			if (isset($groups) && count($groups)>0) {
				$groupSql = " and newsgroup_news.newsgroup_id in (".implode($groups,',').")";
			} else {
				$groupSql = " and newsgroup_news.newsgroup_id=part_news_newsgroup.newsgroup_id and part_news_newsgroup.part_id=".$this->id;
			}
			$sql = "select distinct object.data from object,news, newsgroup_news, part_news_newsgroup where object.id=news.object_id and news.object_id=newsgroup_news.news_id".$groupSql.$timeSql." order by ".$sortBy." ".$sortDir;
		}
		return $sql;
	}
}
?>