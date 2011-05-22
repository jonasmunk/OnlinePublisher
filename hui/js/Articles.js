/**
 * A list of articles
 * @constructor
 * @param {Object} options { element: «Node | id», name: «String», source: «hui.ui.Source» }
 */
hui.ui.Articles = function(options) {
	this.options = options;
	this.name = options.name;
	this.element = hui.get(options.element);
	if (options.source) {
		options.source.listen(this);
	}
}

hui.ui.Articles.prototype = {
	/** @private */
	$articlesLoaded : function(doc) {
		this.element.innerHTML='';
		var a = doc.getElementsByTagName('article');
		for (var i=0; i < a.length; i++) {
			var e = hui.build('div',{'class':'in2igui_article'});
			var c = a[i].childNodes;
			for (var j=0; j < c.length; j++) {
				if (hui.dom.isElement(c[j],'title')) {
					var title = hui.dom.getText(c[j]);
					e.appendChild(hui.build('h2',{text:title}));
				} else if (hui.dom.isElement(c[j],'paragraph')) {
					var text = hui.dom.getText(c[j]);
					var p = hui.build('p',{text:text});
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
		hui.removeClass(this.element,'in2igui_list_busy');
	}
}

/* EOF */