var partController = {
	$ready : function() {
		var field = this.field = hui.get('part_header_textarea');
		this.resizer = new op.FieldResizer({field:field});
		this.resizer.resize(true);
		field.focus();
		field.select();
	},
	syncSize : function() {
		this.resizer.resize();
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