<?
/**
 * @package OnlinePublisher
 * @subpackage Parts.Html
 */
require_once($basePath.'Editor/Classes/Part.php');
require_once($basePath.'Editor/Classes/GuiUtils.php');

class PartNews extends Part {
	
	function PartNews($id=0) {
		parent::Part('news');
		$this->id = $id;
	}
	
	function sub_display($context) {
		return $this->render();
	}
	
	function _buildSql($row) {
		$sql = '';
		if ($row['mode'] == 'single' && $row['news_id']!='') {
			$sql="select * from object where id=".$row['news_id'];
		}
		else if ($row['mode'] == 'groups') {
			$sortBy = $row['sortby'];
			// Find sort direction
			if ($row['sortdir']=='descending') {
				$sortDir = 'DESC';
			}
			else {
				$sortDir = 'ASC';
			}
			$timetype = $row['timetype'];
			if ($timetype=='always') {
				$timeSql=''; // no time managing for always
			}
			else if ($timetype=='now') {
				// Create sql for active news
				$timeSql=" and ((news.startdate is null and news.enddate is null) or (news.startdate<=now() and news.enddate>=now()) or (news.startdate<=now() and news.enddate is null) or (news.startdate is null and news.enddate>=now()))";
			}
			else {
				$count=$row['timecount'];
				if ($timetype=='interval') {
					$start = intval($row['startdate']);
					$end = intval($row['enddate']);
				}
				else if ($timetype=='hours') {
					$start = mktime(date("H")-$count,date("i"),date("s"),date("m"),date("d"),date("Y"));
					$end = mktime();
				}
				else if ($timetype=='days') {
					$start = mktime(date("H"),date("i"),date("s"),date("m"),date("d")-$count,date("Y"));
					$end = mktime();
				}
				else if ($timetype=='weeks') {
					$start = mktime(date("H"),date("i"),date("s"),date("m"),date("d")-($count*7),date("Y"));
					$end = mktime();
				}
				else if ($timetype=='months') {
					$start = mktime(date("H"),date("i"),date("s"),date("m")-$count,date("d"),date("Y"));
					$end = mktime();
				}
				else if ($timetype=='years') {
					$start = mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")-$count);
					$end = mktime();
				}
				$timeSql=" and ((news.startdate is null and news.enddate is null) or (news.startdate>=".Database::datetime($start)." and news.startdate<=".Database::datetime($end).") or (news.enddate>=".Database::datetime($start)." and news.enddate<=".Database::datetime($end).") or (news.enddate>=".Database::datetime($start)." and news.startdate is null) or (news.startdate<=".Database::datetime($end)." and news.enddate is null))";
			}
			if (isset($row['groups'])) {
				$groupSql = " and newsgroup_news.newsgroup_id in (".implode($row['groups'],',').")";
			} else {
				$groupSql = " and newsgroup_news.newsgroup_id=part_news_newsgroup.newsgroup_id and part_news_newsgroup.part_id=".$this->id;
			}
			$sql = "select distinct object.data from object,news, newsgroup_news, part_news_newsgroup where object.id=news.object_id and news.object_id=newsgroup_news.news_id".$groupSql.$timeSql." order by ".$sortBy." ".$sortDir;
		}
		return $sql;
	}
	
	function sub_editor($context) {
		global $baseUrl;
		$sql = "select * from part_news where part_id=".$this->id;
		if ($row = Database::selectFirst($sql)) {
			// Build array of groups
			$groups = array();
			$sql="SELECT newsgroup_id from part_news_newsgroup where part_id=".$this->id;
			$newsResult = Database::select($sql);
			while ($newsRow = Database::next($newsResult)) {
				$groups[]=$newsRow['newsgroup_id'];
			}
			Database::free($newsResult);
			return
			'<input type="hidden" name="title" id="PartNewsTitle" value="'.encodeXML($row['title']).'"/>'.
			'<input type="hidden" name="mode" id="PartNewsMode" value="'.$row['mode'].'"/>'.
			'<input type="hidden" name="news" id="PartNewsNews" value="'.$row['news_id'].'"/>'.
			'<input type="hidden" name="groups" id="PartNewsGroups" value="'.implode(',',$groups).'"/>'.
			'<input type="hidden" name="align" value="'.$row['align'].'"/>'.
			'<input type="hidden" name="sortby" id="PartNewsSortBy" value="'.$row['sortby'].'"/>'.
			'<input type="hidden" name="sortdir" id="PartNewsSortDir" value="'.$row['sortdir'].'"/>'.
			'<input type="hidden" name="maxitems" id="PartNewsMaxItems" value="'.$row['maxitems'].'"/>'.
			'<input type="hidden" name="timetype" id="PartNewsTimeType" value="'.$row['timetype'].'"/>'.
			'<input type="hidden" name="timecount" id="PartNewsTimeCount" value="'.$row['timecount'].'"/>'.
			'<input type="hidden" name="variant" id="PartNewsVariant" value="'.$row['variant'].'"/>'.
			'<div id="part_news_preview"/>'.
			$this->render().
			'</div>'.
			'<script src="'.$baseUrl.'Editor/Parts/news/Script.js"></script>';
		} else {
			return '';
		}
	}
	
	function sub_create() {
		$sql="insert into part_news (part_id,mode,sortdir,sortby,timetype) values (".$this->id.",'single','ascending','startdate','always')";
		Database::insert($sql);
	}
	
	function sub_delete() {
		$sql = "delete from part_news where part_id=".$this->id;
		Database::delete($sql);
		$sql = "delete from part_news_newsgroup where part_id=".$this->id;
		Database::delete($sql);
	}
	
	function sub_update() {
		// Fetch all form vars
		$align = requestPostText('align');
		$groups = requestPostText('groups');
		$mode = requestPostText('mode');
		$news = requestPostNumber('news',0);
		$maxitems = requestPostNumber('maxitems',0);
		$sortby = requestPostText('sortby');
		$sortdir = requestPostText('sortdir');
		$timetype = requestPostText('timetype');
		$timecount = requestPostNumber('timecount',1);
		$variant = requestPostText('variant');
		$title = requestPostText('title');

		// Build sql sentence
		$sql="update part_news set".
		" align=".Database::text($align).
		",title=".Database::text($title).
		",mode=".Database::text($mode).
		",sortby=".Database::text($sortby).
		",sortdir=".Database::text($sortdir).
		",timetype=".Database::text($timetype).
		",timecount=".$timecount.
		",maxitems=".$maxitems.
		",news_id=".$news.
		",variant=".Database::text($variant).
		" where part_id=".$this->id;
		Database::update($sql);
		
		// Remove all existing groups
		$sql="delete from part_news_newsgroup where part_id=".$this->id;
		Database::delete($sql);

		// Insert new groups
		if ($mode=='groups') {
			$groups = explode(",",$groups);
			foreach ($groups as $group) {
				if (is_numeric($group)) {
					$sql="insert into part_news_newsgroup (part_id,newsgroup_id) values (".$this->id.",".$group.")";
					Database::insert($sql);
				}
			}
		}
	}
	
	function sub_build($context) {
		$sql="select * from part_news where part_id=".$this->id;
		$row = Database::selectFirst($sql);
		if ($row) {
			return $this->generate($row);
		}
		return '<news xmlns="'.$this->_buildnamespace('1.0').'"></news>';
	}
	
	function generate($params) {
		$data='<news xmlns="'.$this->_buildnamespace('1.0').'">';
		$data.='<'.$params['variant'].'>';
		if ($params['title']!='') {
			$data.='<title>'.encodeXML($params['title']).'</title>';
		}
		$maxitems = $params['maxitems']; // TODO: Build this into sql PERFORMANCE!
		$sql = $this->_buildSql($params);
		error_log($sql);
		if ($sql!='') {
			$result = Database::select($sql);
			while ($newsRow = Database::next($result)) {
				$data.=$newsRow['data'];
				$maxitems--;
				if ($maxitems==0) break;
			}
			Database::free($result);
		}

		$data.='</'.$params['variant'].'>';
		$data.='</news>';
		return $data;
	}
	
	///////////////////////// Preview /////////////////////////
	
		
	function sub_preview() {
		$params = array(
			'title'=>Request::getUnicodeString('title'),
			'variant'=>Request::getString('variant'),
			'mode'=>Request::getString('mode'),
			'sortby'=>Request::getString('sortby'),
			'sortdir'=>Request::getString('sortdir'),
			'timetype'=>Request::getString('timetype'),
			'timecount'=>Request::getInt('timecount'),
			'maxitems'=>Request::getInt('maxitems'),
			'news_id'=>Request::getInt('news'),
			'groups'=>Request::getIntArrayComma('groups')
		);
		return $this->generate($params);
	}
	
	/////////////////////////// Dynamism ///////////////////////
	
	function sub_isDynamic() {
		return true;
	}
	
	// Toolbar stuff
	
	function getToolbarTabs() {
		return array(
				 'news' => array('title' => 'Nyheder'),
				 'view' => array('title' => 'Visning')
			);
	}
	
	function getToolbarDefaultTab() {
		return 'news';
	}
	
	function getToolbarContent($tab) {
		if ($tab=='news') {
			return $this->_newsTab();
		} elseif ($tab=='view') {
			return $this->_viewTab();
		} else {
			return '';
		}
	}
	
	function _newsTab() {
		$gui=
		'<align xmlns="uri:Style" title="Justering" object="TextAlign" onchange="updateForm();"/>'.
		'<group xmlns="uri:BarForm">'.
		'<top>'.
		'<badge>Titel:</badge>'.
		'<textfield name="title" object="Title" onblur="updateForm();"/>'.
		'<radiobutton name="type" object="ModeSingle" onclick="updateForm()"/>'.
		'<badge>Nyheder:</badge>'.
		'<select name="news" object="News" onchange="updateForm();" onfocus="ModeSingle.setSelected(true);updateForm();">'.
		'<option title="Vælg nyhed..." value="0"/>'.
		GuiUtils::buildObjectOptions('news',20).
		'</select>'.
		'<radiobutton name="type" object="ModeGroups" onclick="updateForm()"/>'.
		'<badge>Grupper:</badge>'.
		'<select name="groups" multiline="true" multiple="true" object="Groups" onchange="updateForm();" onfocus="ModeGroups.setSelected(true);updateForm();">'.
		GuiUtils::buildObjectOptions('newsgroup',20).
		'</select>'.
		'</top>'.
		'<bottom>'.
		'<badge>Variant:</badge>'.
		'<select name="variant" object="Variant" onchange="updateForm();">'.
		'<option value="box" title="Boks"/>'.
		'<option value="list" title="Liste"/>'.
		'</select>'.
		'</bottom>'.
		'</group>'.
		'<script xmlns="uri:Script">
		function updateForm() {
			var alignValue = TextAlign.getValue();
			formula.align.value= alignValue;
			//parent.Editor.document.getElementById("NewsDiv").setAttribute("align",alignValue);
			formula.title.value=Title.getValue();
			formula.variant.value=Variant.getValue();
			if (ModeGroups.isSelected()) {
				formula.mode.value="groups";
			}
			else {
				formula.mode.value="single";
			}
			formula.groups.value=Groups.getValues();
			formula.news.value=News.getValue();
			editorFrame.op.part.News.updatePreview();
		}
		
		function updateThis() {
			TextAlign.setValue(formula.align.value);
			Title.setValue(formula.title.value);
			if (formula.mode.value=="groups") {
				ModeGroups.setSelected(true);
			}
			else {
				ModeSingle.setSelected(true);
			}
			News.setValue(formula.news.value);
			Variant.setValue(formula.variant.value);
			Groups.setValues(formula.groups.value);
		}
		updateThis();
		</script>';
		return $gui;
	}
	
	
	function _viewTab() {
		$gui=
		'<group xmlns="uri:BarForm">'.
		'<top>'.
		'<badge>Sorter efter:</badge>'.
		'<select name="sortby" onchange="updateForm();" object="SortBy">'.
		'<option title="Startdato" value="startdate"/>'.
		'<option title="Slutdato" value="enddate"/>'.
		'<option title="Titel" value="title"/>'.
		'</select>'.
		'<badge>Max antal:</badge>'.
		'<select name="maxitems" onchange="updateForm();" object="MaxItems">'.
		'<option title="Uendeligt" value="0"/>';
		for ($i=1;$i<=50;$i++) {
			$gui.='<option title="'.$i.'" value="'.$i.'"/>';
		}
		$gui.=
		'</select>'.
		'<badge>Tid:</badge>'.
		'<select name="timetype" onchange="updateForm()" object="TimeType">'.
		'<option title="Altid" value="always"/>'.
		'<option title="Lige nu" value="now"/>'.
		'<option title="Seneste timer..." value="hours"/>'.
		'<option title="Seneste dage..." value="days"/>'.
		'<option title="Seneste uger..." value="weeks"/>'.
		'<option title="Seneste måneder..." value="months"/>'.
		'<option title="Seneste år..." value="years"/>'.
		'</select>'.
		'</top>'.
		'<bottom>'.
		'<badge>Retning:</badge>'.
		'<select name="sortdir" onchange="updateForm();" object="SortDir">'.
		'<option title="Stigende" value="ascending"/>'.
		'<option title="Faldende" value="descending"/>'.
		'</select>'.
		'<space/>'.
		'<space/>'.
		'<badge>Antal:</badge>'.
		'<select name="timecount" onchange="updateForm();" object="TimeCount">';
		for ($i=1;$i<=50;$i++) {
			$gui.='<option title="'.$i.'" value="'.$i.'"/>';
		}
		$gui.=
		'</select>'.
		'</bottom>'.
		'</group>'.
		'<script xmlns="uri:Script">
		function updateForm() {
			formula.sortby.value=SortBy.getValue();
			formula.maxitems.value=MaxItems.getValue();
			formula.timetype.value=TimeType.getValue();
			formula.sortdir.value=SortDir.getValue();
			formula.timecount.value=TimeCount.getValue();
			editorFrame.op.part.News.updatePreview();
		}
		function updateThis() {
			SortBy.setValue(formula.sortby.value);
			MaxItems.setValue(formula.maxitems.value);
			TimeType.setValue(formula.timetype.value);
			SortDir.setValue(formula.sortdir.value);
			TimeCount.setValue(formula.timecount.value);
		}
		updateThis();
		</script>';
		return $gui;
	}

}
?>