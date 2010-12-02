<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Preview
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Include/Functions.php';
require_once '../../../Include/XmlWebGui.php';
require_once '../../../Classes/Hierarchy.php';
require_once '../../../Classes/Request.php';
require_once '../../../Classes/Services/RenderingService.php';
require_once '../../../Classes/Services/PageService.php';
require_once '../../../Classes/InternalSession.php';
require_once '../Functions.php';

if (requestGetExists('designsession')) {
	$_SESSION['debug.design']=requestGetText('designsession');
}
if (Request::getBoolean('resetdesign')) {
	unset($_SESSION['debug.design']);
}

if (requestGetNumber('stickyDesignId')>0) {
	setStickyDesignId(requestGetNumber('stickyDesignId'));
}
$stickyDesignId = getStickyDesignId();

$pageNS = 'http://uri.in2isoft.com/onlinepublisher/publishing/page/1.0/';
$frameNS = 'http://uri.in2isoft.com/onlinepublisher/publishing/frame/1.0/';

$id = requestGetNumber('id',0);
$history = requestGetNumber('history');

if ($id==0) {
	if (InternalSession::getPageId()>0) {
		$id=InternalSession::getPageId();
	}
	else {
		$id=RenderingService::findPage('home','../../../../');
	}
}
InternalSession::setPageId($id);
$sql="select page.id,UNIX_TIMESTAMP(page.published) as published, page.description,page.language,page.keywords,page.title,page.dynamic,page.next_page,page.previous_page,".
"template.unique,frame.id as frameid,frame.title as frametitle,frame.data as framedata,frame.dynamic as framedynamic,".
" design.parameters,".
"design.`unique` as design, hierarchy.id as hierarchy".
" from page,template,frame,design,hierarchy".
" where page.frame_id=frame.id and page.template_id=template.id".
($stickyDesignId>0
? " and design.object_id=".$stickyDesignId
: " and page.design_id=design.object_id"
).
" and frame.hierarchy_id=hierarchy.id and page.id=".$id;
$result = Database::select($sql);
if ($row = Database::next($result)) {
	$agent = $_SERVER['HTTP_USER_AGENT'];
	$template = $row['unique'];
	$id = $row['id'];
	if ($history>0) {
		$sql = "select data from page_history where id=".$history;
		if ($hist = Database::selectFirst($sql)) {
			$data = $hist['data'];
		} else {
			$data = PageService::getPagePreview($id,$template);
		}
	} else {
		$data = PageService::getPagePreview($id,$template);
	}
		
	$stuff = RenderingService::applyContentDynamism($id,$template,$data);
	$data = $stuff['data'];
	
	$framedata = $row['framedata'];
	if ($row['framedynamic']) {
		$framedata = RenderingService::applyFrameDynamism($row['frameid'],$framedata);
	}
	$design = $row['design'];
	if (requestGetExists('design')) {
		$design = requestGetText('design');
	}
	else if (isset($_SESSION['debug.design'])) {
		$design = $_SESSION['debug.design'];
	}
	$xml = '<?xml version="1.0" encoding="ISO-8859-1"?>'.
	'<page xmlns="'.$pageNS.'" id="'.$row['id'].'" title="'.encodeXML($row['title']).'">'.
	'<meta>'.
	'<description>'.encodeXML($row['description']).'</description>'.
	'<keywords>'.encodeXML($row['keywords']).'</keywords>'.
	RenderingService::buildDateTag('published',$row['published']).
	'<language>'.encodeXML(strtolower($row['language'])).'</language>'.
	'</meta>'.
		'<design>'.
		$row['parameters'].
		'</design>'.
	RenderingService::buildPageContext($id,$row['next_page'],$row['previous_page']).
	'<frame xmlns="'.$frameNS.'" title="'.encodeXML($row['frametitle']).'">'.
	Hierarchy::build($row['hierarchy']).
	$framedata.
	'</frame>'.
	'<content>'.
	$data.
	'</content>'.
	'</page>';
	if (requestGetBoolean('viewsource')) {
		header('Content-type: text/xml');
		echo $xml;
	}
	else {
		$html = RenderingService::applyStylesheet($xml,$design,$template,'../../../../','../../../../','','?id='.$id.'&amp;',true);
		header("Content-Type: text/html; charset=UTF-8");
		echo $html;
	}
}
else {
	echo 'Page not found!';
}
Database::free($result);
?>