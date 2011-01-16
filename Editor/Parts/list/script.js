op.part.List = {
	data : null,
	setData : function(data) {
		this.data = data;
	},
	$ready : function() {
		listWindow.show();
		dataFormula.setValues(this.data);
	},
	$valueChanged$group : function(value) {
		this.preview();
	},
	$valuesChanged$formula : function(values) {
		document.forms.PartForm.title.value = values.title;
		document.forms.PartForm.time_count.value = values.time_count;
		document.forms.PartForm.maxitems.value = values.maxitems;
		document.forms.PartForm.show_source.value = values.show_source;
		this.preview();
	},
	$valuesChanged$dataFormula : function(values) {
		var x = [values.newsGroups,values.newsSources,values.calendars,values.calendarSources];
		var objects = [];
		for (var i=0; i < x.length; i++) {
			for (var j=0; j < x[i].length; j++) {
				objects.push(x[i][j]);
			};
		};
		document.forms.PartForm.objects.value = objects.join(',');
		this.preview();
	},
	preview : function() {
		op.part.utils.updatePreview({
			node:'part_list_container',
			form:document.forms.PartForm,
			type:'list'
		});
	}
}

ui.listen(op.part.List);