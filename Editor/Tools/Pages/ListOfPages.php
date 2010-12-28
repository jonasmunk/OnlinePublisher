<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Include/Session.php';
require_once '../../Classes/Services/TemplateService.php';
require_once 'PagesController.php';

if (requestGetExists('tab')) {
	setToolSessionVar('pages','listTab',requestGetText('tab'));
}
$extended = (getToolSessionVar('pages','listTab') == 'extended');
$pair = PagesController::getSearchPair();
$freeText = getToolSessionVar('pages','freeTextSearch');
if ($freeText!='' && $freeText!=NULL) {
	$freeTextSql=" and (page.title like ".Database::search($freeText)." or page.`index` like ".Database::search($freeText)." or page.description like ".Database::search($freeText)." or page.keywords like ".Database::search($freeText).")";
}
else {
	$freeTextSql="";
}

if ($pair[0]=='noHierarchyItem') {
	$sql="select page.id,page.secure,page.title,template.unique,date_format(page.changed,'%d-%m-%Y %T') as changed,date_format(page.changed,'%Y%m%d%h%i%s') as changedindex,(page.changed-page.published) as publishdelta,design.unique as design,frame.name as frame,0 as hieritems,page.language from page,template,design,frame left join hierarchy_item on page.id=target_id where hierarchy_item.id is null and page.template_id=template.id and page.design_id=design.object_id and page.frame_id=frame.id".$freeTextSql." order by title";
}
elseif ($pair[0]=='noSecurityZone') {
	$sql="select page.id,page.secure,page.title,template.unique,date_format(page.changed,'%d-%m-%Y %T') as changed,date_format(page.changed,'%Y%m%d%h%i%s') as changedindex,(page.changed-page.published) as publishdelta,design.unique as design,frame.name as frame,0 as hieritems,page.language from page,template,design,frame left join securityzone_page on page.id= securityzone_page.page_id where securityzone_page.securityzone_id is null and page.template_id=template.id and page.design_id=design.object_id and page.frame_id=frame.id".$freeTextSql." order by title";
}
elseif ($pair[0]=='securityZone') {
	$sql="select page.id,page.secure,page.title,template.unique,date_format(page.changed,'%d-%m-%Y %T') as changed,date_format(page.changed,'%Y%m%d%h%i%s') as changedindex,(page.changed-page.published) as publishdelta,design.unique as design,frame.name as frame,0 as hieritems,page.language from page,template,design,frame left join securityzone_page on page.id= securityzone_page.page_id where securityzone_page.securityzone_id=".$pair[1]." and page.template_id=template.id and page.design_id=design.object_id and page.frame_id=frame.id".$freeTextSql." order by title";
}
elseif ($pair[0]=='hierarchy') {
	$sql="select page.id,page.secure,page.title,template.unique,date_format(page.changed,'%d-%m-%Y %T') as changed,date_format(page.changed,'%Y%m%d%h%i%s') as changedindex,(page.changed-page.published) as publishdelta,design.unique as design,frame.name as frame,count(hierarchy_item.id) as hieritems,page.language from page,template,design,frame left join hierarchy_item on page.id=target_id where hierarchy_item.hierarchy_id=".$pair[1]." and page.template_id=template.id and page.design_id=design.object_id and page.frame_id=frame.id".$freeTextSql." group by page.id order by title";
	$sql="select * from page join template on page.template_id=template.id join design on page.design_id=design.object_id join frame on page.frame_id=frame.id left join hierarchy_item on target_id=page.id where hierarchy_item.hierarchy_id=".$pair[1].$freeTextSql." group by page.id order by page.title"
	$sql = "select page.id,page.secure,page.title,template.unique,date_format(page.changed,'%d-%m-%Y %T') as changed,date_format(page.changed,'%Y%m%d%h%i%s') as changedindex,(page.changed-page.published) as publishdelta,design.unique as design,frame.name as frame,count(hierarchy_item.id) as hieritems,page.language from page join template on page.template_id=template.id join design on page.design_id=design.object_id join frame on page.frame_id=frame.id left join hierarchy_item on target_id=page.id where hierarchy_item.hierarchy_id=".$pair[1].$freeTextSql." group by page.id order by page.title";
}
elseif ($pair[0]=='template') {
	$sql="select page.id,page.secure,page.title,template.unique,date_format(page.changed,'%d-%m-%Y %T') as changed,date_format(page.changed,'%Y%m%d%h%i%s') as changedindex,(page.changed-page.published) as publishdelta,design.unique as design,frame.name as frame,count(hierarchy_item.id) as hieritems,page.language from page,template,design,frame left join hierarchy_item on page.id=target_id where template.id=".$pair[1]." and page.template_id=template.id and page.design_id=design.object_id and page.frame_id=frame.id".$freeTextSql." group by page.id order by title";
}
elseif ($pair[0]=='frame') {
	$sql="select page.id,page.secure,page.title,template.unique,date_format(page.changed,'%d-%m-%Y %T') as changed,date_format(page.changed,'%Y%m%d%h%i%s') as changedindex,(page.changed-page.published) as publishdelta,design.unique as design,frame.name as frame,count(hierarchy_item.id) as hieritems,page.language from page,template,design,frame left join hierarchy_item on page.id=target_id where frame.id=".$pair[1]." and page.template_id=template.id and page.design_id=design.object_id and page.frame_id=frame.id".$freeTextSql." group by page.id order by title";
}
else {
	$sql="select page.id,page.secure,page.title,template.unique,date_format(page.changed,'%d-%m-%Y %T') as changed,date_format(page.changed,'%Y%m%d%h%i%s') as changedindex,(page.changed-page.published) as publishdelta,design.unique as design,frame.name as frame,count(hierarchy_item.id) as hieritems,page.language from page,template,design,frame left join hierarchy_item on `page`.`id`=target_id where page.template_id=template.id and page.design_id=design.object_id and page.frame_id=frame.id".$freeTextSql." group by page.id order by title";
}
$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<list xmlns="uri:List" width="100%" margin="3" sort="true">'.
'<tabgroup>'.
'<tab title="Simpel"'.(!$extended ? ' style="Hilited"' : ' link="ListOfPages.php?tab=standard"').' help="Viser færre informationer i listen"/>'.
'<tab title="Udvidet"'.($extended ? ' style="Hilited"' : ' link="ListOfPages.php?tab=extended"').' help="Viser flere informationer i listen"/>'.
'</tabgroup>'.
'<content>'.
'<headergroup>'.
'<header title="Titel" width="100%"/>'.
($extended ?
'<header title="Skabelon" help="Angiver den skabelon siden følger"/>'.
'<header title="Design" help="Angiver sidens design"/>'.
'<header title="Ramme" help="Angiver sidens omsluttende ramme"/>'.
'<header title="Sprog" help="Angiver sidens primære sprog"/>'
: ''
).
'<header title="&#198;ndret" type="number" width="1%" nowrap="true" help="Angiver hvornår siden sidst blev ændret"/>'.
'<header title="" width="1%"/>'.
'</headergroup>';
$templates = TemplateService::getTemplatesKeyed();
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$gui.='<row link="EditPage.php?id='.$row['id'].'" target="_parent">'.
	'<cell>'.
	'<icon size="1" icon="'.$templates[$row['unique']]['icon'].'"/>'.
	'<text>'.encodeXML($row['title']).'</text>'.
	($row['secure']==1 ? '<status type="Locked" link="EditPageSecurity.php?id='.$row['id'].'" help="Siden er beskyttet, klik for at ændre opsætning"/>' : '').
	'</cell>'.
	($extended ?
	'<cell>'.
	encodeXML($templates[$row['unique']]['name']).
	'</cell>'.
	'<cell>'.encodeXML($row['design']).'</cell>'.
	'<cell>'.encodeXML($row['frame']).'</cell>'.
	'<cell index="'.$row['language'].'">'.xwgBuildListLanguageIcon($row['language']).'</cell>'
	: '').
	'<cell index="'.$row['changedindex'].'">'.
	'<text>'.encodeXML($row['changed']).'</text>';
	if ($row['publishdelta']>0) {
		$gui.='<status type="Attention" help="Siden er ændret siden sidste udgivning"/>';
	}
	$gui.=
	'</cell>'.
	'<cell>'.
	'<icon size="1" icon="Basic/Edit" link="../../Template/Edit.php?id='.$row['id'].'" target="Desktop" help="Rediger siden"/>'.
	'<icon size="1" icon="Basic/View" link="../../Services/Preview/?id='.$row['id'].'&amp;return=Tools/Pages/" target="Desktop" help="Se siden"/>'.
	'<icon size="1" icon="Basic/Info" help="Sidens egenskaber"/>'.
	($extended && $row['hieritems']>0 ? '<icon icon="Element/Structure" help="Siden har '.$row['hieritems'].' menupunkt der peger derpå"/>' : '').
	'</cell>'.
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