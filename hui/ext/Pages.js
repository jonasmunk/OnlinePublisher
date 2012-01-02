/**
 * Pages
 * @constructor
 */
hui.ui.Pages = function(options) {
	this.options = options || {};
	this.element = hui.get(options.element);
	this.name = options.name;
	this.pages = hui.get.children(this.element);
	this.index = 0;
	hui.ui.extend(this);
	//hui.listen(this.element,'click',this.next.bind(this));
}

hui.ui.Pages.prototype = {
	next : function() {
		var current = this.pages[this.index];
		this.index = this.pages.length <= this.index+1 ? 0 : this.index+1;
		this._transition({hide:current,show:this.pages[this.index]});
	},
	previous : function() {
		var current = this.pages[this.index];
		this.index = this.index == 0 ? this.pages.length-1 : this.index-1;
		this._transition({hide:current,show:this.pages[this.index]});
	},
	_transition : function(options) {
		var hide = options.hide,
			show = options.show;
		hui.style.set(hide,{position:'absolute',width:this.element.clientWidth+'px'});
		hui.style.set(show,{position:'absolute',width:this.element.clientWidth+'px',display:'block',opacity:0});
		hui.effect.fadeOut({element:hide,onComplete:function() {
			hui.style.set(hide,{width : '',position:'',display:'none'});
		}});
		hui.effect.fadeIn({element:show,onComplete:function() {
			hui.style.set(show,{width : '',position:''});
			hui.ui.callVisible(this);
		}.bind(this)});
	}
}