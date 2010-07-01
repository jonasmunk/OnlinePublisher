/**
 * A list of articles
 * @constructor
 * @param {Object} options { element: «Node | id», name: «String», source: «In2iGui.Source» }
 */
In2iGui.Articles = function(options) {
	this.options = options;
	this.name = options.name;
	this.element = $(options.element);
	if (options.source) {
		options.source.listen(this);
	}
}

In2iGui.Articles.prototype = {
	/** @private */
	$articlesLoaded : function(doc) {
		this.element.update();
		var a = doc.getElementsByTagName('article');
		for (var i=0; i < a.length; i++) {
			var e = new Element('div',{'class':'in2igui_article'});
			var c = a[i].childNodes;
			for (var j=0; j < c.length; j++) {
				if (n2i.dom.isElement(c[j],'title')) {
					var title = n2i.dom.getNodeText(c[j]).escapeHTML();
					e.insert(new Element('h2').update(title));
				} else if (n2i.dom.isElement(c[j],'paragraph')) {
					var text = n2i.dom.getNodeText(c[j]).escapeHTML();
					var p = new Element('p').update(text);
					if (c[j].getAttribute('dimmed')==='true') {
						p.addClassName('in2igui_dimmed');
					}
					e.insert(p);
				}
			};
			this.element.insert(e);
		};
	},
	/** @private */
	$sourceIsBusy : function() {
		this.element.update('<div class="in2igui_articles_loading">Loading...</div>');
	},
	/** @private */
	$sourceIsNotBusy : function() {
		this.element.removeClassName('in2igui_list_busy');
	}
}

/* EOF */