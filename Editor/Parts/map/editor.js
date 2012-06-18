var partController = {
	markers : null,
	
	$ready : function() {
		mapWindow.show();
		var form = document.forms.PartForm;
		var values = {
			maptype : form.maptype.value,
			zoom : form.zoom.value,
			provider : form.provider.value,
			width : form.mapwidth.value,
			height : form.mapheight.value
		};
		var markers = hui.string.fromJSON(form.markers.value);
		if (hui.isArray(markers) && markers.length>0) {
			values.text = markers[0].text;
			values.point = markers[0].point;
		}
		mapFormula.setValues(values)
	},
	$valuesChanged$mapFormula : function(values) {
		var form = document.forms.PartForm;
		form.provider.value = values.provider;
		form.mapwidth.value = values.width;
		form.mapheight.value = values.height;
		form.maptype.value = values.maptype;
		form.zoom.value = values.zoom;
		var markers = [{
			text : values.text,
			point : values.point
		}];
		form.markers.value = hui.string.toJSON(markers);
		this.preview();
	},
	preview : function() {
		op.part.utils.updatePreview({
			node : hui.get('part_map_container'),
			form : document.forms.PartForm,
			type : 'map',
			delay : 500
		});
	},
	setMarkers : function(markers) {
		hui.log(markers)
		this.markers = markers;
	}
};

hui.ui.listen(partController);