/**
 * @constructor
 * @param {Object} options { element «Node | id», name: «String» }
 */
hui.ui.Layout = function(options) {
	this.name = options.name;
	this.options = options || {};
	this.element = hui.get(options.element);
	hui.ui.extend(this);
}

hui.ui.Layout.create = function(options) {
	options = hui.override({text:'',highlighted:false,enabled:true},options);
	
	options.element = hui.dom.parse('<table class="hui_layout"><tbody class="hui_layout"><tr class="hui_layout_middle"><td class="hui_layout_middle">'+
			'<table class="hui_layout_middle"><tr>'+
			'<td class="hui_layout_left hui_context_sidebar"><div class="hui_layout_left"></div></td>'+
			'<td class="hui_layout_center"></td>'+
			'</tr></table>'+
			'</td></tr></tbody></table>');
	return new hui.ui.Layout(options);
}

hui.ui.Layout.prototype = {
	
	addToLeft : function(widget) {
		var tbody = hui.get.firstByClass(this.element,'hui_layout_left');
		tbody.appendChild(widget.element);
	},
	
	addToCenter : function(widget) {
		var tbody = hui.get.firstByClass(this.element,'hui_layout_center');
		tbody.appendChild(widget.element);
	},
	
	/** @private */
	$$layout : function() {
		if (hui.browser.gecko) {
			var center = hui.get.firstByClass(this.element,'hui_layout_center');
			if (center) {
				center.style.height='100%';
			}
		}
		if (!window.navigator.userAgent.indexOf('AppleWebKit/536')) {
			if (!hui.browser.msie7 && !hui.browser.msie8 && !hui.browser.msie9) {
				return;
			}			
		}
		if (!hui.dom.isVisible(this.element)) {
			return;
		}
		if (this.diff===undefined) {
			var head = hui.get.firstByClass(this.element,'hui_layout_top');
			var top = hui.get.firstByTag(head,'*').clientHeight;
			var foot = hui.get.firstByTag(hui.get.firstByTag(this.element,'tfoot'),'td');
			var bottom = 0;
			if (foot) {
				var inner = hui.get.firstByTag(foot,'*');
				if (inner) {
					bottom = inner.clientHeight;
				}
			}
			top += hui.position.getTop(this.element);
			this.diff = bottom+top;
			if (this.element.parentNode!==document.body) {
				this.diff+=15;
			} else {
			}
		}
		var tbody = hui.get.firstByTag(this.element,'tbody');
		var cell = hui.get.firstByTag(tbody,'td');
		var height = (hui.window.getViewHeight()-this.diff+5);
		cell.style.height = height+'px';
	}
};

/* EOF */