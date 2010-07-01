<?php
/**
 * @package OnlinePublisher
 * @subpackage Developer
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Classes/In2iGui.php';


$gui='
<gui xmlns="uri:In2iGui" context="../../../../">
	<view>
		<toolbar>
			<icon icon="common/smiley" title="Ny person" name="newPerson"/>
			<icon icon="common/folder" title="Open"/>
			<search title="Søgning" object="search"/>
			<icon icon="common/folder" title="Icons" name="changeToIconView"/>
			<icon icon="common/folder" title="List" name="changeToListView"/>
		</toolbar>
		<content>
			<split>
				<sidebar>
					<selector name="selector">
						<item title="Alt" badge="12" icon="common/folder" value=""/>
						<item title="Billeder" badge="12" icon="common/folder" value="image"/>
						<item title="Filer" badge="12" icon="common/folder" value="file" selected="true"/>
						<item title="Nyheder" badge="12" icon="common/folder" value="news"/>
						<item title="Personer" badge="12" icon="common/folder" value="person"/>
						<item title="Projekter" badge="12" icon="common/folder" value="project"/>
						<item title="Begivenheder" badge="12" icon="common/folder" value="event"/>
					</selector>
				</sidebar>
				<content>
					<overflow>
					<viewstack name="viewStack">
						<content name="listView">
							<list source="ListData.php" name="list">
								<window/>
								<column title="Titel"/>
								<column title="Notat"/>
								<column title="Oprettet"/>
							</list>
						</content>
						<content name="iconView">
							<icons source="IconData.php" name="icons">
							
							</icons>
						</content>
					</viewstack>
					</overflow>
					<!--<browser source="BrowserData.php"/>-->
				</content>
			</split>
		</content>
	</view>
	<window floating="true" title="My stuff" name="editor">
		<formula name="formula">
			<group>
				<text label="Titel" name="editorTitle"/>
				<text label="Notat" name="editorNote" lines="10"/>
				<button title="Gem" name="editorSave"/>
			</group>
		</formula>
	</window>
	<window floating="true" title="Person" name="personEditor" width="500">
		<tabs>
			<tab title="Egenskaber">
				<formula name="personFormula">
					<split>
						<content>
							<group legend="Navn">
								<text label="Fornavn" name="personFirstname"/>
								<text label="Mellemnavn" name="personMiddlename"/>
								<text label="Efternavn" name="personSurname"/>
								<text label="Initialer" name="personInitials"/>
								<text label="Kaldenavn" name="personNickname"/>
							</group>
						</content>
						<content>
							<group legend="Adresse">
								<text label="Gade" name="personStreetname"/>
								<text label="Postnr." name="personZipcode"/>
								<text label="By" name="personCity"/>
								<text label="Land" name="personCountry"/>
							</group>
						</content>
					</split>
					<group>
						<button title="Gem" name="personSave"/>
					</group>
				</formula>
			</tab>
			<tab title="Links">
			
			</tab>
		</tabs>
	</window>
	<script source="Layout.js"/>
</gui>
';

In2iGui::render($gui);
?>