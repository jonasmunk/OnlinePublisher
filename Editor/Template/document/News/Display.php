<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
	$output.='<td style="'.$sectionStyle.'" class="sectionTDnews">';
	$sql="select * from document_news where section_id=".$sectionId;
	$row = Database::selectFirst($sql);
	if ($row) {
		$output.='<div class="NewsBlock">';
		if ($row['title']!='') {
			$output.='<div class="NewsBlockTitle">'.$row['title'].'</div>';
		}
		
		if ($row['mode'] == 'single') {
			$sql="select * from object where id=".$row['news_id'];
			$singleRow = Database::selectFirst($sql);
			if ($singleRow) {
				$output.='<div class="NewsItem">'.
				'<div class="NewsTitle">'.$singleRow['title'].'</div>'.
				'<div class="NewsNote">'.$singleRow['note'].'</div>'.
				'</div>';
			}
		}
		else if ($row['mode'] == 'groups') {
			$maxitems = $row['maxitems'];
			$sortBy = $row['sortby'];
			// Find sort direction
			if ($row['sortdir']=='descending') {
				$sortDir = 'DESC';
			}
			else {
				$sortDir = 'ASC';
			}
			$timetype = $row['timetype'];
			if ($timetype=='always') {
				$timeSql=''; // no time managing for always
			}
			else if ($timetype=='now') {
				// Create sql for active news
				$timeSql=" and ((news.startdate is null and news.enddate is null) or (news.startdate<=now() and news.enddate>=now()) or (news.startdate<=now() and news.enddate is null) or (news.startdate is null and news.enddate>=now()))";
			}
			else {
				$count=$row['timecount'];
				if ($timetype=='interval') {
					$start = intval($row['startdate']);
					$end = intval($row['enddate']);
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
				$timeSql=" and ((news.startdate is null and news.enddate is null) or (news.startdate>=".sqlTimestamp($start)." and news.startdate<=".sqlTimestamp($end).") or (news.enddate>=".sqlTimestamp($start)." and news.enddate<=".sqlTimestamp($end).") or (news.enddate>=".sqlTimestamp($start)." and news.startdate is null) or (news.startdate<=".sqlTimestamp($end)." and news.enddate is null))";
			}
			$sql = "select distinct object.note,object.title from object,news, newsgroup_news, document_news_newsgroup where object.id=news.object_id and news.object_id=newsgroup_news.news_id and newsgroup_news.newsgroup_id=document_news_newsgroup.newsgroup_id and document_news_newsgroup.section_id=".$row['section_id'].$timeSql." order by ".$sortBy." ".$sortDir;
			$groupResult = Database::select($sql);
			while ($groupRow = Database::next($groupResult)) {
				$output.='<div class="NewsItem">'.
				'<div class="NewsTitle">'.$groupRow['title'].'</div>'.
				'<div class="NewsNote">'.$groupRow['note'].'</div>'.
				'</div>';
				$maxitems--;
				if ($maxitems==0) break;
			}
			Database::free($groupResult);
		}
		$output.='</div>';
	}
	$output.='</td>';
?>