op.part.News = {
	updatePreview : function() {
		op.part.utils.updatePreview({
			node:$('part_news_preview'),
			form:document.forms.PartForm,
			type:'news'
		});
	}
}