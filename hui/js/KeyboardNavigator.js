hui.ui.KeyboardNavigator = function(options) {
	options = options || {};
	this.text = '';
	this.items = [];
	this.index = null;
	this.name = options.name;
	hui.listen(window,'keydown',this._onKeyDown.bind(this));
	this.element = hui.build('div',{'class':'hui_keyboardnavigator',parent:document.body})
	this.input = hui.build('p',{'class':'hui_keyboardnavigator_input',parent:this.element});
	this.list = hui.build('ul',{parent:this.element});
	this.listNodes = [];
	hui.ui.extend(this);
}

hui.ui.KeyboardNavigator.prototype = {
	_onKeyDown : function(e) {
		if (hui.ui.keyboardTarget) {
			return;
		}
		e = hui.event(e.key);
		if (e.metaKey || e.altKey || e.shiftKey || e.leftKey || e.rightKey) {
			return;
		} else if (e.downKey) {
			this._selectNext();
			return;
		} else if (e.upKey) {
			this._selectPrevious();
			return;
		} else if (e.returnKey || e.enterKey) {
			this._select();
			this.text = '';
			this._render();
			return;
		}
		if (e.escapeKey) {
			this.text = '';
		} else if (e.backspaceKey) {
			e.stop();
			if (this.text.length>0) {
				this.text = this.text.substring(0,this.text.length-1);				
			} else {
				return;
			}
		} else {
			this.text+=String.fromCharCode(e.keyCode).toLowerCase();
		}
		this._render();
		if (this.text.length>0) {
			this._complete();			
		}
	},
	_select : function() {
		if (this.index!==null) {
			this.fire('select',this.items[this.index]);
		}
	},
	_selectPrevious : function() {
		if (this.items.length==0) {
			return;
		}
		if (this.index===null) {
			this.index = 0;
		} else {
			hui.cls.remove(this.listNodes[this.index],'hui_keyboardnavigator_selected');
		}
		this.index--;
		if (this.index < 0) {
			this.index = this.items.length-1;
		}
		hui.cls.add(this.listNodes[this.index],'hui_keyboardnavigator_selected');
	},
	_selectNext : function() {
		if (this.items.length==0) {
			return;
		}
		if (this.index===null) {
			this.index = -1;
		} else {
			hui.cls.remove(this.listNodes[this.index],'hui_keyboardnavigator_selected');
		}
		this.index++;
		if (this.index>this.items.length-1) {
			this.index = 0;
		}
		hui.cls.add(this.listNodes[this.index],'hui_keyboardnavigator_selected');
	},
	_render : function() {
		if (!this.text) {
			this.element.style.display='none';
			return;
		}
		hui.dom.setText(this.input,this.text);
		hui.dom.clear(this.list);
		this.listNodes = [];
		this.index = Math.min(this.index,this.items.length-1);
		for (var i=0; i < this.items.length; i++) {
			var item = this.items[i]
			var node = hui.build('li',{text:item.text,parent:this.list});
			this.listNodes.push(node);
		};
		if (this.index>-1) {
			hui.cls.add(this.listNodes[this.index],'hui_keyboardnavigator_selected');
		} else {
			this.index = null;
		}
		this.element.style.display = 'block';
		this.element.style.marginLeft = (this.element.clientWidth/-2)+'px';
		hui.animate({node:this.element,duration:300,ease:hui.ease.slowFastSlow,css:{marginTop:(this.element.clientHeight/-2)+'px'}})
	},
	_complete : function() {
		this.fire('complete',{
			text : this.text,
			callback : function(items) {
				this.items = items;
				this._render();
			}.bind(this)
		});
	}
}