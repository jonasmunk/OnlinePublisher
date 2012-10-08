hui.ui.listen({
	$ready : function() {
		this._initDrop();
		//this._dropURL('http://www.dr.dk/php/p3/komma-nul-wordpress/mama/media/images/21802/620/348/Mariekey.jpg');
	},
	_initDrop : function() {
		if (this.activeSection) {return};
		hui.drag.listen({
			element : hui.get.firstByClass(document.body,'editor_body'),
			hoverClass : 'editor_dropping',
			$hover : this._hover.bind(this),
			$leave : this._leave.bind(this),
			$dropFiles : this._dropFiles.bind(this),
			$dropText : this._dropText.bind(this),
			$dropURL : this._dropURL.bind(this)
		});
		this._dropPoints = [];
		var adders = hui.get.byClass(document.body,'editor_section_adder');
		for (var i=0; i < adders.length; i++) {
			var adder = adders[i];
			var pos = hui.position.get(adder);
			this._dropPoints.push({left:pos.left+adder.clientWidth/2,top:pos.top+adder.clientHeight/2,element:adder});
		};
	},
	_dropURL : function(url) {
		if (!this.latestAdder) {
			return;
		}
		var info = hui.string.fromJSON(this.latestAdder.getAttribute('data'));
		var overlay = hui.ui.Overlay.create({modal:true});
		overlay.addText(hui.ui.getTranslated({en:'What should be done?',da:'Hvad skal gøres?'}));
		overlay.add(hui.ui.Button.create({text:'Insæt link',listener:{
			$click : function() {
				overlay.hide();
				linkController.newLink({url:url});
			}
		}}))
		overlay.add(hui.ui.Button.create({text:'Insæt billede',listener:{
			$click : function() {
				overlay.hide();
				this._uploadUrl(url,info);
			}.bind(this)
		}}))
		overlay.show({element:this.latestAdder});
		this._reset();
	},
	_uploadUrl : function(url,info) {
		info.url = url;
		hui.ui.request({
			url : 'actions/ImportUpload.php',
			parameters : info,
			$success : function() {
				document.location='Editor.php';
			}
		})		
	},
	_dropText : function(text) {
		if (!this.latestAdder) {
			return;
		}
		
		var info = hui.string.fromJSON(this.latestAdder.getAttribute('data'));
		info.text = text;
		
		var overlay = hui.ui.Overlay.create({modal:true});
		overlay.addText(hui.ui.getTranslated({en:'What should be done?',da:'Hvad skal gøres?'}));
		overlay.add(hui.ui.Button.create({text:'Insæt som tekst',listener:{
			$click : function() {
				overlay.hide();
				info.type = 'text';
				this._importText(info);
			}.bind(this)
		}}))
		overlay.add(hui.ui.Button.create({text:'Insæt som overskrift',listener:{
			$click : function() {
				overlay.hide();
				info.type = 'header';
				this._importText(info);
			}.bind(this)
		}}))
		overlay.show({element:this.latestAdder});
		this._reset();
	},
	_importText : function(parameters) {
		hui.ui.request({
			url : 'actions/ImportUpload.php',
			parameters : parameters,
			$success : function() {
				document.location='Editor.php';
			}
		})
	},
	_dropFiles : function(files) {
		if (!this.latestAdder) {
			return;
		}
		
		var info = hui.string.fromJSON(this.latestAdder.getAttribute('data'));

		var overlay = hui.ui.Overlay.create({modal:true});
		overlay.addText(hui.ui.getTranslated({en:'What should be done?',da:'Hvad skal gøres?'}));
		
		overlay.add(hui.ui.Button.create({text:{en:'Insert as image',da:'Indsæt som billede'},listener:{
			$click : function() {
				overlay.hide();
				importUpload.setParameter('columnId',info.columnId);
				importUpload.setParameter('sectionIndex',info.sectionIndex);
				importUpload.setParameter('type','image');
				importWindow.show();
				importUpload.uploadFiles(files);
			}.bind(this)
		}}))
		
		overlay.add(hui.ui.Button.create({text:{en:'Insert as file',da:'Indsæt som fil'},listener:{
			$click : function() {
				overlay.hide();
				importUpload.setParameter('columnId',info.columnId);
				importUpload.setParameter('sectionIndex',info.sectionIndex);
				importUpload.setParameter('type','file');
				importWindow.show();
				importUpload.uploadFiles(files);
			}.bind(this)
		}}))
		
		overlay.show({element:this.latestAdder});
		this._reset();
	},
	$uploadDidCompleteQueue$importUpload : function() {
		document.location='Editor.php';
	},
	_reset : function() {
		if (this.latestAdder) {
			hui.cls.remove(this.latestAdder,'editor_section_adder_drop');
		}
		this.latestAdder = null;
	},
	_hover : function(e) {
		e = hui.event(e);
		var top = e.getTop(),
			left = e.getLeft();
		
		var closestDist = Number.MAX_VALUE;
		var adder = null;
		for (var i=0; i < this._dropPoints.length; i++) {
			var point = this._dropPoints[i];
			var dist = this._getDistance({left:left,top:top},point);
			if (dist<closestDist) {
				closestDist = dist;
				adder = point.element;
			}
		};
		if (adder && adder!=this.latestAdder) {
			hui.cls.remove(this.latestAdder,'editor_section_adder_drop')
			hui.cls.add(adder,'editor_section_adder_drop')
		}
		this.latestAdder = adder;
	},
	_leave : function(e) {
		hui.log('_leaveDrop')
		if (this.latestAdder) {
			hui.cls.remove(this.latestAdder,'editor_section_adder_drop')
		}
		this.latestAdder = null;
	},
	_getDistance : function( point1, point2 ) {
		var x = point2.left - point1.left,
	  		y = point2.top - point1.top;
		return Math.sqrt( x * x + y * y );
	}
})