<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/UserInterface.php';
require_once 'NewsController.php';

NewsController::setListGrouping(requestGetText('grouping'));
NewsController::setListState(requestGetText('state'));
$grouping = NewsController::getListGrouping();
$state = NewsController::getListState();

$group = NewsController::getGroupId();
$isGroup = ($group>0);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<form xmlns="uri:Form" action="RemoveFromGroup.php" method="post">'.
'<result xmlns="uri:Result">'.
'<sidebar>'.
'<block title="Gruppering">'.
'<selection value="'.$grouping.'" object="Group">'.
'<item title="Ingen" value="none"/>'.
//'<item title="Efter dato" value="date"/>'.
'<item title="Efter status" value="state"/>'.
'</selection>'.
'</block>'.
'<block title="Status">'.
'<selection value="'.$state.'" object="State">'.
'<item title="Vis alle" value="all"/>'.
'<item title="Vis kun aktive" value="onlyactive"/>'.
'<item title="Vis kun inaktive" value="onlyinactive"/>'.
'</selection>'.
'</block>'.
/*'<block title="Oversigt">'.
'<selection value="simple" object="State">'.
'<item title="Simpel" value="simple"/>'.
'<item title="Avanceret" value="advanced"/>'.
'</selection>'.
'</block>'.*/
'</sidebar>'.
'<content>';

$sql="select object.id,object.title,object.note,UNIX_TIMESTAMP(news.startdate) as startdate,date_format(news.startdate,'%Y%m%d%h%i%s') as startdateindex,UNIX_TIMESTAMP(news.enddate) as enddate,date_format(news.enddate,'%Y%m%d%h%i%s') as enddateindex,enddate-now() as enddatedelta,startdate-now() as startdatedelta";
if ($isGroup) {
	$sql.=" from object,news,newsgroup_news where object.id=news.object_id and newsgroup_news.news_id=object.id and newsgroup_news.newsgroup_id=".$group;
}
else {
	$sql.=" FROM news LEFT JOIN object ON object.id=news.object_id WHERE object.type='news'";
}
if ($state=='onlyactive') {
	$sql.=" and ((startdate is null and enddate is null) or (startdate<now() and enddate>now()) or (startdate is null and enddate>now()) or (startdate<now() and enddate is null))";
} else if ($state=='onlyinactive') {
	$sql.=" and not ((startdate is null and enddate is null) or (startdate<now() and enddate>now()) or (startdate is null and enddate>now()) or (startdate<now() and enddate is null))";
}
$sql.=" order by title";
$result = Database::select($sql);
if ($grouping=='none') {
	$gui.=groupByNone($result,$isGroup);
} elseif ($grouping=='state') {
	$gui.=groupByState($result,$isGroup);
}
Database::free($result);

$gui.=
'</content>'.
'</result>'.
'</form>'.
'<script xmlns="uri:Script">
var sideBarDelegate = {
	valueDidChange : function(event,obj) {
		document.location = "NewsList.php?grouping="+Group.getValue()+"&amp;state="+State.getValue();
	}
}
Group.setDelegate(sideBarDelegate);
State.setDelegate(sideBarDelegate);
</script>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("List","Form","Result","Script");
writeGui($xwg_skin,$elements,$gui);


//	
function groupByNone(&$result,$isGroup) {
	$gui=buildListHeader($isGroup);
	while ($row = Database::next($result)) {
		$gui.=buildRow($row,$isGroup);
	}
	return $gui.buildListFooter();
}

function groupByState(&$result,$isGroup) {
	$future='';
	$present='';
	$past='';
	while ($row = Database::next($result)) {
		if ($row['startdatedelta']>0) {
			$future.=buildRow($row,$isGroup,false);
		} elseif ($row['enddatedelta']<0) {
			$past.=buildRow($row,$isGroup,false);
		} else {
			$present.=buildRow($row,$isGroup,false);
		}
	}
	$gui=
	'<group title="Aktive" open="'.(NewsController::getGroupingOpen('present') ? 'true' : 'false').'" close-action="ToggleGrouping.php?type=present&amp;open=false" open-action="ToggleGrouping.php?type=present&amp;open=true">'.
	buildListHeader($isGroup,false).
	$present.
	buildListFooter().
	'</group>'.
	'<group title="Kommende" open="'.(NewsController::getGroupingOpen('future') ? 'true' : 'false').'" close-action="ToggleGrouping.php?type=future&amp;open=false" open-action="ToggleGrouping.php?type=future&amp;open=true">'.
	buildListHeader($isGroup,false).
	$future.
	buildListFooter().
	'</group>'.
	'<group title="Afsluttede" open="'.(NewsController::getGroupingOpen('past') ? 'true' : 'false').'" close-action="ToggleGrouping.php?type=past&amp;open=false" open-action="ToggleGrouping.php?type=past&amp;open=true">'.
	buildListHeader($isGroup,false).
	$past.
	buildListFooter().
	'</group>';
	
	return $gui;
}

function buildListHeader($isGroup,$showStatus=true) {
	return
	'<list xmlns="uri:List" width="100%" margin="6" sort="true" variant="Light">'.
	'<content>'.
	'<headergroup>'.
	($isGroup ? '<header width="1%"/>' : '').
	'<header title="Titel" width="50%"/>'.
	'<header title="Start" width="15%" nowrap="true" type="number"/>'.
	'<header title="Slut" width="15%" nowrap="true" type="number"/>'.
	($showStatus ? '<header title="Status" width="5%" align="center" type="number"/>' : '').
	'</headergroup>';
}

function buildListFooter() {
	return '</content>'.
	'</list>';
}

function buildRow(&$row,$isGroup,$showStatus=true) {
	if ($showStatus) {
		if ($row['startdatedelta']>0) {
			$style="Disabled";
			$status="Active";
			$index=2;
		}
		else if ($row['enddatedelta']<0) {
			$style="Disabled";
			$status="Stopped";
			$index=3;
		}
		else {
			$style="Standard";
			$status="Finished";
			$index=1;
		}
	} else {
		$style="Standard";
	}
	$gui='<row link="NewsProperties.php?id='.$row['id'].'" target="_parent" style="'.$style.'">'.
	($isGroup ? '<cell><checkbox name="news[]" value="'.$row['id'].'"/></cell>' : '').
	'<cell>'.
	'<icon size="1" icon="Part/News"/>'.
	'<text><strong>'.encodeXML(shortenString($row['title'],30)).'</strong><break/>'.encodeXML($row['note']).'</text>'.
	'</cell>'.
	'<cell index="'.$row['startdateindex'].'">'.encodeXML(UserInterface::presentLongDateTime($row['startdate'])).'</cell>'.
	'<cell index="'.$row['enddateindex'].'">'.encodeXML(UserInterface::presentLongDateTime($row['enddate'])).'</cell>'.
	($showStatus ? '<cell index="'.$index.'"><status type="'.$status.'"/></cell>' : '').
	'</row>';
	return $gui;
}
?>