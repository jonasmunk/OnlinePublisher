/**
 * @constructor
 */
op.Editor.Html = function(options) {
	this.element = hui.get(options.element);
	this.id = hui.ui.Editor.getPartId(this.element);
	this.partElement = hui.get.firstChild(this.element);
	this.part = null;
}

op.Editor.Html.prototype = {
	activate : function(callback) {
		this._load(callback);
	},
	save : function(options) {
		hui.ui.Editor.get().partChanged(this);
		hui.ui.request({
			url : 'parts/update.php',
			parameters : {id:this.id,pageId:op.page.id,html:this.part.html,type:'html'},
			onText : function(html) {
				this.deactivate();
				this.element.innerHTML = html;
				this.partElement = hui.dom.firstChild(this.element);
				options.callback();
			}.bind(this)
		});
	},
	cancel : function() {
		this.deactivate();
	},
	deactivate : function() {
		this.win.hide();
		hui.ui.Editor.get().partDidDeacivate(this);
	},
	getValue : function() {
		return this.value;
	},
	
	
	_load : function(callback) {
		hui.ui.request({url:'parts/load.php',parameters:{type:'html',id:this.id},onJSON:function(part) {
			this.part = part;
			this._edit();
			callback();
		}.bind(this)});
	},
	_buildUI : function() {
		if (!this.win) {
			this.win = hui.ui.Window.create({width:500});
			this.code = hui.ui.CodeInput.create();
			this.code.listen({
				$valueChanged : function(value) {
					hui.log(value)
					this.partElement.innerHTML = value;
					this.part.html = value;
				}.bind(this)
			})
			this.win.add(this.code);
		}
	},
	_edit : function() {
		this._buildUI();
		this.code.setValue(this.part.html);
		this.win.show();
	},
	_updateFieldStyle : function() {
		hui.style.set(this.field,{width:this.header.clientWidth+'px',height:this.header.clientHeight+'px'});
		hui.style.copy(this.header,this.field,['font-size','line-height','margin-top','font-weight','font-family','text-align','color','font-style']);
	}
}