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
	if (this.options.reveal) {
		hui.onReady(this._reveal.bind(this));
	}
}

hui.ui.Tiles.prototype = {
	_addBehavior : function() {
	},
	_reveal : function() {
		for (var i=0; i < this.tiles.length; i++) {
			var tile = this.tiles[i];
			this._bounce(tile);
		};
	},
	_bounce : function(tile) {
		hui.effect.fadeIn({element:tile,delay:Math.random()*500});		
	}
}

hui.ui.Tile = function(options) {
	this.options = options || {};
	this.element = hui.get(options.element);
	this.name = options.name;
	hui.ui.extend(this);
	this.fullScreen = false;
	this.initial = {
		width : this.element.style.width,
		height : this.element.style.height,
		top : this.element.style.top,
		left : this.element.style.left
	}
	this._addBehavior();
}

hui.ui.Tile._zIndex = 0;

hui.ui.Tile.prototype = {
	_addBehavior : function() {
		hui.listen(this.element,'click',function(e) {
			e = hui.event(e);
			if (hui.cls.has(e.element,'hui_tile_icon')) {
				var key = e.element.getAttribute('data-hui-key');
				this.fire('clickIcon',{key:key,tile:this});
			}
		}.bind(this))
	},
	isFullScreen : function() {
		return this.fullScreen;
	},
	toggleFullScreen : function() {
		if (this.fullScreen) {
			hui.animate({
				node : this.element,
				css : this.initial,
				duration : 1000,
				ease : hui.ease.elastic,
				onComplete : function() {
					hui.ui.reLayout()
				}
			});			
		} else {
			hui.ui.Tile._zIndex++;
			this.element.style.zIndex = hui.ui.Tile._zIndex;
			hui.animate({
				node : this.element,
				css : {top:'0%',left:'0%',width:'100%',height:'100%'},
				duration : 1000,
				ease : hui.ease.elastic,
				onComplete : function() {
					hui.ui.reLayout()
				}
			});			
		}
		this.fullScreen = !this.fullScreen;
	}
}