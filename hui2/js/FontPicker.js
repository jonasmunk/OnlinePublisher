/**
	@constructor
	@param options The options (non)
*/
hui.ui.FontPicker = function(options) {
	this.name = options.name;
	this.element = hui.get(options.element);
	this.fonts = options.fonts.concat(options.additionalFonts);
	this.previews = {};
	this.options = options || {};
	hui.override(this.options,options);
	hui.ui.extend(this);
	if (this.options.listener) {
		this.listen(this.options.listener);
	}
	this._addBehavior();
}

hui.ui.FontPicker.fonts =[
	{text:'Verdana',value:'Verdana,sans-serif'},
	{text:'Tahoma',value:'Tahoma,Geneva,sans-serif'},
	{text:'Trebuchet',value:'Trebuchet MS,Helvetica,sans-serif'},
	{text:'Geneva',value:'Geneva,Tahoma,sans-serif'},
	{text:'Helvetica',value:'Helvetica,Arial,sans-serif'},
	{text:'Helvetica Neue',value:'Helvetica Neue,Helvetica,Arial,sans-serif'},
	{text:'Arial',value:'Arial,Helvetica,sans-serif'},
	{text:'Arial Black',value:'Arial Black,Gadget,Arial,sans-serif'},
	{text:'Impact',value:'Impact,Charcoal,Arial Black,Gadget,Arial,sans-serif'},

	{text:'Times New Roman',value:'Times New Roman,Times,serif'},
	{text:'Times',value:'Times,Times New Roman,serif'},
	{text:'Book Antiqua',value:'Book Antiqua,Palatino,serif'},
	{text:'Palatino',value:'Palatino,Book Antiqua,serif'},
	{text:'Georgia',value:'Georgia,Book Antiqua,Palatino,serif'},
	{text:'Garamond',value:'Garamond,Times New Roman,Times,serif'},

	{text:'Comic Sans',value:'Comic Sans MS,cursive'},

	{text:'Courier New',value:'Courier New,Courier,monospace'},
	{text:'Courier',value:'Courier,Courier New,monospace'},
	{text:'Lucida Console',value:'Lucida Console,Monaco,monospace'},
	{text:'Monaco',value:'Monaco,Lucida Console,monospace'}
]

hui.ui.FontPicker.create = function(options) {
	options = hui.override({
		fonts : hui.ui.FontPicker.fonts,
		additionalFonts : []
	},options);
	
	var fonts = options.fonts.concat(options.additionalFonts);
	
	var element = options.element = hui.build('div',{
		'class' : 'hui_fontpicker'
		});
	for (var i=0; i < fonts.length; i++) {
		var font = fonts[i];
		var node = hui.build('div',{parent:element,'class':'hui_fontpicker_item',text:font.text,style:'font-family:'+font.value+';'});
		var icon = hui.ui.createIcon('monochrome/info',16,'a');
		node.appendChild(icon);
		node.huiIndex = i;
	};
	return new hui.ui.FontPicker(options);
}

hui.ui.FontPicker.prototype = {
	_addBehavior : function() {
		hui.listen(this.element,'click',this._onClick.bind(this));
	},
	_onClick : function(e) {
		e = hui.event(e);
		var node = e.findByClass('hui_fontpicker_item');
		if (node) {
			var a = e.findByClass('hui_icon');
			var font = this.fonts[node.huiIndex];
			if (a) {
				this._buildPreview(font);
			} else {
				this.fire('select',font);
			}
		}
	},
	/** @private */
	$visibilityChanged : function() {
		if (!hui.dom.isVisible(this.element)) {
			hui.each(this.previews,function(key,value) {
				value.hide();
			})
		}
	},
	_buildPreview : function(font) {
		if (this.previews[font.text]) {
			this.previews[font.text].show();
			return;
		}
		var e = hui.build('div',{className:'hui_fontpicker_example',style:'font-family:'+font.value+';'});

		var weights = [100,200,300,'normal',500,600,'bold','bolder'];
		var sizes = ['9pt','10pt','11pt','12pt','13pt','14pt','16px','18pt'];

		var html = '<h1>'+font.text+'</h1>';
		
		html+='<p style="font-size: 12px;">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>';
		
		html+='<table>';
		html+='<thead><tr><th></th>';
		for (var i=0; i < weights.length; i++) {
			html+='<th>'+weights[i]+'</th>';
		};
		html+='</tr></thead>';

		html+='<tbody>';
		for (var i=0; i < sizes.length; i++) {
			html+='<tr><th>'+sizes[i]+'</th>';
			for (var j=0; j < weights.length; j++) {
				html+='<td style="font-weight: '+weights[j]+'; font-size:'+sizes[i]+';">Pack my box with five dozen liquor jugs</td>';
			};
		};
		html+='</tbody>';
		e.innerHTML = html;
		var win = hui.ui.Window.create({title:font.text,padding:3});
		win.add(e);
		this.previews[font.text] = win;
		win.show();
	},
    destroy : function() {
		hui.each(this.previews,function(key,value) {
			value.destroy();
		})
        hui.dom.remove(this.element);
    }
}

/* EOF */




