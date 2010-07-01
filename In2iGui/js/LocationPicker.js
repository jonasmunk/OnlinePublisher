/**
	Used to get a geografical location
	@constructor
*/
In2iGui.LocationPicker = function(options) {
	options = options || {};
	this.name = options.name;
	this.options = options.options || {};
	this.element = $(options.element);
	this.defaultCenter = new google.maps.LatLng(57.0465, 9.9185);
	In2iGui.extend(this);
	this.addBehavior();
}

In2iGui.LocationPicker.prototype = {
	/** @private */
	addBehavior : function() {
	},
	show : function(options) {
		if (!this.panel) {
			var panel = this.panel = In2iGui.BoundPanel.create({width:300});
			var mapContainer = new Element('div').setStyle({width:'300px',height:'300px'});
			panel.add(mapContainer);
			var buttons = In2iGui.Buttons.create({align:'right',top:5});
			var button = In2iGui.Button.create({text:'Luk'});
			button.listen({$click:function() {panel.hide()}});
			panel.add(buttons.add(button));
			panel.element.setStyle({left:'-10000px',top:'-10000px',display:''});
			var latLng = this.buildLatLng();
		    var mapOptions = {
		      zoom: 15,
		      mapTypeId: google.maps.MapTypeId.TERRAIN
		    }
		    this.map = new google.maps.Map(mapContainer, mapOptions);
			google.maps.event.addListener(this.map, 'click', function(obj) {
				var loc = {latitude:obj.latLng.lat(),longitude:obj.latLng.lng()};
    			this.setLocation(loc);
				this.fire('locationChanged',loc);
  			}.bind(this));
			panel.element.setStyle({display:'none'});
		}
		this.setLocation(options.location);
		if (options.node) {
			this.panel.position(options.node);
		}
		this.panel.show();
	},
	setLocation : function(loc) {
		if (!loc && this.marker) {
			this.marker.setMap(null);
			this.map.setCenter(this.defaultCenter);
			return;
		}
		loc = this.buildLatLng(loc);
		if (!this.marker) {
		    this.marker = new google.maps.Marker({
		        position: loc, 
		        map: this.map
		    });
		} else {
    		this.marker.setPosition(loc);
			this.marker.setMap(this.map);
		}
		this.map.setCenter(loc);
	},
	/** @private */
	buildLatLng : function(loc) {
		if (!loc) {
			loc = {latitude:57.0465, longitude:9.9185};
		}
		return new google.maps.LatLng(loc.latitude, loc.longitude);
	}
}

/* EOF */