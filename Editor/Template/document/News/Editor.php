<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
	$sql="select * from document_news,document_section where document_news.section_id=document_section.id and document_section.id=".$sectionId;
	$row = Database::selectFirst($sql);
	if ($row) {
		$style='';
		
		$groups = array();
		$sql="SELECT newsgroup_id from document_news_newsgroup";
		$newsResult = Database::select($sql);
		while ($newsRow = Database::next($newsResult)) {
			$groups[]=$newsRow['newsgroup_id'];
		}
		Database::free($newsResult);
		
		$output.=
		'<td style="'.$sectionStyle.'" id="selectedSectionTD" class="sectionTDnews sectionSelected">'.
		'<div align="'.$row['align'].'" id="NewsDiv">'.
		'<form name="NewsForm" action="News/Update.php" method="post" style="margin: 0px;">'.
		'<input type="hidden" name="title" value="'.encodeXML($row['title']).'"/>'.
		'<input type="hidden" name="mode" value="'.$row['mode'].'"/>'.
		'<input type="hidden" name="news" value="'.$row['news_id'].'"/>'.
		'<input type="hidden" name="groups" value="'.implode(',',$groups).'"/>'.
		'<input type="hidden" name="align" value="'.$row['align'].'"/>'.
		'<input type="hidden" name="left" value="'.$row['left'].'"/>'.
		'<input type="hidden" name="right" value="'.$row['right'].'"/>'.
		'<input type="hidden" name="top" value="'.$row['top'].'"/>'.
		'<input type="hidden" name="bottom" value="'.$row['bottom'].'"/>'.
		'<input type="hidden" name="sortby" value="'.$row['sortby'].'"/>'.
		'<input type="hidden" name="sortdir" value="'.$row['sortdir'].'"/>'.
		'<input type="hidden" name="maxitems" value="'.$row['maxitems'].'"/>'.
		'<input type="hidden" name="timetype" value="'.$row['timetype'].'"/>'.
		'<input type="hidden" name="timecount" value="'.$row['timecount'].'"/>'.
		'</form>'.
		'<div id="NewsPreview"/>'.
		'</div>'.
		'<script>parent.Toolbar.location=\'News/Toolbar.php?\'+Math.random();</script>'.
		'<script src="News/Script.js"></script>'.
		'<script>
		function updatePreview(prefix) {
			if (prefix==null) prefix="";
			var mode = document.forms.NewsForm.mode.value;
			var news = document.forms.NewsForm.news.value;
			var groups = document.forms.NewsForm.groups.value;
			var sortby = document.forms.NewsForm.sortby.value;
			var sortdir = document.forms.NewsForm.sortdir.value;
			var timetype = document.forms.NewsForm.timetype.value;
			var timecount = document.forms.NewsForm.timecount.value;
			var maxitems = document.forms.NewsForm.maxitems.value;
			loadXMLDoc("./"+prefix+"ScriptServer.php?mode="+mode+"&news="+news+"&groups="+groups+"&sortby="+sortby+"&sortdir="+sortdir+"&timetype="+timetype+"&timecount="+timecount+"&maxitems="+maxitems+"&"+new Date().getTime(),processReqChange)
		}
		updatePreview("News/");
		
		function saveSection() {
			document.forms.NewsForm.submit();
		}
	
		</script>'.
		'</td>';
	}
?>