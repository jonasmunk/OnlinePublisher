var partController = {
	$ready : function() {
		var field = hui.get('PartListingTextarea');
		field.focus();
		field.select();
		this.resizer = new op.FieldResizer({field:field});
		this.resizer.resize(true);
	},
	syncSize : function() {
		this.resizer.resize();
	},
	showFontWindow : function(callback) {
		this.fontCallback = callback;
		if (!this.fontWindow) {
			this.fontWindow = hui.ui.Window.create({padding:3,title:{en:'Font',da:'Skrift'}});
			this.fontWindow.add(
				hui.ui.FontPicker.create({listener:{$select:function(font) {
					callback(font.value);
				}}})
			);
		}
		this.fontWindow.show();
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