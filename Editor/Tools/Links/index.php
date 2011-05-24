<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Links
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/In2iGui.php';

$gui='
<gui xmlns="uri:hui" padding="10" title="System">
	<controller source="controller.js"/>
	<source name="listSource" url="LinkList.php">
		<parameter key="subset" value="@selector.value"/>
	</source>
	<layout>
		<top>
			<toolbar>
			</toolbar>
		</top>
		<middle>
			<left>
				<selection value="all" name="selector">
					<item icon="common/folder" title="Alle" value="all"/>
					<title>Kilder</title>
					<item icon="common/page" title="Sider" value="source-page"/>
					<item icon="common/hierarchy" title="Hierarkier" value="source-hierarchy"/>
					<!--title>Mål</title>
					<item icon="common/internet" title="Udgående" value="target-external"/>
					<item icon="common/email" title="E-mail adresser" value="target-email"/>
					<item icon="common/page" title="Sider" value="target-page"/>
					<item icon="file/generic" title="Filer" value="target-file"/-->
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