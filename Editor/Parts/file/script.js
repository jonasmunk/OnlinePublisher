var partController = {
	$ready : function() {
		this.showFinder();
	},
	showFinder : function() {
		if (!this.finder) {
			this.finder = hui.ui.Finder.create({
				title : 'Vælg fil',
				list : {url : '../../Services/Finder/FilesList.php'},
				selection : {value : 'all', parameter : 'group', url : '../../Services/Finder/FilesSelection.php'},
				search : {parameter : 'query'}
			});
			this.finder.listen({
				$select : function(obj) {
					document.forms.PartForm.fileId.value = obj.id;
					this.preview();
				}.bind(this)
			})
		}
		this.finder.show();
	},
	preview : function() {
		op.part.utils.updatePreview({
			node : 'part_file_container',
			form : document.forms.PartForm,
			type : 'file'
		});
	}
}

hui.ui.listen(partController);