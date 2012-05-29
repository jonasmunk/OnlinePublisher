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
			<fields>
				<field label="Bud:">
					<text-input name="offerOffer"/>
				</field>
				<field label="Deadline:">
					<datetime-input name="offerExpiry"/>
				</field>
				<field label="Notat:">
					<text-input name="offerNote" lines="6"/>
				</field>
			</fields>
			<buttons>
				<button name="cancelOffer" title="Annuller"/>
				<button name="deleteOffer" title="Slet"/>
				<button name="saveOffer" title="Gem" highlighted="true"/>
			</buttons>
		</formula>
	</window>
	
	<window name="groupEditor" width="300" title="Gruppe" pad="5">
		<formula name="groupFormula">
			<fields>
				<field label="Titel:">
					<text-input key="title"/>
				</field>
				<field label="Notat:">
					<text-input key="note" lines="10"/>
				</field>
				<buttons>
					<button name="cancelGroup" title="Annuller"/>
					<button name="deleteGroup" title="Slet"/>
					<button name="saveGroup" title="Gem" highlighted="true" submit="true"/>
				</buttons>
			</fields>
		</formula>
	</window>
	
	<window name="typeEditor" width="300" title="Type" pad="5">
		<formula name="typeFormula">
			<fields>
				<field label="Titel:">
					<text-input name="typeTitle"/>
				</field>
				<field label="Notat:">
					<text-input name="typeNote" lines="10"/>
				</field>
				<buttons>
					<button name="deleteType" title="Slet"/>
					<button name="cancelType" title="Annuller"/>
					<button name="saveType" title="Gem" highlighted="true"/>
				</buttons>
			</fields>
		</formula>
	</window>
	
	<window name="productEditor" width="500" title="Produkt">
		<formula name="productFormula">
			<tabs small="true" centered="true">
				<tab title="Produkt" padding="5">
					<columns space="10">
						<column>
							<field label="Titel:">
								<text-input name="productTitle"/>
							</field>
						</column>
						<column>
							<field label="Nummer:">
								<text-input name="productNumber"/>
							</field>
						</column>
						<column>
							<field label="type">
								<dropdown name="productType" source="typeSource" adaptive="true"/>
							</field>
						</column>
					</columns>
					<columns flexible="true" space="5">
						<column>
							<field label="Beskrivelse:">				
								<text-input name="productNote" multiline="true"/>
							</field>
						</column>
						<column width="60px">
							<field label="Billede:">
								<image-input name="productImage" source="../../Services/Model/ImagePicker.php"/>
							</field>
						</column>
					</columns>
					<fields labels="above">
						<field label="Attributter:">
							<objectlist name="productAttributes">
								<text key="name" label="Navn"/>
								<text key="value" label="Værdi"/>
							</objectlist>
						</field>
					</fields>
				</tab>
				<tab title="Priser" padding="5">
				<fields labels="above">
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
				</fields>
				</tab>
				<tab title="Indstillinger" padding="5">
					<columns>
						<column>
							<fields>
								<field label="Søgbar:">
									<checkbox name="productSearchable"/>
								</field>
								<field label="Tillad bud:">
									<checkbox name="productAllowOffer"/>
								</field>
							</fields>
						</column>
						<column>
							<fields>
								<field label="Grupper:">
									<checkboxes name="productGroups">
										<items source="groupSource"/>
									</checkboxes>
								</field>
							</fields>
						</column>
					</columns>
				</tab>
			</tabs>
			<fields>
				<buttons>
					<button name="cancelProduct" title="Annuller"/>
					<button name="deleteProduct" title="Slet">
						<confirm text="Er du sikker?" ok="Ja,slet produkt" cancel="Nej"/>
					</button>
					<button name="saveProduct" title="Gem" highlighted="true"/>
				</buttons>
			</fields>
		</formula>
	</window>
</gui>';
In2iGui::render($gui);
?>