<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/InternalSession.php';
require_once '../../Classes/Services/TemplateService.php';
require_once 'PagesController.php';

PagesController::setGroupView(requestGetText('groupView'));
PagesController::setViewDetails(requestGetText('viewDetails'));

$groupView = PagesController::getGroupView();
$viewDetails = PagesController::getViewDetails();

$freeText = InternalSession::getToolSessionVar('pages','freeTextSearch');
if ($freeText!='' && $freeText!=NULL) {
	$freeTextSql=" (page.title like ".Database::search($freeText)." or page.`index` like ".Database::search($freeText)." or page.description like ".Database::search($freeText)." or page.keywords like ".Database::search($freeText).")";
} else {
    $freeTextSql='';
}

$gui='<xmlwebgui xmlns="uri:XmlWebGui">'.
'<configuration path="../../../"/>'.
'<interface>'.
'<result xmlns="uri:Result">'.
'<sidebar>'.
'<block title="Grupper efter">'.
'<selection value="'.$groupView.'" object="GroupView">'.
'<item title="Intet" value="none"/>'.
'<item title="Hierarki" value="hierarchy"/>'.
'<item title="Skabelon" value="template"/>'.
'<item title="Design" value="design"/>'.
'<item title="Ramme" value="frame"/>'.
'<item title="Beskyttet zone" value="securityzone"/>'.
'</selection>'.
'</block>'.
'<block title="Visning">'.
'<selection object="ViewDetails" value="'.$viewDetails.'">'.
'<item title="Simpel" value="simple"/>'.
'<item title="Udvidet" value="extended"/>'.
'</selection>'.
'</block>'.
'</sidebar>'.
'<content>';
if ($groupView=='none') {
    buildContent($gui,$freeTextSql,$viewDetails);
} elseif ($groupView=='hierarchy') {
    buildContentHierarchy($gui,$freeTextSql,$viewDetails);
} elseif ($groupView=='template') {
    buildContentTemplate($gui,$freeTextSql,$viewDetails);
} elseif ($groupView=='design') {
    buildContentDesign($gui,$freeTextSql,$viewDetails);
} elseif ($groupView=='frame') {
    buildContentFrame($gui,$freeTextSql,$viewDetails);
} elseif ($groupView=='securityzone') {
    buildContentSecurityZone($gui,$freeTextSql,$viewDetails);
}
$gui.=
'</content>'.
'</result>'.
'<script xmlns="uri:Script">
var viewDelegate = {
	valueDidChange : function(event,obj) {
		document.location = "Result.php?groupView="+
		                    GroupView.getValue()+
		                    "&amp;viewDetails="+
		                    ViewDetails.getValue();
	}
}
GroupView.setDelegate(viewDelegate);
ViewDetails.setDelegate(viewDelegate);
</script>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Result","List","Script");
writeGui($xwg_skin,$elements,$gui);

function buildContent(&$gui,$freeTextSql,$detail) {
    $templates = TemplateService::getTemplatesKeyed();
	$gui.=
	'<group title="Sider">'.
	'<list xmlns="uri:List" width="100%" variant="Light" sort="true">'.
	'<content>';
    buildHeaders($gui,$detail);
	$sql="select page.id,page.secure,page.title,template.unique,date_format(page.changed,'%d/%m-%Y') as changed,date_format(page.changed,'%Y%m%d%h%i%s') as changedindex,(page.changed-page.published) as publishdelta,page.language from page,template where page.template_id=template.id".($freeTextSql!="" ? " and ".$freeTextSql : "")." order by title";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
	    buildRow($gui,$row,$templates,$detail);
	}
	Database::free($result);
	$gui.=
	'</content>'.
	'</list>'.
	'</group>';
}

function buildContentHierarchy(&$gui,$freeTextSql,$detail) {
    $templates = TemplateService::getTemplatesKeyed();
    $current = -1;
    $count = null;
	$sql="select distinct page.id,page.secure,page.title,template.unique,date_format(page.changed,'%d/%m-%Y') as changed,date_format(page.changed,'%Y%m%d%h%i%s') as changedindex,(page.changed-page.published) as publishdelta,design.unique as design,page.language,hierarchy.name as hierarchy,hierarchy.id as hierarchy_id from page join template on page.template_id=template.id join design on page.design_id=design.object_id left join hierarchy_item on hierarchy_item.target_id=page.id left join hierarchy on hierarchy_item.hierarchy_id=hierarchy.id".($freeTextSql!="" ? " where ".$freeTextSql : "")." order by hierarchy,title";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
	    if ($current!=$row['hierarchy_id']) {
	        if ($count>0) {
	            $gui.='</content></list></group>';
            }
	        $gui.=
            '<group title="'.($row['hierarchy_id']>0 ? encodeXML($row['hierarchy']) : 'Intet hierarki').'">'.
            '<list xmlns="uri:List" width="100%" variant="Light" sort="true">'.
            '<content>';
            buildHeaders($gui,$detail);
	        $current = $row['hierarchy_id'];
	        $count++;
	    }
	    buildRow($gui,$row,$templates,$detail);
	}
	Database::free($result);
    if ($count>0) {
        $gui.='</content></list></group>';
    }
}

function buildContentTemplate(&$gui,$freeTextSql,$detail) {
    $templates = TemplateService::getTemplatesKeyed();
    $current = '';
    $count = null;
    $sql="select distinct page.id,page.secure,page.title,template.unique,date_format(page.changed,'%d/%m-%Y') as changed,date_format(page.changed,'%Y%m%d%h%i%s') as changedindex,(page.changed-page.published) as publishdelta,design.unique as design,page.language from page,template,design where page.template_id=template.id and page.design_id=design.object_id".($freeTextSql!="" ? " and ".$freeTextSql : "")." order by `unique`,title";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
	    if ($current!=$row['unique']) {
	        if ($count>0) {
	            $gui.='</content></list></group>';
            }
	        $gui.=
            '<group title="'.encodeXML($templates[$row['unique']]['name']).'">'.
            '<list xmlns="uri:List" width="100%" variant="Light" sort="true">'.
            '<content>';
            buildHeaders($gui,$detail);
	        $current = $row['unique'];
	        $count++;
	    }
	    buildRow($gui,$row,$templates,$detail);
	}
	Database::free($result);
    if ($count>0) {
        $gui.='</content></list></group>';
    }
}

function buildContentDesign(&$gui,$freeTextSql,$detail) {
    $templates = TemplateService::getTemplatesKeyed();
    $current = '';
    $count = null;
    $sql="select distinct page.id,page.secure,page.title,template.unique,date_format(page.changed,'%d/%m-%Y') as changed,date_format(page.changed,'%Y%m%d%h%i%s') as changedindex,(page.changed-page.published) as publishdelta,object.title as design,page.language from page,template,object where page.template_id=template.id and page.design_id=object.id".($freeTextSql!="" ? " and ".$freeTextSql : "")." order by design,title";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
	    if ($current!=$row['design']) {
	        if ($count>0) {
	            $gui.='</content></list></group>';
            }
	        $gui.=
            '<group title="'.encodeXML($row['design']).'">'.
            '<list xmlns="uri:List" width="100%" variant="Light" sort="true">'.
            '<content>';
            buildHeaders($gui,$detail);
	        $current = $row['design'];
	        $count++;
	    }
	    buildRow($gui,$row,$templates,$detail);
	}
	Database::free($result);
    if ($count>0) {
        $gui.='</content></list></group>';
    }
}

function buildContentFrame(&$gui,$freeTextSql,$detail) {
    $templates = TemplateService::getTemplatesKeyed();
    $current = '';
    $count = null;
    $sql="select distinct page.id,page.secure,page.title,template.unique,date_format(page.changed,'%d/%m-%Y') as changed,date_format(page.changed,'%Y%m%d%h%i%s') as changedindex,(page.changed-page.published) as publishdelta,frame.name as frame,page.language from page,template,frame where page.template_id=template.id and page.frame_id=frame.id".($freeTextSql!="" ? " and ".$freeTextSql : "")." order by frame,title";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
	    if ($current!=$row['frame']) {
	        if ($count>0) {
	            $gui.='</content></list></group>';
            }
	        $gui.=
            '<group title="'.encodeXML($row['frame']).'">'.
            '<list xmlns="uri:List" width="100%" variant="Light" sort="true">'.
            '<content>';
            buildHeaders($gui,$detail);
	        $current = $row['frame'];
	        $count++;
	    }
	    buildRow($gui,$row,$templates,$detail);
	}
	Database::free($result);
    if ($count>0) {
        $gui.='</content></list></group>';
    }
}

function buildContentSecurityZone(&$gui,$freeTextSql,$detail) {
    $templates = TemplateService::getTemplatesKeyed();
    $current = -1;
    $count = null;
	$sql = "select page.id,page.secure,page.title,template.unique,date_format(page.changed,'%d/%m-%Y') as changed,date_format(page.changed,'%Y%m%d%h%i%s') as changedindex,(page.changed-page.published) as publishdelta,design.unique as design,page.language,object.title as securityzone,object.id as securityzone_id from page join template on page.template_id=template.id join design on page.design_id=design.object_id left join securityzone_page on securityzone_page.page_id=page.id left join object on securityzone_page.securityzone_id=object.id ".($freeTextSql!="" ? " where ".$freeTextSql : "")." order by securityzone,title";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
	    if ($current!=$row['securityzone_id']) {
	        if ($count>0) {
	            $gui.='</content></list></group>';
            }
	        $gui.=
            '<group title="'.($row['securityzone_id']>0 ? encodeXML($row['securityzone']) : 'Ingen beskyttet zone').'">'.
            '<list xmlns="uri:List" width="100%" variant="Light" sort="true">'.
            '<content>';
            buildHeaders($gui,$detail);
	        $current = $row['securityzone_id'];
	        $count++;
	    }
	    buildRow($gui,$row,$templates,$detail);
	}
	Database::free($result);
    if ($count>0) {
        $gui.='</content></list></group>';
    }
}

// Secur
// 

function buildHeaders(&$gui,$detail) {
    if ($detail=='simple') {
        $gui.=
        '<headergroup>'.
        '<header title="Side" width="75%"/>'.
        '<header title="Ændret" width="25%"/>'.
        '<header title="" width="1%"/>'.
        '</headergroup>';
    } else {
        $gui.=
        '<headergroup>'.
        '<header title="Side" width="50%"/>'.
        '<header title="Skabelon" width="25%"/>'.
        '<header title="Ændret" width="25%"/>'.
        '<header title="" width="1%"/>'.
        '</headergroup>';
    }
}


function buildRow(&$gui,&$row,&$templates,$detail='simple') {
    $gui.='<row link="EditPage.php?id='.$row['id'].'" target="_parent">'.
	'<cell>'.
	'<icon size="1" icon="'.$templates[$row['unique']]['icon'].'"/>'.
	'<text>'.encodeXML($row['title']).'</text>'.
	xwgBuildListLanguageIcon($row['language']).
	($row['secure']==1 ? '<status type="Locked" link="EditPageSecurity.php?id='.$row['id'].'" help="Siden er beskyttet, klik for at ændre opsætning"/>' : '').
	'</cell>'.
	($detail=='extended' ?
	'<cell>'.
	encodeXML($templates[$row['unique']]['name']).
	'</cell>'//.
//	'<cell>'.encodeXML($row['design']).'</cell>'.
//	'<cell>'.encodeXML($row['frame']).'</cell>'.
//	'<cell index="'.$row['language'].'">'.xwgBuildListLanguageIcon($row['language']).'</cell>'
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
//	($extended && $row['hieritems']>0 ? '<icon icon="Element/Structure" help="Siden har '.$row['hieritems'].' menupunkt der peger derpå"/>' : '').
	'</cell>'.
	'</row>';
} 
?>