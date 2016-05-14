<?php
/**
 * @package OnlinePublisher
 * @subpackage Sites
 */
require_once '../../../Include/Private.php';

$windowSize = Request::getInt('windowSize',30);
$windowPage = Request::getInt('windowPage',0);
$query = Request::getString('query');
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
	
	$writer->startList()->
		startHeaders()->
			header(array('title'=>array('Menu item','da'=>'Menupunkt'),'width'=>30))->
			header(array('title'=>'Link / Destination','width'=>40))->
			header(array('title'=>array('Path','da'=>'Sti')))->
			header(array('width'=>1))->
		endHeaders();

	if ($kind=='hierarchy') {
		$hierarchyId = intval($value);
		$parent = null;
		$hierarchy = Hierarchy::load($hierarchyId);
		
		if ($hierarchy) {
			$writer->startRow(array('id'=>$hierarchyId,'kind'=>'hierarchy','level'=>1))->
				startCell(array('icon'=>'common/hierarchy'))->text($hierarchy->getName())->endCell()->
				startCell()->endCell()->
				startCell()->endCell()->
				startCell()->endCell()->
			endRow();			
		}
	} else {
		$hierarchyId = null;
		$parent = intval($value);
		$item = HierarchyItem::load($parent);
		$row = Database::selectFirst(buildHierarchySql(null,null,$parent));
		_writeHierarchyItem($row,1,$writer);
	}
	listHierarhyLevel($writer,$hierarchyId,$parent,2);
	
	$writer->endList();	
}

function listReview() {
	global $windowSize,$windowPage,$query,$sort,$kind,$value,$direction;
	$writer = new ListWriter();
	
	$span = Request::getString('reviewSpan');
	
	$writer->startList()->
		startHeaders()->
			header(array('title'=>array('Page','da'=>'Side'),'width'=>45))->
			header(array('width'=>1))->
			header(array('title'=>array('User','da'=>'Bruger')))->
			header(array('title'=>array('Time','da'=>'Tidspunkt'),'width'=>20))->
			header(array('width'=>1))->
		endHeaders();
	$sql = "SELECT page.id as page_id,page.title as page_title,user.title as user_title,UNIX_TIMESTAMP(review.date) as date,review.accepted
from page,relation as page_review,relation as review_user,review,object as user 
where page_review.from_type='page' and page_review.from_object_id=page.id
and page_review.to_type='object' and page_review.to_object_id=review.object_id
and review_user.from_type='object' and review_user.from_object_id=review.object_id
and review_user.to_type='object' and review_user.to_object_id=user.id";
	if ($span=='day') {
		$sql.=' and review.date<'.Database::datetime(Dates::addDays(time(),-1));
	} else if ($span=='week') {
		$sql.=' and review.date<'.Database::datetime(Dates::addDays(time(),-7));
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
			$writer->startCell()->text(Dates::formatFuzzy($row['date']))->endCell()->
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

function _writeHierarchyItem($row,$level,$writer) {
	$icon = Hierarchy::getItemIcon($row['target_type']);
	$options = array('id'=>$row['id'],'kind'=>'hierarchyItem','level'=>$level);
	if ($row['target_type']=='page' && $row['pageid']) {
		$options['data'] = array('page'=>intval($row['pageid']));
	}
	$writer->
	startRow($options)->
	startCell(array('icon'=>'monochrome/dot'));
	if ($row['hidden']) {
		$writer->startDelete()->text($row['title'])->endDelete();
	} else {
		$writer->text($row['title']);
	}
	$writer->endCell();
	if ($row['target_type']=='page') {
		if (!$row['pageid']) {
			$writer->startCell(array('icon'=>'common/warning'))->text(array('No page','da'=>'Ingen side!'))->endCell();
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
}

function listHierarhyLevel($writer,$hierarchyId,$parent,$level) {
	$sql = buildHierarchySql($hierarchyId,$parent);
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		_writeHierarchyItem($row,$level,$writer);
		listHierarhyLevel($writer,$hierarchyId,$row['id'],$level+1);
	}
	Database::free($result);
}

function buildHierarchySql($hierarchyId,$parent,$hierarchItemId=null) {
	$sql="select hierarchy_item.*,page.id as pageid,page.title as pagetitle,page.path as page_path,page.disabled as page_disabled,template.unique as templateunique,file.object_id as fileid,file.filename,fileobject.title as filetitle from hierarchy_item left join page on page.id = hierarchy_item.target_id left join file on file.object_id = hierarchy_item.target_id left join object as fileobject on file.object_id=fileobject.id left join template on template.id=page.template_id";
	if ($parent===null && $hierarchyId!==null) {
		$sql.=" where hierarchy_id=".Database::int($hierarchyId)." and parent=0";
	} else if ($hierarchItemId!==null) {
			$sql.=" where hierarchy_item.id=".Database::int($hierarchItemId);
	} else {
		$sql.=" where parent=".Database::int($parent);
	}
	$sql.=" order by `index`";
	return $sql;
}

function listPages() {
	global $windowSize,$windowPage,$query,$sort,$kind,$value,$direction;
	
    $advanced = Request::getBoolean('advanced');
  
	if ($sort=='') $sort='page.title';


	$sql = buildPagesSql();

	$total = Database::selectFirst($sql['total']);

	$writer = new ListWriter();

	$writer->startList()->
		sort($sort,$direction)->
		window(['total' => $total['total'], 'size' => $windowSize, 'page' => $windowPage ])->
		startHeaders();
    $writer->header(['title'=>['Title','da'=>'Titel'],'width'=>40,'key'=>'page.title','sortable'=>'true']);
    if ($advanced) {
        $writer->header(['title'=>'Type','key'=>'template.unique','sortable'=>'true']);
    }
			

	if ($kind=='subset' && $value=='news') {
		$writer->header(['title'=>['News','da'=>'Nyhed']]);
	}
	$writer->
		header(['title'=>array('Language','da'=>'Sprog'),'key'=>'page.language','sortable'=>'true'])->
		header(['title'=>array('Modified','da'=>'Ændret'),'width'=>1,'key'=>'page.changed','sortable'=>'true'])->
		header(['title'=>array('Hits','da'=>'Hits'),'width'=>1])->
		header(['width'=>1])->
	endHeaders();

	$templates = TemplateService::getTemplatesKeyed();
	$rows = Database::selectAll($sql['list']);
	$hits = StatisticsService::getPageHits($rows);
	foreach ($rows as $row) {
		$writer->startRow(['id' => $row['id'], 'title' => $row['title'], 'kind' => 'page', 'icon' => 'common/page'])->
			startCell(['icon' => 'common/page'])->
			startLine()->text($row['title'])->endLine();
			if ($advanced && $row['path']) {
				$writer->startLine(['dimmed'=>true,'mini'=>true])->startWrap()->text($row['path'])->endWrap()->endLine();
			}
			$writer->endCell();
        if ($advanced) {
            $writer->startCell(['dimmed'=>true])->text($templates[$row['unique']]['name'])->endCell();
        }
		if ($kind=='subset' && $value=='news') {
			$news = News::load($row['news_id']);
			if ($news) {
				$writer->startCell([ 'icon' => $news->getIcon() ]);
				$writer->text($news->getTitle());

				$writer->startIcons()->
					icon([
                        'icon' => 'monochrome/info',
                        'revealing' => true,
                        'action' => true,
                        'data' => ['action'=>'newsInfo','id'=>$news->getId()]
                    ])->
				endIcons();
			} else {
				$writer->startCell();
			}
			$writer->endCell();
		}
		$writer->
		    startCell(['icon' => GuiUtils::getLanguageIcon($row['language'])])->endCell()->
		    startCell(['wrap' => false,'dimmed' => true])->text(Dates::formatFuzzy($row['changed']));
		if ($row['publishdelta']>0) {
			$writer->startIcons(['left'=>3])->icon(['icon'=>'monochrome/warning','size'=>12])->endIcons();
		}
		$writer->startCell()->text(@$hits[$row['id']])->endCell();
		$writer->endCell()->
			startCell()->
			startIcons()->
				icon(['icon'=>'monochrome/info','revealing'=>true,'action'=>true,'data'=>['action'=>'pageInfo','id'=>$row['id']]])->
				icon(['icon'=>'monochrome/edit','revealing'=>true,'action'=>true,'data'=>['action'=>'editPage','id'=>$row['id']]])->
				icon(['icon'=>'monochrome/view','revealing'=>true,'action'=>true,'data'=>['action'=>'viewPage','id'=>$row['id']]])->
				icon(['icon'=>'monochrome/crosshairs','revealing'=>true,'action'=>true,'data'=>['action'=>'previewPage','id'=>$row['id']]]);
		if (!$row['searchable']) {
			$writer->icon(['icon'=>'monochrome/nosearch']);
		}
		if ($row['disabled']) {
			$writer->icon(['icon'=>'monochrome/invisible']);
		}
		$writer->endIcons()->endCell();
		$writer->endRow();
	}
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
		$sql.=",news.object_id as news_id";
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
		$sqlLimits.=" and page.changed>".Database::datetime(Dates::addDays(time(),-1));
	}
	$sqlLimits.=" order by ".$sort.($direction=='ascending' ? ' asc' : ' desc');

	$listSql = $sql.$sqlLimits." limit ".($windowPage*$windowSize).",".$windowSize;
	return array('list'=>$listSql,'total'=>$countSql.$sqlLimits);
}
?>