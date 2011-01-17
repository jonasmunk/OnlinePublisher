/**
 * A list of articles
 * @constructor
 * @param {Object} options { element: «Node | id», name: «String», source: «In2iGui.Source» }
 */
In2iGui.Articles = function(options) {
	this.options = options;
	this.name = options.name;
	this.element = n2i.get(options.element);
	if (options.source) {
		options.source.listen(this);
	}
}

In2iGui.Articles.prototype = {
	/** @private */
	$articlesLoaded : function(doc) {
		this.element.innerHTML='';
		var a = doc.getElementsByTagName('article');
		for (var i=0; i < a.length; i++) {
			var e = n2i.build('div',{'class':'in2igui_article'});
			var c = a[i].childNodes;
			for (var j=0; j < c.length; j++) {
				if (n2i.dom.isElement(c[j],'title')) {
					var title = n2i.dom.getText(c[j]);
					e.appendChild(n2i.build('h2',{text:title}));
				} else if (n2i.dom.isElement(c[j],'paragraph')) {
					var text = n2i.dom.getText(c[j]);
					var p = n2i.build('p',{text:text});
					if (c[j].getAttribute('dimmed')==='true') {
						p.className='in2igui_dimmed';
					}
					e.appendChild(p);
				}
			};
			this.element.appendChild(e);
		};
	},
	$sourceFailed : function() {
		this.element.innerHTML='<div>Failed!</div>';
	},
	/** @private */
	$sourceIsBusy : function() {
		this.element.innerHTML='<div class="in2igui_articles_loading">Loading...</div>';
	},
	/** @private */
	$sourceIsNotBusy : function() {
		n2i.removeClass(this.element,'in2igui_list_busy');
	}
}

/* EOF */