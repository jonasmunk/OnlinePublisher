op.part.News = {
	$ready : function() {
		newsWindow.show();
		this.form = document.forms.PartForm;
		newsGroups.setValue(n2i.toIntArray(this.form.groups.value));
	},
	$valueChanged$newsTitle : function(value) {
		this.form.title.value = value;
		this.updatePreview();
	},
	$valueChanged$newsGroups : function(value) {
		this.form.groups.value=value.join(',');
		this.form.mode.value='groups';
		this.updatePreview();
		newsNews.setValue();
	},
	$valueChanged$newsNews : function(value) {
		newsGroups.setValue([]);
		this.form.news.value=value;
		this.form.mode.value='single';
		this.updatePreview();
	},
	$valueChanged$newsVariant : function(value) {
		this.form.variant.value = value;
		this.updatePreview();
	},
	$valueChanged$newsSortDir : function(value) {
		this.form.sortdir.value = value;
		this.updatePreview();
	},
	$valueChanged$newsSortBy : function(value) {
		this.form.sortby.value = value;
		this.updatePreview();
	},
	$valueChanged$newsAlign : function(value) {
		this.form.align.value = value;
		this.updatePreview();
	},
	$valueChanged$newsMaxItems : function(value) {
		this.form.maxitems.value = value;
		this.updatePreview();
	},
	$valueChanged$newsTimeType : function(value) {
		this.form.timetype.value = value;
		this.updatePreview();
	},
	$valueChanged$newsTimeCount : function(value) {
		this.form.timecount.value = value;
		this.updatePreview();
	},
	updatePreview : function() {
		op.part.utils.updatePreview({
			node:'part_news_preview',
			form:document.forms.PartForm,
			type:'news'
		});
	}
}
ui.listen(op.part.News);