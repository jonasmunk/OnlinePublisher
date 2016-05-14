var partController = {
	$ready : function() {
		//this.buildWindow();
	},
	preview : function() {
		var url = controller.context+'Editor/Services/Parts/Preview.php?type=imagegallery';
		var parms = hui.form.getValues(document.forms.PartForm);
		hui.ui.request({
			method : 'post',
			url : url,
			parameters : parms,
			$success : function(t) {
				var node = hui.get('part_imagegallery_container');
				hui.dom.replaceHTML(node,t.responseText);
				hui.dom.runScripts(node);
		}});
	}
}

hui.ui.listen(partController);