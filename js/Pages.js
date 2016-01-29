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
	this.fixedHeight = hui.cls.has(this.element,'hui_pages_full');
	this.expanded = false;
	hui.ui.extend(this);
}

hui.ui.Pages.create = function(options) {
	options = options || {};
	options.element = hui.build('div',{'class':'hui_pages'});
	return new hui.ui.Pages(options);
}

hui.ui.Pages.prototype = {
	add : function(widgetOrElement) {
		var element = hui.dom.isElement(widgetOrElement) ? element : widgetOrElement.element;
		var page = hui.build('div',{'class':'hui_pages_page'});
		page.appendChild(element);
		this.element.appendChild(page);
		if (this.pages.length>0) {
			page.style.display = 'none';
		}
		this.pages = hui.get.children(this.element);
	},
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
	goTo : function(key) {
		for (var i=0; i < this.pages.length; i++) {
			if (this.pages[i].getAttribute('data-key')==key && i!=this.index) {
				var current = this.pages[this.index];
				this.index = i;
				this._transition({hide:current,show:this.pages[i]});
				return;
			}
		};
	},
	getPageKey : function() {
		return this.pages[this.index].getAttribute('data-key');
	},
	expand : function() {
		var l = this.pages.length;
		for (var i=0; i < l; i++) {
			if (!this.expanded) {
				hui.style.set(this.pages[i],{
					width : (100 / l)+'%',
					display : 'block',
					'float' : 'left',
					opacity: 1
				});
			} else {
				hui.style.set(this.pages[i],{
					width : '',
					display : i==this.index ? 'block' : 'none',
					'float' : ''
				});
			}
		};
		hui.ui.callVisible(this);
		this.expanded = !this.expanded;
	},
	_transition : function(options) {
		var hide = options.hide,
			show = options.show,
			e = this.element,
            duration = 300;
		if (this.fixedHeight) {
			hui.style.set(hide,{
                position:'absolute',
                width:e.clientWidth+'px',
                height:this.element.clientHeight+'px'
            });
			hui.style.set(show,{
                position:'absolute',
                display:'block',opacity:0,
                width:e.clientWidth+'px',
                height:this.element.clientHeight+'px'
            });
		} else {
			hui.style.set(hide,{
                position:'absolute',width:e.clientWidth+'px'
            });
			hui.style.set(show,{
                position:'absolute',width:e.clientWidth+'px',display:'block',opacity:0
            });
			hui.style.set(e,{height:hide.offsetHeight+'px',overflow:'hidden',position:'relative'});
			hui.animate({
                node : e,
                css : {height:show.offsetHeight+'px'},
                duration : duration,
                ease : hui.ease.slowFastSlow
            })
		}
		hui.ui.reLayout();
		
		hui.effect.fadeOut({
            element : hide,
            duration : duration,
            $complete : function() {
                hui.style.set(hide,{width : '',position:'',height:''});
                window.setTimeout(function() {
                    hide.style.display = 'none';
                })
            }
        });
		
		hui.effect.fadeIn({
            element : show,
            duration : duration,
            $complete : function() {
  			    hui.style.set(show,{width : '',position:'',height:''});
                if (!this.fixedHeight) {
                    hui.style.set(e,{height:'',overflow:'',position:''});          
                }
                hui.ui.reLayout();
                hui.ui.callVisible(this);
            		this.fireSizeChange();
            }.bind(this)
        });
	}
}