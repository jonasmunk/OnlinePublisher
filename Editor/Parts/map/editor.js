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
			height : form.mapheight.value,
			frame : form.frame.value,
			center : {latitude:parseFloat(form.latitude.value),longitude:parseFloat(form.longitude.value)}
		};
		var markers = hui.string.fromJSON(form.markers.value);
		/*if (hui.isArray(markers) && markers.length>0) {
			values.text = markers[0].text;
			values.point = markers[0].point;
		}*/
		mapFormula.setValues(values)
	},
	$valuesChanged$mapFormula : function(values) {
		var form = document.forms.PartForm;
		form.provider.value = values.provider;
		form.mapwidth.value = values.width;
		form.mapheight.value = values.height;
		form.maptype.value = values.maptype;
		form.zoom.value = values.zoom;
		form.latitude.value = values.center ? values.center.latitude : '';
		form.longitude.value = values.center ? values.center.longitude : '';
		form.frame.value = values.frame;
		/*var markers = [{
			text : values.text,
			point : values.point
		}];
		form.markers.value = hui.string.toJSON(markers);*/
		this.preview();
	},
	preview : function() {
		op.part.utils.updatePreview({
			node : hui.get('part_map_container'),
			form : document.forms.PartForm,
			type : 'map',
			delay : 500,
			runScripts : true
		});
	},
	setMarkers : function(markers) {
		hui.log(markers)
		this.markers = markers;
	}
};

hui.ui.listen(partController);