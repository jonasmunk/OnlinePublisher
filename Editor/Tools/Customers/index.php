<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Customers
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Interface/In2iGui.php';

$gui='
<gui xmlns="uri:hui" padding="10" title="Customers">
	<controller source="controller.js"/>
	<source name="personGroupSource" url="../../Services/Model/Items.php?type=persongroup"/>
	<source name="mailinglistSource" url="../../Services/Model/Items.php?type=mailinglist"/>
	<source name="personListSource" url="ListPersons.php">
		<parameter key="windowPage" value="@list.window.page"/>
		<parameter key="sort" value="@list.sort.key"/>
		<parameter key="direction" value="@list.sort.direction"/>
		<parameter key="query" value="@search.value"/>
		<parameter key="kind" value="@selector.kind"/>
		<parameter key="value" value="@selector.value"/>
	</source>
	<source name="mailinglistListSource" url="../../Services/Model/ListObjects.php?type=mailinglist">
		<parameter key="windowPage" value="@list.window.page"/>
		<parameter key="query" value="@search.value"/>
	</source>
	<source name="groupListSource" url="../../Services/Model/ListObjects.php?type=persongroup">
		<parameter key="windowPage" value="@list.window.page"/>
		<parameter key="query" value="@search.value"/>
	</source>
	
	<structure>
		<top>
		<toolbar>
			<icon icon="common/user" title="Ny person" name="newPerson" overlay="new"/>
			<icon icon="common/folder" title="Ny gruppe" name="newGroup" overlay="new"/>
			<icon icon="common/email" title="Ny postliste" name="newMailinglist" overlay="new"/>
			<divider/>
			<icon icon="common/letter" title="Send email" name="sendEmail"/>
			<right>
				<searchfield title="Søgning" name="search" expandedWidth="200"/>
			</right>
		</toolbar>
		</top>
		<middle>
			<left>
				<overflow>
					<selection value="person" name="selector">
						<item icon="common/person" title="Alle personer" value="person"/>
						<item icon="common/email" title="Alle postlister" value="mailinglist"/>
						<item icon="common/folder" title="Alle grupper" value="persongroup"/>
						<items name="groupSelection" source="personGroupSource" title="Grupper"/>
						<items name="mailinglistSelection" source="mailinglistSource" title="Postlister"/>
					</selection>
				</overflow>
			</left>
			<center>
				<overflow>
					<list name="list" source="personListSource"/>
				</overflow>
			</center>
		</middle>
		<bottom/>
	</structure>
	
	<window name="personEditor" width="460" title="Person">
		<formula name="personFormula">
			<tabs small="true" centered="true">
				<tab title="Person" padding="5">
					<columns flexible="true" space="10">
						<column>
							<columns space="10">
								<column>
									<group labels="above">
										<text name="personFirstname" label="Fornavn:"/>
									</group>
								</column>
								<column>
									<group labels="above">
										<text name="personMiddlename" label="Mellemnavn:"/>
									</group>
								</column>
								<column>
								<group labels="above">
									<text name="personSurname" label="Efternavn:"/>
								</group>
								</column>
							</columns>
							<columns space="10">
								<column>
									<group labels="above">
										<text name="personJobtitle" label="Jobtitel:"/>
									</group>
								</column>
								<column>
									<group labels="above">
										<text name="personInitials" label="Initialer:"/>
									</group>
								</column>
								<column>
								<group labels="above">
									<text name="personNickname" label="Kaldenavn:"/>
								</group>
								</column>
							</columns>
						</column>
						<column width="70px">
							<group labels="above">
								<imagepicker label="Billede:" name="personImage" source="../../Services/Model/ImagePicker.php"/>
							</group>
						</column>
					</columns>
					<columns space="10">
						<column>
							<group labels="above">
								<custom label="Email:">
									<objectlist name="personEmails">
										<text key="address"/>
									</objectlist>
								</custom>
								<custom label="Telefon:">
									<objectlist name="personPhones">
										<text key="number" label="Nummer"/>
										<text key="context" label="Kontekst"/>
									</objectlist>
								</custom>
							</group>
							<group>
								<radiobuttons name="personSex" label="Køn:" value="1">
									<radiobutton value="1" label="Mand"/>
									<radiobutton value="0" label="Kvinde"/>
								</radiobuttons>
							</group>
						</column>
						<column>
							<group labels="above">
								<text name="personStreetname" label="Adresse:"/>
							</group>
							
							<columns space="10">
								<column>
									<group labels="above">
										<text name="personZipcode" label="Postnr:"/>
									</group>
								</column>
								<column>
									<group labels="above">
										<text name="personCity" label="By:"/>
									</group>
								</column>
							</columns>
							<group labels="above">
								<text name="personCountry" label="Land:"/>
								<text name="personWebaddress" label="Internet:"/>
							</group>
						</column>
					</columns>
				</tab>
				<tab title="Indstillinger" padding="10">
					<columns space="5">
						<column>
							<group>
								<checkboxes label="Postlister:" name="personMailinglists">
									<items source="mailinglistSource"/>
								</checkboxes>
								<checkbox label="Søgbar:" name="personSearchable"/>
							</group>
						</column>
						<column>
							<group>
								<checkboxes label="Grupper:" name="personGroups">
									<items source="personGroupSource"/>
								</checkboxes>
							</group>
						</column>
					</columns>
				</tab>
				<tab title="Information" padding="10">
					<group labels="above">
						<text name="personNote" label="Notat:" lines="5"/>
					</group>
				</tab>
			</tabs>
			<buttons padding="5">
				<button name="deletePerson" title="Slet"/>
				<button name="cancelPerson" title="Annuller"/>
				<button name="savePerson" title="Gem" highlighted="true"/>
			</buttons>
		</formula>
	</window>
	
	<window name="mailinglistEditor" width="300" title="Postliste" padding="5">
		<formula name="mailinglistFormula">
			<group>
				<text name="mailinglistTitle" label="Titel:"/>
				<text name="mailinglistNote" label="Notat:" lines="10"/>
				<buttons top="5">
					<button name="cancelMailinglist" title="Annuller"/>
					<button name="deleteMailinglist" title="Slet"/>
					<button name="saveMailinglist" title="Gem" highlighted="true"/>
				</buttons>
			</group>
		</formula>
	</window>
	
	<window name="groupEditor" width="300" title="Gruppe" padding="5">
		<formula name="groupFormula">
			<group>
				<text name="groupTitle" label="Titel:"/>
				<text name="groupNote" label="Notat:" lines="10"/>
				<buttons>
					<button name="cancelGroup" title="Annuller"/>
					<button name="deleteGroup" title="Slet"/>
					<button name="saveGroup" title="Gem" highlighted="true"/>
				</buttons>
			</group>
		</formula>
	</window>
	
</gui>';
In2iGui::render($gui);
?>