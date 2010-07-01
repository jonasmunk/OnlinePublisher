<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<list xmlns="uri:List" width="100%" margin="3" sort="true">'.
'<content>'.
'<headergroup>'.
'<header title="Titel" width="40%"/>'.
'<header title="Beskrivelse" width="50%"/>'.
'<header title="Antal" width="10%" align="center" type="number"/>'.
'</headergroup>';

$sql="select distinct object.id,object.title,object.note,count(news.object_id) as newscount from newsgroup, newsgroup_news, news,object  where newsgroup_news.newsgroup_id=newsgroup.object_id and newsgroup_news.news_id = news.object_id and object.id=newsgroup.object_id group by newsgroup.object_id union select object.id,object.title,object.note,'0' from object left join newsgroup_news on newsgroup_news.newsgroup_id=object.id where object.type='newsgroup' and newsgroup_news.news_id is null order by title";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$gui.='<row link="Group.php?id='.$row['id'].'" target="_parent">'.
	'<cell>'.
	'<icon size="1" icon="Element/Folder"/>'.
	'<text>'.encodeXML($row['title']).'</text>'.
	'</cell>'.
	'<cell>'.encodeXML($row['note']).'</cell>'.
	'<cell>'.encodeXML($row['newscount']).'</cell>'.
	'</row>';
}
Database::free($result);

$gui.=
'</content>'.
'</list>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("List");
writeGui($xwg_skin,$elements,$gui);
?>