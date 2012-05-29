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
				<field title="Søgning">
					<searchfield name="search" expanded-width="200"/>
				</field>
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
				<tab title="Person" padding="10">
					<columns flexible="true" space="10">
						<column>
							<columns space="10">
								<column>
									<field label="Fornavn:">
										<text-input name="personFirstname"/>
									</field>
								</column>
								<column>
									<field label="Mellemnavn:">
										<text-input name="personMiddlename"/>
									</field>
								</column>
								<column>
									<field label="Efternavn:">
										<text-input name="personSurname"/>
									</field>
								</column>
							</columns>
							<columns space="10">
								<column>
									<field label="Jobtitel:">
										<text-input name="personJobtitle"/>
									</field>
								</column>
								<column>
									<field label="Initialer:">
										<text-input name="personInitials"/>
									</field>
								</column>
								<column>
									<field label="Kaldenavn:">
										<text-input name="personNickname"/>
									</field>
								</column>
							</columns>
						</column>
						<column width="70px">
							<field label="Billede">
								<image-input name="personImage" source="../../Services/Model/ImagePicker.php"/>
							</field>
						</column>
					</columns>
					<columns space="10">
						<column>
							<fields labels="above">
								<field label="Email:">
									<objectlist name="personEmails">
										<text key="address"/>
									</objectlist>
								</field>
								<field label="Telefon:">
									<objectlist name="personPhones">
										<text key="number" label="Nummer"/>
										<text key="context" label="Kontekst"/>
									</objectlist>
								</field>
							</fields>
							<fields>
								<field label="Køn:">
									<radiobuttons name="personSex" value="1">
										<radiobutton value="1" label="Mand"/>
										<radiobutton value="0" label="Kvinde"/>
									</radiobuttons>
								</field>
							</fields>
						</column>
						<column>
							<field label="Adresse:">
								<text-input name="personStreetname"/>
							</field>
							
							<columns space="10">
								<column>
									<field label="Postnr:">
										<text-input name="personZipcode"/>
									</field>
								</column>
								<column>
									<field label="By:">
										<text-input name="personCity"/>
									</field>
								</column>
							</columns>
							<fields labels="above">
								<field label="Land:">
									<text-input name="personCountry"/>
								</field>
								<field label="Internet:">
									<text-input name="personWebaddress"/>
								</field>
							</fields>
						</column>
					</columns>
				</tab>
				<tab title="Indstillinger" padding="10">
					<columns space="5">
						<column>
							<fields>
								<field label="Postlister:">
									<checkboxes name="personMailinglists">
										<items source="mailinglistSource"/>
									</checkboxes>
								</field>
								<field label="Søgbar:">
									<checkbox name="personSearchable"/>
								</field>
							</fields>
						</column>
						<column>
							<fields>
								<field label="Grupper:">
									<checkboxes name="personGroups">
										<items source="personGroupSource"/>
									</checkboxes>
								</field>
							</fields>
						</column>
					</columns>
				</tab>
				<tab title="Information" padding="10">
					<field label="Notat:">
						<text-input name="personNote" multiline="true"/>
					</field>
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
			<fields>
				<field label="Titel:">
					<text-input name="mailinglistTitle"/>
				</field>
				<field label="Notat:">
					<text-input name="mailinglistNote" lines="10"/>
				</field>
				<buttons top="5">
					<button name="cancelMailinglist" title="Annuller"/>
					<button name="deleteMailinglist" title="Slet"/>
					<button name="saveMailinglist" title="Gem" highlighted="true"/>
				</buttons>
			</fields>
		</formula>
	</window>
	
	<window name="groupEditor" width="300" title="Gruppe" padding="5">
		<formula name="groupFormula">
			<fields>
				<field label="Titel:">
					<text-input name="groupTitle"/>
				</field>
				<field label="Notat:">
					<text-input name="groupNote" lines="10"/>
				</field>
				<buttons>
					<button name="cancelGroup" title="Annuller"/>
					<button name="deleteGroup" title="Slet"/>
					<button name="saveGroup" title="Gem" highlighted="true"/>
				</buttons>
			</fields>
		</formula>
	</window>
	
</gui>';
In2iGui::render($gui);
?>