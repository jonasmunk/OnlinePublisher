<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once 'NewsController.php';

$group = NewsController::getGroupId();

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<group xmlns="uri:Icon" width="100%" spacing="12" size="2" cellwidth="17%" >'.
'<row>';

$counter=0;

$sql="select object.id,object.title,date_format(news.startdate,'%d-%m-%Y %T') as startdate,date_format(news.startdate,'%Y%m%d%h%i%s') as startdateindex,date_format(news.enddate,'%d-%m-%Y %T') as enddate,date_format(news.enddate,'%Y%m%d%h%i%s') as enddateindex,enddate-now() as enddatedelta,startdate-now() as startdatedelta";
if ($group>0) {
	$sql.=" from object,news,newsgroup_news where object.type='news' and object.id=news.object_id and newsgroup_news.news_id=object.id and newsgroup_news.newsgroup_id=$group order by title";
}
else {
	$sql.=" FROM object,news WHERE object.type='news' and object.id=news.object_id order by title;";
}
$result = Database::select($sql);
while ($row = Database::next($result)) {
	if ($row['startdatedelta']>0 || $row['enddatedelta']<0) {
		$style="Disabled";
	}
	else {
		$style="Standard";
	}
	$counter++;
	if ($counter==7) {
		$gui.='</row><row>';
		$counter=1;
	}
	$gui.=
	'<icon title="'.encodeXML(shortenString($row['title'],20)).'" icon="Part/News" link="NewsProperties.php?id='.$row['id'].'" target="_parent" style="'.$style.'"/>';
}
Database::free($result);


$gui.=
'</row>'.
'</group>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Icon");
writeGui($xwg_skin,$elements,$gui);
?>