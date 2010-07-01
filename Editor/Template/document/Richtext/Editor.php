<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
	$sql="select * from document_richtext,document_section where document_richtext.section_id=document_section.id and document_section.id=".$sectionId;
	$row = Database::selectFirst($sql);
	$html = str_replace('\n', '', $row['data']);
	$html = str_replace('\r', '', $html);
	$html = str_replace('\t', '', $html);
	$output.=
	'<td style="'.$sectionStyle.'" id="selectedSectionTD" class="sectionTDtext sectionSelected">'.
	'<form name="RichTextForm" action="Richtext/Update.php" method="post" style="margin: 0px; padding: 0px;">'.
	'<input type="hidden" name="left" value="'.$row['left'].'"/>'.
	'<input type="hidden" name="right" value="'.$row['right'].'"/>'.
	'<input type="hidden" name="top" value="'.$row['top'].'"/>'.
	'<input type="hidden" name="bottom" value="'.$row['bottom'].'"/>'.
	'<textarea name="data" id="data" style="width: 100%; height: 300px;">'.encodeXML($row['data']).'</textarea>'.
	'<script language="javascript" type="text/javascript" src="../../Libraries/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>'.
	'<script language="javascript" type="text/javascript">
		// Notice: The simple theme does not use all options some of them are limited to the advanced theme
		tinyMCE.init({
			mode : "textareas",
			theme : "advanced",
			plugins : "table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,zoom,flash,searchreplace,print,contextmenu",
			theme_advanced_buttons1_add_before : "save,separator",
			theme_advanced_buttons1_add : "fontselect,fontsizeselect",
			theme_advanced_buttons2_add : "separator,insertdate,inserttime,preview,zoom,separator,forecolor,backcolor",
			theme_advanced_buttons2_add_before: "cut,copy,paste,separator,search,replace,separator",
			theme_advanced_buttons3_add_before : "tablecontrols,separator",
			theme_advanced_buttons3_add : "advhr,separator,print",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_path_location : "bottom",
			content_css : "'.$baseUrl.'style/'.$design.'/documentEditor.css"
		});
			function save() {
				document.forms.RichTextForm.submit();
			}
		function saveSection() {
			document.forms.RichTextForm.submit();
		}
	</script>'.
	/*
	FCKeditor
	'<input type="hidden" name="data" id="data" value="'.encodeXML($row['data']).'">'.
	'<script type="text/javascript" src="../../Libraries/FCKeditor_2/fckeditor.js"></script>'.
	'<script type="text/javascript">
<!--
// Automatically calculates the editor base path based on the _samples directory.
// This is usefull only for these samples. A real application should use something like this:
// oFCKeditor.BasePath = "/fckeditor/" ;	// "/fckeditor/" is the default value.


var oFCKeditor = new FCKeditor( "editor" ) ;
oFCKeditor.BasePath	= "../../Libraries/FCKeditor_2/" ;
//oFCKeditor.Config["CustomConfigurationsPath"] = oFCKeditor.BasePath + "_samples/html/sample06.config.js" ;
oFCKeditor.Config["CustomConfigurationsPath"] = "'.$baseUrl.'Editor/Template/document/RichText/EditorConfig.js";
oFCKeditor.ToolbarSet	= "PluginTest" ;
oFCKeditor.Height	= 350 ;
oFCKeditor.Config["SkinPath"] = "skins/silver/";
oFCKeditor.Config["AutoDetectLanguage"] = false;
oFCKeditor.Config["DefaultLanguage"] = "da";
oFCKeditor.Value	= \''.$html.'\' ;
//oFCKeditor.ToolbarSet = "richtext";
oFCKeditor.Create() ;
	function save() {
		var oEditor = FCKeditorAPI.GetInstance("editor") ;
		document.forms.RichTextForm.data.value=oEditor.GetXHTML( false );
		document.forms.RichTextForm.submit();
	}
//-->
			</script>'.
			*/
	/*
	Home made
	'<a onclick="rich.command(\'bold\')">fed</a> '.
	'<a onclick="rich.command(\'italic\')">kursiv</a> '.
	'<a onclick="rich.command(\'underline\')">understreget</a> '.
	'<a onclick="rich.format(\'del\')">slet</a> '.
	'<iframe id="IFRAME" width="100%" height="200" src="Richtext/Empty.html" style="border: solid 1px #aaa; margin-top: 3px;" frameborder="0"></iframe>'.
	'<table width="200"></table>'.
	'<script src="Richtext/Script.js"></script>'.
	*/	
	'</form>'.
	'<script>
	parent.Toolbar.location=\'Richtext/Toolbar.php?\'+Math.random();
	</script>'.
	'</td>';
?>