<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Customers
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Interface/In2iGui.php';

$gui='
<gui xmlns="uri:hui" padding="10" title="Butik">
	<controller source="controller.js"/>
	<source name="productListSource" url="data/ListProducts.php"/>
	<source name="groupSource" url="../../Services/Model/Items.php?type=productgroup"/>
	<source name="typeSource" url="../../Services/Model/Items.php?type=producttype"/>
	<structure>
		<top>
		<toolbar>
			<icon icon="common/product" title="Nyt produkt" name="newProduct" overlay="new"/>
			<icon icon="common/folder" title="Ny gruppe" name="newGroup" overlay="new"/>
			<icon icon="common/folder" title="Ny type" name="newType" overlay="new"/>
		</toolbar>
		</top>
		<middle>
			<left>
				<selection value="product" name="selector">
					<item icon="common/product" title="Alle produkter" value="product"/>
					<item icon="common/email" title="Alle bud" value="productoffer"/>
					<item icon="common/folder" title="Alle grupper" value="productgroup"/>
					<title>Grupper</title>
					<items source="groupSource"/>
					<title>Typer</title>
					<items source="typeSource"/>
				</selection>
			</left>
			<center>
				<overflow>
					<list name="list" source="productListSource"/>
				</overflow>
			</center>
		</middle>
		<bottom/>
	</structure>
	
	<window name="offerEditor" width="300" title="Bud" pad="5">
		<formula name="offerFormula">
			<group>
				<text name="offerOffer" label="Bud:"/>
				<datetime name="offerExpiry" label="Deadline:"/>
				<text name="offerNote" label="Notat:" lines="6"/>
				<buttons>
					<button name="cancelOffer" title="Annuller"/>
					<button name="deleteOffer" title="Slet"/>
					<button name="saveOffer" title="Gem" highlighted="true"/>
				</buttons>
			</group>
		</formula>
	</window>
	
	<window name="groupEditor" width="300" title="Gruppe" pad="5">
		<formula name="groupFormula">
			<group>
				<text key="title" label="Titel:"/>
				<text key="note" label="Notat:" lines="10"/>
				<buttons>
					<button name="cancelGroup" title="Annuller"/>
					<button name="deleteGroup" title="Slet"/>
					<button name="saveGroup" title="Gem" highlighted="true" submit="true"/>
				</buttons>
			</group>
		</formula>
	</window>
	
	<window name="typeEditor" width="300" title="Type" pad="5">
		<formula name="typeFormula">
			<group>
				<text name="typeTitle" label="Titel:"/>
				<text name="typeNote" label="Notat:" lines="10"/>
				<buttons>
					<button name="deleteType" title="Slet"/>
					<button name="cancelType" title="Annuller"/>
					<button name="saveType" title="Gem" highlighted="true"/>
				</buttons>
			</group>
		</formula>
	</window>
	
	<window name="productEditor" width="500" title="Produkt">
		<formula name="productFormula">
			<tabs small="true" centered="true">
				<tab title="Produkt" padding="5">
					<columns space="10">
						<column>
							<group labels="above">
								<text name="productTitle" label="Titel:"/>
							</group>
						</column>
						<column>
							<group labels="above">
								<text name="productNumber" label="Nummer:"/>
							</group>
						</column>
						<column>
							<group labels="above">
								<dropdown label="type" name="productType" source="typeSource"/>
							</group>
						</column>
					</columns>
					<columns flexible="true" space="5">
						<column>
							<group labels="above">				
								<text name="productNote" label="Beskrivelse:" lines="6"/>
							</group>
						</column>
						<column width="60px">
							<group labels="above">
								<imagepicker label="Billede:" name="productImage" source="../../Services/Model/ImagePicker.php"/>
							</group>
						</column>
					</columns>
					<group labels="above">
						<field label="Attributter:">
							<objectlist name="productAttributes">
								<text key="name" label="Navn"/>
								<text key="value" label="Værdi"/>
							</objectlist>
						</field>
					</group>
				</tab>
				<tab title="Priser" padding="5">
				<group labels="above">
					<field label="Priser:">
						<objectlist name="productPrices">
							<text key="amount" label="Antal"/>
							<select label="Enhed:" key="type">
								<option value="unit" label="enhed"/>
								<option value="meter" label="meter"/>
								<option value="squaremeter" label="kvadratmeter"/>
								<option value="cubicmeter" label="kubikmeter"/>
								<option value="gram" label="gram"/>
							</select>
							<text key="price" label="Pris"/>
							<select key="currency" label="Valuta">
								<option value="DKK" label="Dansk krone"/>
								<option value="EUR" label="Euro"/>
								<option value="USD" label="Amerikansk dollar"/>
							</select>
						</objectlist>
					</field>
				</group>
				</tab>
				<tab title="Indstillinger" padding="5">
					<columns>
						<column>
							<group>
								<checkbox label="Søgbar:" name="productSearchable"/>
								<checkbox label="Tillad bud:" name="productAllowOffer"/>
							</group>
						</column>
						<column>
							<group>
								<checkboxes label="Grupper:" name="productGroups">
									<items source="groupSource"/>
								</checkboxes>
							</group>
						</column>
					</columns>
				</tab>
			</tabs>
			<group>
				<buttons>
					<button name="cancelProduct" title="Annuller"/>
					<button name="deleteProduct" title="Slet">
						<confirm text="Er du sikker?" ok="Ja,slet produkt" cancel="Nej"/>
					</button>
					<button name="saveProduct" title="Gem" highlighted="true"/>
				</buttons>
			</group>
		</formula>
	</window>
</gui>';
In2iGui::render($gui);
?>