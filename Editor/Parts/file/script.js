op.part.File = {
	$ready : function() {
		this.showFinder();
	},
	showFinder : function() {
		var finder = hui.ui.Finder.create({
			title : 'Vælg fil',
			list : {url : '../../Services/Finder/FilesList.php'},
			selection : {value : 'all', url : '../../Services/Finder/FilesSelection.php'},
			search : {parameter:'query'}
		});
		finder.listen({
			$select : function(obj) {
				document.forms.PartForm.fileId.value = obj.id;
				this.preview();
			}.bind(this)
		})
		finder.show();
	},
	preview : function() {
		op.part.utils.updatePreview({
			node : 'part_file_container',
			form : document.forms.PartForm,
			type : 'file'
		});
	}
}

hui.ui.listen(op.part.File);