<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Services/FileService.php';
require_once '../../Classes/Request.php';

$id = Request::getInt('id',0);
$return = Request::getString('return');

$sql="select * from frame where id=".$id;
$row = Database::selectFirst($sql);
$data='';
$dynamic=0;
if ($row['searchenabled']) {
	$data.='<search page="'.$row['searchpage_id'].'">'.
	'<button title="'.StringUtils::escapeXML($row['searchbuttontitle']).'"/>'.
	'<types>'.
	($row['searchpages'] ? '<type unique="page"/>' : '').
	($row['searchimages'] ? '<type unique="image"/>' : '').
	($row['searchfiles'] ? '<type unique="file"/>' : '').
	($row['searchnews'] ? '<type unique="news"/>' : '').
	($row['searchpersons'] ? '<type unique="person"/>' : '').
	($row['searchproducts'] ? '<type unique="product"/>' : '').
	'</types>'.
	'</search>';
}
if ($row['userstatusenabled']) {
	$data.='<userstatus page="'.$row['userstatuspage_id'].'"/>';
}
$data.=
'<text>'.
'<bottom>'.StringUtils::insertEmailLinks(StringUtils::escapeXML($row['bottomtext']),'link','email','').'</bottom>'.
'</text>'.
'<links>'.
'<top>'.
buildLinks($id,'top').
'</top>'.
'<bottom>'.
buildLinks($id,'bottom').
'</bottom>'.
'</links>';
$news=buildNews($id);
$data.=$news;
if (strlen($news)>0) {
	$dynamic=1;
}
$sql="update frame set data=".Database::text($data).",published=now(),dynamic=".$dynamic." where id=".$id;
Database::update($sql);


if ($return=='links') {
	redirect('EditFrameLinks.php?id='.$id.'&position='.Request::getString('position'));
}
else if ($return=='search') {
	redirect('EditFrameSearch.php?id='.$id);
}
else if ($return=='news') {
	redirect('FrameNews.php?id='.$id);
}
else if ($return=='userstatus') {
	redirect('EditFrameUserstatus.php?id='.$id);
}
else {
	redirect('EditFrame.php?id='.$id);
}

function buildLinks($id,$position) {
	$out='';
	$sql="select * from frame_link where position='".$position."' and frame_id=".$id." order by `index`";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$out.='<link title="'.StringUtils::escapeXML($row['title']).'" alternative="'.StringUtils::escapeXML($row['alternative']).'"';
		if ($row['target_type']=='page') {
			$out.=' page="'.$row['target_id'].'"';
		}
		else if ($row['target_type']=='file') {
			$out.=' file="'.$row['target_id'].'" filename="'.StringUtils::escapeXML(FileService::getFileFilename($row['target_id'])).'"';
		}
		else if ($row['target_type']=='url') {
			$out.=' url="'.StringUtils::escapeXML($row['target_value']).'"';
		}
		else if ($row['target_type']=='email') {
			$out.=' email="'.StringUtils::escapeXML($row['target_value']).'"';
		}
		$out.='/>';
	}
	Database::free($result);
	return $out;
}

function buildNews($id) {
	$out='';
	$sql="select * from frame_newsblock where frame_id=".$id." order by `index`";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$out.='<newsblock title="'.StringUtils::escapeXML($row['title']).'">'.
		'<!--newsblock#'.$row['id'].'-->'.
		'</newsblock>';
	}
	Database::free($result);
	return $out;
}
?>