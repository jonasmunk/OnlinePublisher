var partController = {
	$ready : function() {
		//this.showFinder();
	},
	showFinder : function() {
		if (!this.finder) {
			this.finder = hui.ui.Finder.create({
				title : {en:'Select file',da:'Vælg fil'},
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
	},
	addFile : function() {
		fileUploadWindow.show();
	},
	$uploadDidCompleteQueue$fileUpload : function() {
		hui.ui.request({
			'url' : '../../Parts/file/UploadStatus.php',
			onJSON : function(status) {
				if (status.id) {
					document.forms.PartForm.fileId.value = status.id;
					this.preview();
				} else {
					hui.ui.showMessage({text:'Det lykkedes ikke at overføre filen, måske er den for stor',icon:'common/warning',duration:3000});
				}
			}.bind(this)
		});
	},
	$click$cancelUpload : function() {
		fileUploadWindow.hide();
	}
}

hui.ui.listen(partController);