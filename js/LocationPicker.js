/**
	Used to get a geografical location
	@constructor
*/
hui.ui.LocationPicker = function(options) {
	options = options || {};
	this.name = options.name;
	this.options = options.options || {};
	this.element = hui.get(options.element);
	this.backendLoaded = window.google!==undefined && window.google.maps!==undefined;
	this.defered = [];
	hui.ui.extend(this);
}

hui.ui.LocationPicker.prototype = {
	show : function(options) {
		if (!this.panel) {
			var panel = this.panel = hui.ui.BoundPanel.create({width:302,modal:true});
			var mapContainer = hui.build('div',{style:'width:300px;height:300px;border:1px solid #bbb;'});
			panel.add(mapContainer);
			var buttons = hui.ui.Buttons.create({align:'right',top:5});
			var button = hui.ui.Button.create({text:{en:'Close',da:'Luk'},small:true});
			button.listen({$click:function() {panel.hide()}});
			panel.add(buttons.add(button));
			hui.style.set(panel.element,{left:'-10000px',top:'-10000px',display:''});
			this._whenReady(function() {
		   	 	var mapOptions = {
			      zoom: 15,
			      mapTypeId: google.maps.MapTypeId.TERRAIN
			    }
				this.defaultCenter = new google.maps.LatLng(57.0465, 9.9185);
			    this.map = new google.maps.Map(mapContainer, mapOptions);
				google.maps.event.addListener(this.map, 'click', function(obj) {
					var loc = {latitude:obj.latLng.lat(),longitude:obj.latLng.lng()};
	    			this.setLocation(loc);
					this.fire('locationChanged',loc);
	  			}.bind(this));
				this.setLocation(options.location);
			}.bind(this))
		}
		if (options.node) {
			this.panel.position(options.node);
		}
		this.panel.show();
	},
	_whenReady : function(func) {
		if (this.backendLoaded) {
			func();
			return;
		}
		this.defered.push(func);
		if (this.loadingBackend) {return};
		this.loadingBackend = true;
		window.huiLocationPickerReady = function() {
			this.loadingBackend = false;
			this.backendLoaded = true;
			hui.log('Google maps loaded!')
			for (var i=0; i < this.defered.length; i++) {
				this.defered[i]();
			};
			window.huiLocationPickerReady = null;
		}.bind(this);
		hui.log('Loading google maps...')
		hui.require('http://maps.google.com/maps/api/js?sensor=false&callback=huiLocationPickerReady');
	},
	setLocation : function(loc) {
		this._whenReady(function() {
			hui.log('Setting location...')
			if (!loc && this.marker) {
				this.marker.setMap(null);
				this.map.setCenter(this.defaultCenter);
				return;
			}
			loc = this._buildLatLng(loc);
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
		}.bind(this))
	},
	_buildLatLng : function(loc) {
		if (!loc) {
			loc = {latitude:57.0465, longitude:9.9185};
		}
		return new google.maps.LatLng(loc.latitude, loc.longitude);
	}
}

/* EOF */