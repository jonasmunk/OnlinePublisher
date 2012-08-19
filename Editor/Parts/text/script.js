var partController = {
	$ready : function() {
		var field = hui.get('PartTextTextarea');
		this.resizer = new op.FieldResizer({field:field});
		this.resizer.resize(true);
		field.focus();
		field.select();
	},
	syncSize : function() {
		this.resizer.resize();
	},
	$resolveImageUrl : function(obj,width,height) {
		return '../../../services/images/?id='+obj.value+'&width='+width+'&height='+height;
	},
	chooseImage : function() {
		imageChooser.show();
	},
	$select$imageGallery : function() {
		var id = imageGallery.getFirstSelection().value;
		document.forms.PartForm.imageId.value = id;
		this.syncToolbar();
	},
	syncToolbar : function() {
		parent.frames[0].textPartToolbar.$ready();
	},
	showColorWindow : function(callback) {
		this.colorCallback = callback;
		if (!this.colorWindow) {
			this.colorWindow = hui.ui.Window.create({padding:3,title:{en:'Color',da:'Farve'}});
			this.colorWindow.add(
				hui.ui.ColorPicker.create({listener:this})
			);
		}
		this.colorWindow.show();
	},
	$colorWasSelected : function(color) {
		this.colorCallback(color)
	},
	$colorWasHovered : function(color) {
		//hui.get('part_header_textarea').style.color = color;
	}
}

hui.ui.listen(partController);