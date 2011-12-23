/**
 * Tiles
 * @constructor
 */
hui.ui.Tiles = function(options) {
	this.options = options || {};
	this.element = hui.get(options.element);
	this.name = options.name;
	this.tiles = hui.get.children(this.element);
	hui.ui.extend(this);
	this._addBehavior();
}

hui.ui.Tiles.prototype = {
	_addBehavior : function() {
		hui.onReady(this._reveal.bind(this));
	},
	_reveal : function() {
		for (var i=0; i < this.tiles.length; i++) {
			var tile = this.tiles[i];
			this._bounce(tile);
		};
	},
	_bounce : function(tile) {
		
		hui.effect.fadeIn({element:tile,delay:Math.random()*500});
		
		hui.listen(tile,'click',function() {
			//hui.cls.toggle(tile,'hui_effect_spin');
		});
		
	}
}