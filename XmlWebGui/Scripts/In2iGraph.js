if (!N2i) var N2i = {};

N2i.Graph = function() {
	this.body = {width: 380, height: 250, paddingTop: 10, paddingBottom: 30, paddingLeft: 40, paddingRight: 10,innerPaddingVertical: 15,innerPaddingHorizontal: 30};
	this.xAxis = {labels:[]};
	this.yAxis = {min:0,max:0,steps: 8};
	this.data = [];
}

N2i.Graph.prototype.addData = function(data) {
	this.data[this.data.length] = data;
}

N2i.Graph.prototype.generateYLabels = function() {
	var range = this.getYrange();
	var labels = [];
	for (var i=0;i<=this.yAxis.steps;i++) {
		labels[labels.length] = Math.round(range.min+(range.max-range.min)/this.yAxis.steps*i);
	}
	return labels;
}

N2i.Graph.prototype.getYrange = function() {
	var min=this.yAxis.min;
	var max=this.yAxis.max;
	for (var i=0;i<this.data.length;i++) {
		for (var j=0;j<this.data[i].values.length;j++) {
			if (this.data[i].values[j]<min) {
				min = this.data[i].values[j];
			}
			if (this.data[i].values[j]>max) {
				max = this.data[i].values[j];
			}
		}
	}
	return {min:min,max:max};
}

N2i.Graph.prototype.generateXLabels = function() {
	var count = this.getMaxValuesCount();
	//alert(count);
	var labels = new Array();
	for (var i=0;i<count;i++) {
		if (this.xAxis.labels[i]) {
			labels[i] = this.xAxis.labels[i];
		} else {
			labels[i] = '';
		}
	}
	return labels;
}
/**
 * Go thru all datasets & find count the max value count
 */
N2i.Graph.prototype.getMaxValuesCount = function() {
	var max = 0;
	for (var i=0;i<this.data.length;i++) {
		if (this.data[i].values.length>max) {
			max = this.data[i].values.length;
		}
	}
	return max;
}

N2i.Graph.Renderer = function(graph) {
	this.graph = graph;
}

N2i.Graph.Renderer.prototype.render = function(canvasId) {
	this.canvas = document.getElementById(canvasId);
	this.ctx = this.canvas.getContext("2d");
	this.renderBody();
	for (var i=0;i<this.graph.data.length;i++) {
		if (this.graph.data[i].type=='line') {
			this.renderLineGraph(this.graph.data[i]);
		} else if (this.graph.data[i].type=='column') {
			this.renderColumnGraph(this.graph.data[i]);
		}
	}
}

N2i.Graph.Renderer.prototype.renderBody = function() {

	var body = this.graph.body;
	
	var lingrad = this.ctx.createLinearGradient(0,0,200,200);
  	lingrad.addColorStop(0, 'rgb(240,240,240)');
  	lingrad.addColorStop(0.5, 'rgb(240,240,240)');
  	lingrad.addColorStop(1, 'rgb(240,240,240)');

	this.ctx.fillStyle='rgb(240,240,240)';
	this.ctx.fillRect(body.paddingLeft,body.paddingTop,body.width-body.paddingLeft-body.paddingRight,body.height-body.paddingTop-body.paddingBottom);
	
	var xLabels = this.graph.generateXLabels();
	this.ctx.strokeStyle='rgb(255,255,255)';
	for (var i=0;i<xLabels.length;i++) {
		var left = i*((body.width-2*body.innerPaddingHorizontal-body.paddingLeft-body.paddingRight)/(xLabels.length-1))+body.paddingLeft+body.innerPaddingHorizontal;
		var left = Math.round(left);
		this.ctx.beginPath();
		this.ctx.moveTo(.5+left,body.paddingTop+.5);
		this.ctx.lineTo(.5+left,body.paddingTop+.5+body.height);
		this.ctx.stroke();
		this.ctx.closePath();
		var label = document.createElement('span');
		label.appendChild(document.createTextNode(xLabels[i]));
		label.style.position='absolute';
		label.style.marginLeft=left-25+'px';
		//label.style.background = '#aaa';
		label.style.textAlign = 'center';
		label.style.width = '50px';
		label.style.font='9px Tahoma';
		label.style.marginTop=body.height-body.paddingBottom+4+'px';
		this.canvas.parentNode.insertBefore(label,this.canvas);
	}
	this.ctx.strokeStyle='rgb(255,255,255)';
	var yLabels = this.graph.generateYLabels();
	yLabels.reverse();
	for (var i=0;i<yLabels.length;i++) {
		//var top = body.height-data[i]*(body.height-body.innerPadding*2-body.padding*2)-body.innerPadding-body.padding;
		var top = i*((body.height-body.innerPaddingVertical*2-body.paddingTop-body.paddingBottom)/(yLabels.length-1))+body.paddingTop+body.innerPaddingVertical;
		top = Math.round(top);
		this.ctx.beginPath();
		this.ctx.moveTo(.5+body.paddingLeft,top+.5);
		this.ctx.lineTo(.5+body.width-body.paddingRight,top+.5);
		this.ctx.stroke();
		this.ctx.closePath();
		var label = document.createElement('span');
		label.appendChild(document.createTextNode(yLabels[i]));
		label.style.position='absolute';
		label.style.textAlign='right';
		label.style.width=body.paddingLeft-3+'px';
		//label.style.background = '#aaa';
		label.style.font='9px Tahoma';
		label.style.marginTop=top-5+'px';
		this.canvas.parentNode.insertBefore(label,this.canvas);
	}
	if (yLabels[0]>0 && yLabels[yLabels.length-1]<0) {
		var top = (body.height-body.innerPaddingVertical*2-body.paddingTop-body.paddingBottom)*yLabels[0]/(yLabels[0]-yLabels[yLabels.length-1])+body.paddingTop+body.innerPaddingVertical;
		top = Math.round(top);
		this.ctx.lineWidth = 2;
		this.ctx.strokeStyle='rgb(255,255,255)';
		this.ctx.beginPath();
		this.ctx.moveTo(.5+body.paddingLeft,top);
		this.ctx.lineTo(.5+body.width-body.paddingRight,top);
		this.ctx.stroke();
		this.ctx.closePath();
	}
	this.ctx.strokeStyle='rgb(230,230,230)';
	this.ctx.strokeRect(body.paddingLeft+.5,body.paddingTop+.5,body.width-body.paddingLeft-body.paddingRight,body.height-body.paddingTop-body.paddingBottom);
}

N2i.Graph.Renderer.prototype.renderLineGraph = function(data) {
	var values = data.values;
	var xLabels = this.graph.generateXLabels();
	var yLabels = this.graph.generateYLabels();
	var yMin = yLabels[0];
	var yMax = yLabels[yLabels.length-1];
	var body = this.graph.body;
	this.ctx.strokeStyle = data.color ? data.color : "rgb(150, 200, 255)";
	this.ctx.lineWidth = data.width ? data.width : 3;
	this.ctx.lineCap = this.ctx.lineJoin = 'round';
	this.ctx.beginPath();
	for (var i=0;i<xLabels.length;i++) {
		var amount = (values[i]==undefined ? 0 : values[i]);
		var value = (amount-yMin)/(yMax-yMin);
		//alert(value);
		var top = body.height-value*(body.height-body.innerPaddingVertical*2-body.paddingTop-body.paddingBottom)-body.innerPaddingVertical-body.paddingBottom;
		var left = i*((body.width-2*body.innerPaddingHorizontal-body.paddingLeft-body.paddingRight)/(xLabels.length-1))+body.paddingLeft+body.innerPaddingHorizontal;
		if (i==0) {
			this.ctx.moveTo(left+.5,top+.5);
		} else {
			this.ctx.lineTo(left+.5,top+.5);
		}
	}
	this.ctx.stroke();
	this.ctx.closePath();
	
}

N2i.Graph.Renderer.prototype.renderColumnGraph = function(data) {
	var values = data.values;
	var xLabels = this.graph.generateXLabels();
	var yLabels = this.graph.generateYLabels();
	var yMin = yLabels[0];
	var yMax = yLabels[yLabels.length-1];
	var body = this.graph.body;
	this.ctx.fillStyle = data.color ? data.color : "rgb(150, 200, 255)";
	this.ctx.lineCap = this.ctx.lineJoin = 'round';
	this.ctx.beginPath();
	for (var i=0;i<xLabels.length;i++) {
		if (values[i]) {
			if (yMin<=0 && values[i]<=0) {
				var value = (values[i]-yMin)/(yMax-yMin);
				var top = body.paddingTop+body.innerPaddingVertical+(body.height-body.innerPaddingVertical*2-body.paddingTop-body.paddingBottom)*yMax/(yMax-yMin);
				var height = (body.height-body.innerPaddingVertical*2-body.paddingTop-body.paddingBottom)*Math.abs(values[i])/(yMax-yMin);
			} else if (yMin<=0) {
				var value = (values[i]-yMin)/(yMax-yMin);
				var top = body.height-value*(body.height-body.innerPaddingVertical*2-body.paddingTop-body.paddingBottom)-body.innerPaddingVertical-body.paddingBottom;
				var height = (body.height-body.innerPaddingVertical*2-body.paddingTop-body.paddingBottom)*Math.abs(values[i])/(yMax-yMin);
			}
			else {
				var value = (values[i]-yMin)/(yMax-yMin);
				var top = body.height-value*(body.height-body.innerPaddingVertical*2-body.paddingTop-body.paddingBottom)-body.innerPaddingVertical-body.paddingBottom;
				var height = (body.height-body.paddingBottom-Math.round(top));
			}
			var left = i*((body.width-2*body.innerPaddingHorizontal-body.paddingLeft-body.paddingRight)/(xLabels.length-1))+body.paddingLeft+body.innerPaddingHorizontal;
			this.ctx.fillRect(left-10,top,20,height);
		}
	}
	this.ctx.stroke();
	this.ctx.closePath();
	
}