<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<hierarchy xmlns="uri:Hierarchy" persistence="true" unique="tools-news-hierarchy">'.

$gui.='<element icon="Tool/News" title="Bibliotek" link="Library.php" target="Right">';
	
$sql="SELECT object.* FROM object WHERE type='news' order by title";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$gui.='<element icon="Part/News" title="'.encodeXML($row['title']).'" link="NewsProperties.php?id='.$row['id'].'&amp;group=0" target="Right"/>';
}
Database::free($result);

$gui.='</element>';

$gui.='<element icon="Element/Folders" title="Grupper" link="Groups.php" target="Right">';

$sql="select * from object where type='newsgroup' order by title";
$result = Database::select($sql); 
while ($row = Database::next($result)) {
	$gui.='<element icon="Element/Folder" title="'.encodeXML($row['title']).'" link="Group.php?id='.$row['id'].'" target="Right">';
	
	$sql="select object.* from object,newsgroup_news where object.type='news' and newsgroup_news.news_id=object.id and newsgroup_news.newsgroup_id=".$row['id']." order by title";
	$result_news = Database::select($sql);
	while ($news = Database::next($result_news)) {
		$gui.='<element icon="Part/News" title="'.encodeXML($news['title']).'" link="NewsProperties.php?id='.$news['id'].'&amp;group='.$row['id'].'" target="Right"/>';

	}
	$gui.='</element>';
	Database::free($result_news);
}
Database::free($result);


$gui.='<element icon="Basic/Add" title="Ny gruppe" link="NewGroup.php" target="Right"/>';
$gui.='</element>';

$gui.=
'</hierarchy>'.
'<refresh xmlns="uri:Script" source="HierarchyUpdateCheck.php" interval="3000"/>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Hierarchy","Script");
writeGui($xwg_skin,$elements,$gui);
?>