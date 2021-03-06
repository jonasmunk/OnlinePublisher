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
        var values = hui.form.getValues(this.form);
        if (values.imageId) {
            values.image = {id:values.imageId}
        }
        if (values.fileId) {
            values.file = {id:values.fileId,title:'TODO'}
        }
        hui.log(values);
        movieInfoFormula.setValues(values);
	},
	$resolveImageUrl : function(img,width,height) {
		return '../../../services/images/?id='+img.id+'&width='+width+'&height='+height+'&format=jpg';
	},
	showFinder : function() {
		if (!this.finder) {
			this.finder = hui.ui.Finder.create({
                url : '../../Services/Finder/Files.php'
			});
			this.finder.listen({
				$select : function(obj) {
					this.form.fileId.value = obj.id;
					this.preview();
                    this.finder.hide();
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
			$object : function(status) {
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

    showInfo : function() {
        movieInfoWindow.show();
    },

    $valuesChanged$movieInfoFormula : function(values) {
        this.form.movieWidth.value = values.movieWidth;
        this.form.movieHeight.value = values.movieHeight;
        this.form.text.value = values.text;
        this.form.code.value = values.code;
        if (values.image) {
            this.form.imageId.value = values.image.id;
        }
        if (values.file) {
            this.form.fileId.value = values.file.id;
        }
        this.form.url.value = values.url;
        this.preview(true);
    }
}

hui.ui.listen(partController);