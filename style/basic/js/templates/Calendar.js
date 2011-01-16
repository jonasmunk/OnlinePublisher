op.CalendarTemplate = function() {
	this.days = [];
	this.arrows = [];
	this.maxDayEvents = [0,0,0,0,0,0,0];
	this.analyze();
	this.createArrows();
	this.windowWasScrolled();
	
	var self = this;
	n2i.listen(window,'scroll',
		function(e) {
			self.windowWasScrolled();
		}
	);
	n2i.listen(window,'resize',
		function(e) {
			self.windowWasScrolled();
		}
	);
}

op.CalendarTemplate.prototype.windowWasScrolled = function() {
	var top = n2i.getScrollTop();
	var height = n2i.getInnerHeight();
	var bottom = top+height;
	for (var i=0;i<this.days.length;i++) {
		if (this.maxDayEvents[i]>bottom) {
			var left = this.days[i].cumulativeOffset().left;
			this.arrows[i].style.top=(bottom-20)+'px';
			this.arrows[i].style.left=(left)+'px';
			this.arrows[i].style.display='block';
		} else {
			this.arrows[i].style.display='none';
		}
	}
}

op.CalendarTemplate.prototype.createArrows = function() {
	for (var i=0; i < this.days.length; i++) {
		var arrow = document.createElement('div');
		arrow.className = 'calendar_arrow';
		document.body.appendChild(arrow);
		this.arrows[i] = arrow;
	};
}

op.CalendarTemplate.prototype.analyze = function() {
	this.days = n2i.byClass('day');
	for (var i=0;i<this.days.length;i++) {
		var events = n2i.byClass(this.days[i],'event');
		for (var j=0;j<events.length;j++) {
			var top = events[j].cumulativeOffset().top;
			if (top>this.maxDayEvents[i]) {
				this.maxDayEvents[i]=top;
			}
		}
	}
}

n2i.onReady(
	function() {
		new op.CalendarTemplate();
	}
);