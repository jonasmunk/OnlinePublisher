<?php
/**
 * @package OnlinePublisher
 * @subpackage Sites
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Database.php';
require_once '../../Classes/Template.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Hierarchy.php';
require_once '../../Classes/Utilities/StringUtils.php';
require_once '../../Classes/Utilities/GuiUtils.php';


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
} else {
	listPages();
}

function listHierarhy() {
	global $windowSize,$windowPage,$query,$sort,$kind,$value,$direction;
	$writer = new ListWriter();
	
	$writer->startList();
	$writer->startHeaders();
	$writer->header(array('title'=>'Menupunkt'));
	$writer->header(array('title'=>'Link','width'=>40));
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
		startCell(array('icon'=>'monochrome/dot'))->text($row['title']);
		if ($row['hidden']) {
			$writer->startIcons()->icon(array('icon'=>'monochrome/invisible','data'=>array('action'=>'invisible')))->endIcons();
		}
		$writer->endCell();
		if ($row['target_type']=='page') {
			if (!$row['pageid']) {
				$writer->startCell(array('icon'=>'common/warning'))->text('Ingen side!')->endCell();
			} else {
				$writer->
					startCell(array('icon'=>$icon))->
					text($row['pagetitle'])->
					startIcons()->
						icon(array('icon'=>'monochrome/info_light','revealing'=>true,'data'=>array('action'=>'pageInfo','id'=>$row['pageid'])))->
					endIcons()->
					endCell();
			}
		} else if ($row['target_type']=='pageref') {
			$writer->startCell(array('icon'=>$icon))->text($row['pagetitle'].'')->endCell();
		} else if ($row['target_type']=='url') {
			$writer->startCell(array('icon'=>$icon))->text($row['target_value'].'')->endCell();
		} else if ($row['target_type']=='email') {
			$writer->startCell(array('icon'=>'monochrome/email'))->text($row['target_value'].'')->endCell();
		} else if ($row['target_type']=='file') {
			$writer->startCell(array('icon'=>$icon))->text($row['filetitle'].'')->endCell();
		} else {
			$writer->startCell(array('icon'=>'monochrome/round_question'))->text($row['target_type'].'')->endCell();
		}
		$writer->
		startCell()->startWrap()->text($row['page_path'])->endWrap()->endCell()->
		startCell(array('wrap'=>false))->
		startIcons()->
			icon(array('icon'=>'monochrome/round_arrow_up','revealing'=>true,'data'=>array('action'=>'moveItem','direction'=>'up')))->
			icon(array('icon'=>'monochrome/round_arrow_down','revealing'=>true,'data'=>array('action'=>'moveItem','direction'=>'down')))->
		endIcons()->
		endCell()->
		endRow();
		listHierarhyLevel($writer,$hierarchyId,$row['id'],$level+1);
	}
	Database::free($result);
}

function buildHierarchySql($hierarchyId,$parent) {
	$sql="select hierarchy_item.*,page.id as pageid,page.title as pagetitle,page.path as page_path,template.unique as templateunique,file.object_id as fileid,file.filename,fileobject.title as filetitle from hierarchy_item left join page on page.id = hierarchy_item.target_id left join file on file.object_id = hierarchy_item.target_id left join object as fileobject on file.object_id=fileobject.id left join template on template.id=page.template_id";
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

	$writer->startList();
	$writer->sort($sort,$direction);
	$writer->window(array( 'total' => $total['total'], 'size' => $windowSize, 'page' => $windowPage ));
	$writer->startHeaders();
	$writer->header(array('title'=>'Titel','width'=>40,'key'=>'page.title','sortable'=>'true'));
	$writer->header(array('title'=>'Skabelon','key'=>'template.unique','sortable'=>'true'));
	//$writer->header(array('title'=>'Sti','key'=>'page.path','sortable'=>'true'));
	$writer->header(array('title'=>'Sprog','key'=>'page.language','sortable'=>'true'));
	$writer->header(array('title'=>'Ændret','key'=>'page.changed','sortable'=>'true'));
	$writer->endHeaders();

	$templates = TemplateService::getTemplatesKeyed();
	$result = Database::select($sql['list']);
	while ($row = Database::next($result)) {
		$modified = $row['publishdelta']>0;
		echo '<row id="'.$row['id'].'" title="'.StringUtils::escapeXML($row['title']).'" kind="page" icon="common/page">'.
		'<cell icon="common/page"><line>'.StringUtils::escapeXML($row['title']).'</line>'.
		($row['path'] ? '<line dimmed="true"><wrap>'.StringUtils::escapeXML($row['path']).'</wrap></line>' : '').
		'</cell>'.
		'<cell>'.StringUtils::escapeXML($templates[$row['unique']]['name']).'</cell>'.
		//'<cell'.($row['path']=='' ? ' icon="monochrome/warning"' : '').'><line dimmed="true">'.StringUtils::escapeXML($row['path']).'</line></cell>'.
		'<cell icon="'.GuiUtils::getLanguageIcon($row['language']).'"></cell>'.
		'<cell'.($modified ? ' icon="monochrome/warning"' : '').'>'.StringUtils::escapeXML($row['changed']).'</cell>'.
		'</row>';
	}
	Database::free($result);
	$writer->endList();
}


function buildPagesSql() {
	global $windowSize,$windowPage,$query,$sort,$kind,$value,$direction;
	
	$countSql ="select count(page.id) as total";

	$sql = "select page.id,page.secure,page.path,page.title,template.unique,
		date_format(page.changed,'%d/%m-%Y') as changed,
		date_format(page.changed,'%Y%m%d%h%i%s') as changedindex,
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
	} else if ($value=='warnings') {
		$sqlLimits.=" and (page.changed>page.published or page.path is null or page.path='')";
	}
	$sqlLimits.=" order by ".$sort.($direction=='ascending' ? ' asc' : ' desc');

	$listSql = $sql.$sqlLimits." limit ".($windowPage*$windowSize).",".(($windowPage+1)*$windowSize);

	return array('list'=>$listSql,'total'=>$countSql.$sqlLimits);
}
?>