<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';

$id = requestGetNumber('id',0);
$return = requestGetText('return');

$sql="select * from frame where id=".$id;
$row = Database::selectFirst($sql);
$data='';
$dynamic=0;
if ($row['searchenabled']) {
	$data.='<search page="'.$row['searchpage_id'].'">'.
	'<button title="'.encodeXML($row['searchbuttontitle']).'"/>'.
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
'<bottom>'.insertEmailLinks(encodeXML($row['bottomtext']),'link','email','').'</bottom>'.
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
$sql="update frame set data=".sqlText($data).",published=now(),dynamic=".$dynamic." where id=".$id;
Database::update($sql);


if ($return=='links') {
	redirect('EditFrameLinks.php?id='.$id.'&position='.requestGetText('position'));
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
		$out.='<link title="'.encodeXML($row['title']).'" alternative="'.encodeXML($row['alternative']).'"';
		if ($row['target_type']=='page') {
			$out.=' page="'.$row['target_id'].'"';
		}
		else if ($row['target_type']=='file') {
			$out.=' file="'.$row['target_id'].'" filename="'.encodeXML(getFileFilename($row['target_id'])).'"';
		}
		else if ($row['target_type']=='url') {
			$out.=' url="'.encodeXML($row['target_value']).'"';
		}
		else if ($row['target_type']=='email') {
			$out.=' email="'.encodeXML($row['target_value']).'"';
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
		$out.='<newsblock title="'.encodeXML($row['title']).'">'.
		'<!--newsblock#'.$row['id'].'-->'.
		'</newsblock>';
	}
	Database::free($result);
	return $out;
}

function getFileFilename($id) {
	$output=NULL;
	$sql = "select filename from file where id=".$id;
	$result = Database::select($sql);
	if ($row = Database::next($result)) {
		$output=$row['filename'];
	}
	Database::free($result);
	return $output;
}
?>