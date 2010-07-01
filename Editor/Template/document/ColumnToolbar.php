<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once 'Functions.php';

$id = getDocumentColumn();

$sql = "select * from document_column where id=".$id;
$row = Database::selectFirst($sql);

$gui=
'<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<dock xmlns="uri:Dock" orientation="Top">'.
'<tabgroup align="left">'.
'<tab title="Kolonne" style="Hilited"/>'.
'</tabgroup>'.
'<content>'.
'<tool title="Annuller" icon="Basic/Stop" link="Editor.php?column=0" target="Editor"/>'.
'<tool title="Gem" icon="Basic/Save" link="javascript: document.forms.Formula.submit();"/>'.
'<tool title="Slet" icon="Basic/Delete" link="DeleteColumn.php?column='.$id.'" target="Editor"/>'.
'<divider/>'.
'<form xmlns="uri:BarForm" name="Formula" target="Editor" action="UpdateColumn.php" method="post">'.
'<group>'.
'<top>'.
'<badge>Bredde:</badge>'.
'<select name="width" selected="'.$row['width'].'" onchange="changeColumnWidth(this.value);">'.
'<option value="" title="Auto"/>'.
'<option value="min" title="Mindst"/>'.
'<option value="max" title="Størst"/>'.
buildWidths().
'</select>'.
'</top>'.
'</group>'.
'</form>'.
'<flexible/>'.
'<script xmlns="uri:Script">
function changeColumnWidth(width) {
	if (width=="min") width="1%";
	if (width=="max") width="100%";
	parent.Frame.EditorFrame.getDocument().getElementById(\'column'.$id.'\').setAttribute(\'width\',width);
}
</script>'.
'</content>'.
'</dock>'.
'</xmlwebgui>';

$elements = array("Dock","BarForm","Script");
writeGui($xwg_skin,$elements,$gui);

function buildWidths() {
	$out="";
	for ($i=1;$i<20;$i++) {
		$out.='<option value="'.($i*5).'%" title="'.($i*5).'%"/>';
	}
	for ($i=1;$i<71;$i++) {
		$out.='<option value="'.($i*10).'" title="'.($i*10).' pixel"/>';
	}
	return $out;
}
?>