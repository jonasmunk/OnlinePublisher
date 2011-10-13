<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Links
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Interface/In2iGui.php';

$gui='
<gui xmlns="uri:hui" padding="10" title="System">
	<controller source="controller.js"/>
	<source name="listSource" url="LinkList.php">
		<parameter key="source" value="@sourceSelector.value"/>
		<parameter key="target" value="@targetSelector.value"/>
	</source>
	<layout>
		<top>
			<toolbar>
				<segmented value="list" label="Visning">
					<item icon="view/list" value="list"/>
					<item icon="view/graph" value="graph"/>
				</segmented>
			</toolbar>
		</top>
		<middle>
			<left>
				<selection value="all" name="sourceSelector">
					<title>Kilde</title>
					<item icon="common/folder" title="Alle" value="all"/>
					<item icon="common/page" title="Sider" value="page"/>
					<item icon="common/news" title="Nyheder" value="news"/>
					<item icon="common/hierarchy" title="Hierarkier" value="hierarchy"/>
				</selection>
				<selection value="all" name="targetSelector">
					<title>Mål</title>
					<item icon="common/folder" title="Alle" value="all"/>
					<item icon="common/internet" title="Adresser" value="url"/>
					<item icon="common/email" title="E-mail-adresser" value="email"/>
					<item icon="common/page" title="Sider" value="page"/>
					<item icon="file/generic" title="Filer" value="file"/>
				</selection>
			</left>
			<middle>
				<overflow>
					<list name="list" source="listSource"/>
				</overflow>
			</middle>
		</middle>
		<bottom/>
	</layout>
</gui>';
//$gui='<gui xmlns="uri:hui" pad="10" title="System"><upload/></gui>';

In2iGui::render($gui);
?>