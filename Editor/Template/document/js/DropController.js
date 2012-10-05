hui.ui.listen({
	$ready : function() {
		this._initDrop();
	},
	_initDrop : function() {
		if (this.activeSection) {return};
		hui.drag.listen({
			element : hui.get.firstByClass(document.body,'editor_body'),
			hoverClass : 'editor_dropping',
			$hover : this._hover.bind(this),
			$leave : this._leave.bind(this),
			onFiles : this._dropFiles.bind(this),
		});
		this._dropPoints = [];
		var adders = hui.get.byClass(document.body,'editor_section_adder');
		for (var i=0; i < adders.length; i++) {
			var adder = adders[i];
			var pos = hui.position.get(adder);
			this._dropPoints.push({left:pos.left+adder.clientWidth/2,top:pos.top+adder.clientHeight/2,element:adder});
		};
	},
	_dropFiles : function(files) {
		if (!this.latestAdder) {
			return;
		}
		var info = hui.string.fromJSON(this.latestAdder.getAttribute('data'));
		importUpload.setParameter('columnId',info.columnId);
		importUpload.setParameter('sectionIndex',info.sectionIndex);
		importWindow.show();
		importUpload.uploadFiles(files);
	},
	$uploadDidCompleteQueue$importUpload : function() {
		document.location='Editor.php';
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