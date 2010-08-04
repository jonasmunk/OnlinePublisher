<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/In2iGui.php';
require_once 'Functions.php';

$id = getDocumentColumn();

$sql = "select * from document_column where id=".$id;
$row = Database::selectFirst($sql);

$gui='
<gui xmlns="uri:In2iGui" title="Toolbar">
	<controller source="js/ColumnToolbar.js"/>
	<script>
	columnToolbar.columnId='.getDocumentColumn().';
	</script>
	<tabs small="true" below="true">
		<tab title="Kolonne" background="light">
			<toolbar>
				<icon icon="common/stop" title="Annuller" name="cancel"/>
				<icon icon="common/save" title="Gem" name="save"/>
				<icon icon="common/delete" title="Slet" name="delete">
					<confirm text="Er du sikker?" ok="Ja, slet" cancel="Nej"/>
				</icon>
				<divider/>
				<dropdown label="Bredde" name="width" value="'.$row['width'].'">
					<item value="" title="Tilpas indhold"/>
					<item value="1%" title="Mindst mulig"/>
					<item value="100%" title="Størst mulig"/>
					'.buildWidths().'
				</dropdown>
			</toolbar>
		</tab>
	</tabs>
</gui>
';
In2iGui::render($gui);

function buildWidths() {
	$out="";
	for ($i=1;$i<20;$i++) {
		$out.='<item value="'.($i*5).'%" title="'.($i*5).'%"/>';
	}
	for ($i=1;$i<71;$i++) {
		$out.='<item value="'.($i*10).'" title="'.($i*10).' pixel"/>';
	}
	return $out;
}
?>