<?
/**
 * @package OnlinePublisher
 * @subpackage Parts.Richtext
 */
require_once($basePath.'Editor/Classes/Parts/LegacyPartController.php');

class PartRichtext extends LegacyPartController {
	
	function PartRichtext($id=0) {
		parent::LegacyPartController('richtext');
		$this->id = $id;
	}
	
	function sub_display($context) {
		$data='';
		$sql = "select * from part_richtext where part_id=".$this->id;
		if ($row = Database::selectFirst($sql)) {
			$data=
			'<div class="Part-richtext">'.
			$row['html'].
			'</div>';
		}
		return $data;
	}
	
	function sub_editor($context) {
	    global $baseUrl;
		$sql = "select * from part_richtext where part_id=".$this->id;
		if ($row = Database::selectFirst($sql)) {
			return
			'<textarea class="Part-richtext" id="PartRichtextTextarea" name="html" style="width: 100%; height: 250px;">'.
			encodeXML($row['html']).
			'</textarea>'.
			'<script language="javascript" type="text/javascript" src="'.$baseUrl.'Editor/Libraries/tinymce/tiny_mce.js"></script>
            <script language="javascript" type="text/javascript">
            tinyMCE.init({
            	mode : "textareas",
            	theme : "advanced",
            	entity_encoding : "numeric",
            	convert_fonts_to_spans : true,
            	language : "en",
            	content_css : "'.$baseUrl.'style/'.$context->getDesign().'/editors/'.$context->getTemplate().'_richtext.css",
            	theme_advanced_toolbar_location : "top",
            	theme_advanced_toolbar_align : "left",
            	theme_advanced_path_location : "bottom", //preview,emotions,iespell,flash,advimage,
            	plugins : "table,save,advhr,advlink,insertdatetime,zoom,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable",
        		//theme_advanced_buttons1_add_before : "save,newdocument,separator",
        		theme_advanced_buttons1 : "cut,copy,paste,pastetext,pasteword,separator,search,replace,separator,undo,redo,separator,formatselect,fontselect,fontsizeselect",
        		theme_advanced_buttons2 : "bold,italic,underline,separator,justifyleft,justifycenter,justifyright,jusityfull,separator,bullist,numlist,separator,indent,outdent,separator,forecolor,backcolor,separator,sub,sup,separator,hr,advhr,separator,link,unlink,anchor", // preview,
        		theme_advanced_buttons3 : "visualaid,tablecontrols,separator,insertdate,inserttime,charmap,separator,fullscreen,removeformat,cleanup,code",
                theme_advanced_disable : "image,help,styleselect",
        	    plugin_insertdate_dateFormat : "%d-%m-%Y",
        	    plugin_insertdate_timeFormat : "%H:%M:%S",
            	extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
        		theme_advanced_resize_horizontal : false,
        		theme_advanced_resizing : true
            });
            </script>';
		} else {
			return '';
		}
	}
	
	function sub_create() {
		$sql = "insert into part_richtext (part_id) values (".$this->id.")";
		Database::insert($sql);
	}
	
	function sub_delete() {
		$sql = "delete from part_richtext where part_id=".$this->id;
		Database::delete($sql);
	}
	
	function sub_update() {
		$html = requestPostText('html');
		$sql = "update part_richtext set html=".Database::text($html)." where part_id=".$this->id;
		Database::update($sql);
	}
	
	function sub_build($context) {
		$sql = "select * from part_richtext where part_id=".$this->id;
		if ($row = Database::selectFirst($sql)) {
			return 
			'<richtext xmlns="'.$this->_buildnamespace('1.0').'">'.
			//'<![CDATA['.
			$row['html'].
			//']]>'.
			'</richtext>';
		} else {
			return '';
		}
	}

	function sub_import(&$node) {
		$html = '';
		$c =& $node->childNodes;
		for ($i=0;$i<$node->childCount;$i++) {
			$html.=$c[$i]->toString();
		}
		$sql = "update part_richtext set".
		" html=".Database::text($html).
		" where part_id=".$this->id;
		Database::update($sql);
	}
	
}
?>