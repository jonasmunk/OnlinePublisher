var partController = {
    form : null,
    
	$ready : function() {
		fileUpload.addDropTarget({
			element : hui.get('part_movie_container'),
			hoverClass : 'editor_drop',
			$drop : function() {
				fileUploadWindow.show();
			}
		});
        this.form = document.forms.PartForm;
        movieInfoWindow.show();
        movieInfoFormula.setValues(hui.form.getValues(this.form));
	},
	$resolveImageUrl : function(img,width,height) {
		return '../../../services/images/?id='+img.id+'&width='+width+'&height='+height+'&format=jpg';
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
					this.form.fileId.value = obj.id;
					this.preview();
				}.bind(this)
			})
		}
		this.finder.show();
	},
	preview : function(delayed) {
		op.part.utils.updatePreview({
			node : 'part_movie_container',
			form : this.form,
			type : 'movie',
			delay : delayed ? 300 : 0
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
	},
  
    /////////////////// Info /////////////////////

    $valuesChanged$movieInfoFormula : function(values) {
        this.form.movieWidth.value = values.movieWidth;
        this.form.movieHeight.value = values.movieHeight;
        this.form.text.value = values.text;
        this.preview(true);
    }
}

hui.ui.listen(partController);