/**
 * @constructor
 */
op.Editor.Html = function(options) {
	this.element = hui.get(options.element);
	this.id = hui.ui.Editor.getPartId(this.element);
	this.partElement = hui.get.firstChild(this.element);
	this.part = null;
	this.original = null;
}

op.Editor.Html.prototype = {
	activate : function(callback) {
		this._load(callback);
	},
	save : function(options) {
		hui.ui.request({
			url : 'parts/update.php',
			parameters : {id:this.id,pageId:op.page.id,html:this.part.html,type:'html'},
			$text : function(html) {
				this.element.innerHTML = html;
				this.partElement = hui.dom.firstChild(this.element);
				options.callback();
			}.bind(this)
		});
	},
	cancel : function() {
		this.partElement.innerHTML = this.original;
		this.part = null;
	},
	deactivate : function(callback) {
		this.win.hide();
		callback();
	},
	getValue : function() {
		return this.value;
	},
	
	
	_load : function(callback) {
		hui.ui.request({url:'parts/load.php',parameters:{type:'html',id:this.id},onJSON:function(part) {
			this.part = part;
			this.original = part.html;
			this._edit();
			callback();
		}.bind(this)});
	},
	_buildUI : function() {
		if (!this.win) {
			this.win = hui.ui.Window.create({width:500,title:'HTML',close:false});
			this.code = hui.ui.CodeInput.create();
			this.code.listen({
				$valueChanged : this._valueChanged.bind(this)
			})
			this.win.add(this.code);
			this.msg = hui.build('div',{style:'font-size: 11px; color: red; text-align: left; padding: 2px 0 0 3px'});
			this.win.add(this.msg);
		}
	},
	_valueChanged : function(value) {
		var valid = hui.xml.parse(value)!=null;
		this.partElement.innerHTML = value;
		this.part.html = value;
		hui.dom.setText(this.msg,valid ? '' : 'Ikke valid');
	},
	_edit : function() {
		this._buildUI();
		this.code.setValue(this.part.html);
		this.win.show();
	}
}