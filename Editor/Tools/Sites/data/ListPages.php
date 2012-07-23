<?php
/**
 * @package OnlinePublisher
 * @subpackage Sites
 */
require_once '../../../Include/Private.php';

$windowSize = Request::getInt('windowSize',30);
$windowPage = Request::getInt('windowPage',0);
$query = Request::getUnicodeString('query');
$sort = Request::getString('sort');
$kind = Request::getString('kind');
$value = Request::getString('value');
$direction = Request::getString('direction');

if ($direction=='') $direction='ascending';

if ($kind=='hierarchy' || $kind=='hierarchyItem') {
	listHierarhy();
} else if ($kind=='subset' && $value=='review') {
	listReview();
} else {
	listPages();
}

function listHierarhy() {
	global $windowSize,$windowPage,$query,$sort,$kind,$value,$direction;
	$writer = new ListWriter();
	
	$writer->startList();
	$writer->startHeaders();
	$writer->header(array('title'=>'Menupunkt','width'=>30));
	$writer->header(array('title'=>'Link / Destination','width'=>40));
	$writer->header(array('title'=>'Sti'));
	$writer->header(array('width'=>1));
	$writer->endHeaders();
	if ($kind=='hierarchy') {
		$hierarchyId = intval($value);
		$parent = null;
	} else {
		$hierarchyId = null;
		$parent = intval($value);
	}
	listHierarhyLevel($writer,$hierarchyId,$parent,1);
	
	$writer->endList();	
}

function listReview() {
	global $windowSize,$windowPage,$query,$sort,$kind,$value,$direction;
	$writer = new ListWriter();
	
	$span = Request::getString('reviewSpan');
	
	$writer->startList()->
		startHeaders()->
			header(array('title'=>'Side','width'=>45))->
			header(array('width'=>1))->
			header(array('title'=>'Bruger'))->
			header(array('title'=>'Tidspunkt','width'=>20))->
			header(array('width'=>1))->
		endHeaders();
	$sql = "select page.id as page_id,page.title as page_title,user.title as user_title,UNIX_TIMESTAMP(review.date) as date,review.accepted
from page,relation as page_review,relation as review_user,review,object as user 
where page_review.from_type='page' and page_review.from_object_id=page.id
and page_review.to_type='object' and page_review.to_object_id=review.object_id
and review_user.from_type='object' and review_user.from_object_id=review.object_id
and review_user.to_type='object' and review_user.to_object_id=user.id";
	if ($span=='day') {
		$sql.=' and review.date<'.Database::datetime(DateUtils::addDays(time(),-1));
	} else if ($span=='week') {
		$sql.=' and review.date<'.Database::datetime(DateUtils::addDays(time(),-7));
	}
	$sql.=" union
		select page.id as page_id, page.title as page_title,'' as user_title, null as date, -1 as accepted 
		from page where page.id not in(select relation.from_object_id from 	relation,review 
		where relation.to_type='object' and relation.to_object_id=review.object_id)
		order by date desc,page_title";
	
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$writer->startRow(array('kind'=>'page','id'=>$row['page_id']))->
			startCell(array('icon'=>'common/page'))->text($row['page_title'])->
			endCell()->
			startCell()->
				startIcons()->
					icon(array('icon'=>'monochrome/info','revealing'=>true,'action'=>true,'data'=>array('action'=>'pageInfo','id'=>$row['page_id'])))->
					icon(array('icon'=>'monochrome/edit','revealing'=>true,'action'=>true,'data'=>array('action'=>'editPage','id'=>$row['page_id'])))->
					icon(array('icon'=>'monochrome/view','revealing'=>true,'action'=>true,'data'=>array('action'=>'viewPage','id'=>$row['page_id'])))->
					icon(array('icon'=>'monochrome/crosshairs','revealing'=>true,'action'=>true,'data'=>array('action'=>'previewPage','id'=>$row['page_id'])))->
				endIcons()->
			endCell();
			if ($row['user_title']) {
				$writer->startCell(array('icon'=>'common/user'))->text($row['user_title'])->endCell();
			} else {
				$writer->startCell()->endCell();
			}
			$writer->startCell()->text(DateUtils::formatFuzzy($row['date']))->endCell()->
			startCell();
			if ($row['accepted']!=-1) {
				$writer->icon(array('icon' => $row['accepted'] ? 'common/success' : 'common/stop'));
			}
			$writer->endCell()->
		endRow();
			
	}
	Database::free($result);
	
	$writer->endList();	
}

function listHierarhyLevel($writer,$hierarchyId,$parent,$level) {
	$sql=buildHierarchySql($hierarchyId,$parent);
	$result = Database::select($sql['list']);
	while ($row = Database::next($result)) {
		$icon=Hierarchy::getItemIcon($row['target_type']);
		$options = array('id'=>$row['id'],'kind'=>'hierarchyItem','level'=>$level);
		if ($row['target_type']=='page' && $row['pageid']) {
			$options['data'] = array('page'=>intval($row['pageid']));
		}
		$writer->
		startRow($options)->
		startCell(array('icon'=>'monochrome/dot'));
		if ($row['hidden']) {
			//$writer->startIcons()->icon(array('icon'=>'monochrome/invisible','data'=>array('action'=>'invisible')))->endIcons();
			$writer->startDelete()->text($row['title'])->endDelete();
		} else {
			$writer->text($row['title']);
		}
		$writer->endCell();
		if ($row['target_type']=='page') {
			if (!$row['pageid']) {
				$writer->startCell(array('icon'=>'common/warning'))->text('Ingen side!')->endCell();
			} else {
				$writer->
					startCell(array('icon'=>$icon));
					if ($row['page_disabled']) {
						$writer->startDelete()->text($row['pagetitle'])->endDelete();
					} else {
						$writer->text($row['pagetitle']);
					}
					$writer->startIcons()->
						icon(array('icon'=>'monochrome/info','revealing'=>true,'action'=>true,'data'=>array('action'=>'pageInfo','id'=>$row['pageid'])))->
						icon(array('icon'=>'monochrome/edit','revealing'=>true,'action'=>true,'data'=>array('action'=>'editPage','id'=>$row['pageid'])))->
						icon(array('icon'=>'monochrome/view','revealing'=>true,'action'=>true,'data'=>array('action'=>'viewPage','id'=>$row['pageid'])))->
						icon(array('icon'=>'monochrome/crosshairs','revealing'=>true,'action'=>true,'data'=>array('action'=>'previewPage','id'=>$row['pageid'])))->
					endIcons()->
				endCell();
			}
		} else if ($row['target_type']=='pageref') {
			$writer->startCell(array('icon'=>$icon))->text($row['pagetitle'].'')->endCell();
		} else if ($row['target_type']=='url') {
			$writer->startCell(array('icon'=>$icon))->text($row['target_value'].'')->
				startIcons()->
					icon(array('icon'=>'monochrome/round_arrow_right','revealing'=>true,'data'=>array('action'=>'visitLink','url'=>$row['target_value'])))->
				endIcons()->
			endCell();
		} else if ($row['target_type']=='email') {
			$writer->startCell(array('icon'=>'monochrome/email'))->text($row['target_value'].'')->endCell();
		} else if ($row['target_type']=='file') {
			$writer->startCell(array('icon'=>$icon))->text($row['filetitle'].'')->endCell();
		} else {
			$writer->startCell(array('icon'=>'monochrome/round_question'))->text($row['target_type'].'')->endCell();
		}
		$writer->
		startCell()->startLine(array('dimmed'=>true))->startWrap()->text($row['page_path'])->endWrap()->endLine()->endCell()->
		startCell(array('wrap'=>false))->
		startIcons()->
			icon(array('icon'=>'monochrome/round_arrow_up','revealing'=>true,'action'=>true,'data'=>array('action'=>'moveItem','direction'=>'up')))->
			icon(array('icon'=>'monochrome/round_arrow_down','revealing'=>true,'action'=>true,'data'=>array('action'=>'moveItem','direction'=>'down')))->
		endIcons()->
		endCell()->
		endRow();
		listHierarhyLevel($writer,$hierarchyId,$row['id'],$level+1);
	}
	Database::free($result);
}

function buildHierarchySql($hierarchyId,$parent) {
	$sql="select hierarchy_item.*,page.id as pageid,page.title as pagetitle,page.path as page_path,page.disabled as page_disabled,template.unique as templateunique,file.object_id as fileid,file.filename,fileobject.title as filetitle from hierarchy_item left join page on page.id = hierarchy_item.target_id left join file on file.object_id = hierarchy_item.target_id left join object as fileobject on file.object_id=fileobject.id left join template on template.id=page.template_id";
	if ($parent===null && $hierarchyId!==null) {
		$sql.=" where hierarchy_id=".Database::int($hierarchyId)." and parent=0";
	} else {
		$sql.=" where parent=".Database::int($parent);
	}
	$sql.=" order by `index`";
	return array('list'=>$sql);
}

function listPages() {
	global $windowSize,$windowPage,$query,$sort,$kind,$value,$direction;
	
	if ($sort=='') $sort='page.title';


	$sql=buildPagesSql();

	$total = Database::selectFirst($sql['total']);

	$writer = new ListWriter();

	$writer->startList()->
		sort($sort,$direction)->
		window(array( 'total' => $total['total'], 'size' => $windowSize, 'page' => $windowPage ))->
		startHeaders()->
			header(array('title'=>'Titel','width'=>40,'key'=>'page.title','sortable'=>'true'))->
			header(array('title'=>'Skabelon','key'=>'template.unique','sortable'=>'true'))->
			header(array('title'=>'Sprog','key'=>'page.language','sortable'=>'true'))->
			header(array('title'=>'Ændret','width'=>1,'key'=>'page.changed','sortable'=>'true'))->
			header(array('width'=>1))->
		endHeaders();

	$templates = TemplateService::getTemplatesKeyed();
	$result = Database::select($sql['list']);
	while ($row = Database::next($result)) {
		$writer->startRow(array('id' => $row['id'], 'title' => $row['title'], 'kind' => 'page', 'icon' => 'common/page'))->
			startCell(array('icon' => 'common/page'))->
			startLine()->text($row['title'])->endLine();
			if ($row['path']) {
				$writer->startLine(array('dimmed'=>true,'mini'=>true))->startWrap()->text($row['path'])->endWrap()->endLine();
			}
			$writer->endCell()->
			startCell(array('dimmed'=>true))->text($templates[$row['unique']]['name'])->endCell()->
			startCell(array('icon'=>GuiUtils::getLanguageIcon($row['language'])))->endCell()->
			startCell(array('wrap'=>false,'dimmed'=>true))->text(DateUtils::formatFuzzy($row['changed']));
		if ($row['publishdelta']>0) {
			$writer->startIcons()->icon(array('icon'=>'monochrome/warning'))->endIcons();
		}
		$writer->endCell()->
			startCell()->
			startIcons()->
				icon(array('icon'=>'monochrome/info','revealing'=>true,'action'=>true,'data'=>array('action'=>'pageInfo','id'=>$row['id'])))->
				icon(array('icon'=>'monochrome/edit','revealing'=>true,'action'=>true,'data'=>array('action'=>'editPage','id'=>$row['id'])))->
				icon(array('icon'=>'monochrome/view','revealing'=>true,'action'=>true,'data'=>array('action'=>'viewPage','id'=>$row['id'])))->
				icon(array('icon'=>'monochrome/crosshairs','revealing'=>true,'action'=>true,'data'=>array('action'=>'previewPage','id'=>$row['id'])));
		if (!$row['searchable']) {
			$writer->icon(array('icon'=>'monochrome/nosearch'));
		}
		if ($row['disabled']) {
			$writer->icon(array('icon'=>'monochrome/invisible'));
		}
		$writer->endIcons()->endCell();
		$writer->endRow();
	}
	Database::free($result);
	$writer->endList();
}


function buildPagesSql() {
	global $windowSize,$windowPage,$query,$sort,$kind,$value,$direction;
	
	$countSql ="select count(page.id) as total";

	$sql = "select page.id,page.secure,page.searchable,page.disabled,page.path,page.title,template.unique,
		UNIX_TIMESTAMP(page.changed) as changed,
		(page.changed-page.published) as publishdelta,page.language";

	$sqlLimits = " from page,template";
	if ($kind=='subset' && $value=='news') {
		$sqlLimits.=",news, object_link";
	}
	$sqlLimits .= " where page.template_id=template.id ";
	if ($kind=='subset' && $value=='news') {
		$sqlLimits.=" and object_link.object_id=news.object_id and object_link.target_value=page.ID and object_link.target_type='page'";
	}
	if ($query!='' && $query!=NULL) {
		$sqlLimits.=" and (page.title like ".Database::search($query).
			" or page.`index` like ".Database::search($query).
			" or page.description like ".Database::search($query).
			" or page.keywords like ".Database::search($query).")";
	}
	if ($kind=='language') {
		if ($value=='') {
			$sqlLimits.=" and (page.language is NULL or page.language='')";
		} else {
			$sqlLimits.=" and page.language=".Database::text($value);
		}
	} else if ($value=='changed') {
		$sqlLimits.=" and page.changed>page.published";
	} else if ($value=='nomenu') {
		$sqlLimits.=" and page.id not in (select target_id from `hierarchy_item` where `target_type`='page')";
	} else if ($value=='warnings') {
		$sqlLimits.=" and (page.changed>page.published or page.path is null or page.path='')";
	} else if ($value=='latest') {
		$sqlLimits.=" and page.changed>".Database::datetime(DateUtils::addDays(time(),-1));
	}
	$sqlLimits.=" order by ".$sort.($direction=='ascending' ? ' asc' : ' desc');

	$listSql = $sql.$sqlLimits." limit ".($windowPage*$windowSize).",".$windowSize;

	return array('list'=>$listSql,'total'=>$countSql.$sqlLimits);
}
?>