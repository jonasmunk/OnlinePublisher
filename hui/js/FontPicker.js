/**
	@constructor
	@param options The options (non)
*/
hui.ui.FontPicker = function(options) {
	this.name = options.name;
	this.element = hui.get(options.element);
	this.fonts = options.fonts.concat(options.additionalFonts);
	hui.log(this.fonts)
	this.options = options;
	hui.override(this.options,options);
	hui.ui.extend(this);
	this._addBehavior();
}

hui.ui.FontPicker.create = function(options) {
	options = hui.override({
		fonts : [
			{name:'Verdana',css:'Verdana,sans-serif'},
			{name:'Tahoma',css:'Tahoma,Geneva,sans-serif'},
			{name:'Trebuchet',css:'Trebuchet MS,Helvetica,sans-serif'},
			{name:'Geneva',css:'Geneva,Tahoma,sans-serif'},
			{name:'Helvetica',css:'Helvetica,Arial,sans-serif'},
			{name:'Helvetica Neue',css:'Helvetica Neue,Helvetica,Arial,sans-serif'},
			{name:'Arial',css:'Arial,Helvetica,sans-serif'},
			{name:'Arial Black',css:'Arial Black,Gadget,Arial,sans-serif'},
			{name:'Impact',css:'Impact,Charcoal,Arial Black,Gadget,Arial,sans-serif'},

			{name:'Times New Roman',css:'Times New Roman,Times,serif'},
			{name:'Times',css:'Times,Times New Roman,serif'},
			{name:'Book Antiqua',css:'Book Antiqua,Palatino,serif'},
			{name:'Palatino',css:'Palatino,Book Antiqua,serif'},
			{name:'Georgia',css:'Georgia,Book Antiqua,Palatino,serif'},
			{name:'Garamond',css:'Garamond,Times New Roman,Times,serif'},

			{name:'Comic Sans',css:'Comic Sans MS,cursive'},

			{name:'Courier New',css:'Courier New,Courier,monospace'},
			{name:'Courier',css:'Courier,Courier New,monospace'},
			{name:'Lucida Console',css:'Lucida Console,Monaco,monospace'},
			{name:'Monaco',css:'Monaco,Lucida Console,monospace'}
		],
		additionalFonts : []
	},options);
	
	var fonts = options.fonts.concat(options.additionalFonts);
	
	var element = options.element = hui.build('div',{
		'class' : 'hui_fontpicker'
		});
	for (var i=0; i < fonts.length; i++) {
		var font = fonts[i];
		var node = hui.build('div',{parent:element,'class':'hui_fontpicker_item',text:font.name,style:'font-family:'+font.css+';'});
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
	_buildPreview : function(font) {
		var e = hui.build('div',{className:'hui_fontpicker_example',style:'font-family:'+font.css+';'});

		var weights = [100,200,300,'normal',500,600,'bold','bolder'];
		var sizes = ['9pt','10pt','11pt','12pt','13pt','14pt','16px','18pt'];

		var html = '<h1>'+font.name+'</h1>';
		
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
		var win = hui.ui.Window.create({title:font.name,padding:3});
		win.add(e);
		win.show();
	}
}

/* EOF */




