////////////////////////// Finder ///////////////////////////

/**
 * A "finder" for finding objects
 * @constructor
 */
hui.ui.Finder = function(options) {
	this._init(options);
	hui.ui.extend(this);
}

hui.ui.Finder.create = function(options) {
	return new hui.ui.Finder();
}

hui.ui.Finder.prototype = new hui.ui.Widget();