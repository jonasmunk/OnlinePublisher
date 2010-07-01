<?
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Include/Functions.php';
require_once '../../../Include/XmlWebGui.php';
require_once '../Functions.php';
header('content-type: text/xml');
echo '<?xml version="1.0" encoding="ISO-8859-1"?>';
echo '<news>';

$mode = requestGetText("mode");
$news = requestGetNumber("news",0);
$groups = requestGetText("groups");

$maxitems = requestGetNumber("maxitems");

$sortBy = requestGetText("sortby");
$sortDir = requestGetText("sortdir");
$mode = requestGetText("mode");
$timetype = requestGetText("timetype");
$timecount = requestGetNumber("timecount",1);


if ($mode == 'single') {
	$sql="select * from object where id=".$news;
	$singleRow = Database::selectFirst($sql);
	if ($singleRow) {
		echo $singleRow['data'];
	}
}
else if ($mode == 'groups') {
	if ($sortDir=='descending') {
		$sortDir = 'DESC';
	}
	else {
		$sortDir = 'ASC';
	}
	if ($timetype=='always') {
		$timeSql=''; // no time managing for always
	}
	else if ($timetype=='now') {
		// Create sql for active news
		$timeSql=" and ((news.startdate is null and news.enddate is null) or (news.startdate<=now() and news.enddate>=now()) or (news.startdate<=now() and news.enddate is null) or (news.startdate is null and news.enddate>=now()))";
	}
	else {
		$count=$timecount;
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
	$sql = "select distinct object.data,object.title from object,news, newsgroup_news where object.id=news.object_id and news.object_id=newsgroup_news.news_id and newsgroup_news.newsgroup_id in (".$groups.")".$timeSql." order by ".$sortBy." ".$sortDir;
	
	$groupResult = Database::select($sql);
	while ($groupRow = Database::next($groupResult)) {
		echo $groupRow['data'];
		$maxitems--;
		if ($maxitems==0) break;
	}
	Database::free($groupResult);
}


echo '</news>';
?>