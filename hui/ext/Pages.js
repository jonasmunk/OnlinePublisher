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
	this.expanded = false;
	hui.ui.extend(this);
	//hui.listen(this.element,'click',this.next.bind(this));
}

hui.ui.Pages.prototype = {
	next : function() {
		if (this.expanded) {return}
		var current = this.pages[this.index];
		this.index = this.pages.length <= this.index+1 ? 0 : this.index+1;
		this._transition({hide:current,show:this.pages[this.index]});
	},
	previous : function() {
		if (this.expanded) {return}
		var current = this.pages[this.index];
		this.index = this.index == 0 ? this.pages.length-1 : this.index-1;
		this._transition({hide:current,show:this.pages[this.index]});
	},
	expand : function() {
		var l = this.pages.length;
		for (var i=0; i < l; i++) {
			if (!this.expanded) {
				hui.style.set(this.pages[i],{
					width : (100 / l)+'%',
					display : 'block',
					float : 'left',
					opacity: 1
				});
			} else {
				hui.style.set(this.pages[i],{
					width : '',
					display : i==this.index ? 'block' : 'none',
					float : ''
				});
			}
		};
		hui.ui.callVisible(this);
		this.expanded = !this.expanded;
	},
	_transition : function(options) {
		var hide = options.hide,
			show = options.show;
		hui.style.set(hide,{position:'absolute',width:this.element.clientWidth+'px',height:this.element.clientHeight+'px'});
		hui.style.set(show,{position:'absolute',width:this.element.clientWidth+'px',display:'block',opacity:0,height:this.element.clientHeight+'px'});
			hui.ui.reLayout();
		hui.effect.fadeOut({element:hide,onComplete:function() {
			hui.style.set(hide,{width : '',position:'',height:'',display:'none'});
		}});
		hui.effect.fadeIn({element:show,onComplete:function() {
			hui.style.set(show,{width : '',position:'',height:''});
			hui.ui.reLayout();
			hui.ui.callVisible(this);
		}.bind(this)});
	}
}