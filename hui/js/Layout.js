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

hui.ui.Layout.prototype = {
	$$resize : function() {
		if (hui.browser.gecko) {
			var center = hui.get.firstByClass(this.element,'hui_layout_center');
			if (center) {
				center.style.height='100%';
			}
		}
		if (!hui.browser.msie7 && !hui.browser.msie8 && !hui.browser.msie9) {
			return;
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
				bottom = hui.get.firstByTag(foot,'*').clientHeight;
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