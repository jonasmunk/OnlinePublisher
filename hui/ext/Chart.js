if (!N2i) var N2i = {};

hui.ui.Chart = function() {
	this.body  = { width: 380, height: 250, paddingTop: 10, paddingBottom: 30, paddingLeft: 40, paddingRight: 10, innerPaddingVertical: 10, innerPaddingHorizontal: 10 };
	this.style = { border:true, background:true, colors:['#36a','#69d','#acf']};
	this.style.legends = { position: 'right' , left: 0, top: 0 };
	this.style.pie = { radiusFactor: .9 , valueInLegend: false , left: 0, top: 0 };
	this.xAxis = { labels:[], grid:true, concentration:.8 , maxLabels:12};
	this.yAxis = { min:0, max:0, steps:8, above:false , factor: 10};
	this.dataSets = [];
}

hui.ui.Chart.prototype.addDataSet = function(dataSet) {
	this.dataSets[this.dataSets.length] = dataSet;
}

hui.ui.Chart.prototype.setXaxisLabels = function(labels) {
	for (var i=0; i < labels.length; i++) {
		this.xAxis.labels[this.xAxis.labels.length] = {key:labels[i],label:labels[i]};
	};
}




/*********************************************************************/
/*                             Data set                              */
/*********************************************************************/

hui.ui.Chart.DataSet = function(type) {
	type = type ? type : 'line';
	this.dataSets = [];
	this.entries = [];
	this.legend = null;
	this.style = {type:type};
}

hui.ui.Chart.DataSet.prototype.addDataSet = function(dataSet) {
	this.dataSets[this.dataSets.length] = dataSet;
}

hui.ui.Chart.DataSet.prototype.setLegend = function(legend) {
	this.legend = legend;
}

hui.ui.Chart.DataSet.prototype.isMultiDimensional = function() {
	return this.dataSets.length>0;
}

hui.ui.Chart.DataSet.prototype.addEntry = function(key,value) {
	this.entries[this.entries.length] = {key:key,value:value};
}


hui.ui.Chart.DataSet.prototype.setValues = function(graph,values) {
	for (var i=0; i < graph.xAxis.labels.length; i++) {
		if (values[i]) {
			this.addEntry(graph.xAxis.labels[i].key,values[i])
		}
	};
}

hui.ui.Chart.DataSet.prototype.getEntryValue = function(key) {
	var value = 0;
	for (var i=0;i<this.entries.length;i++) {
		if (this.entries[i].key==key) {
			return this.entries[i].value;
		}
	}
	return value;
}

hui.ui.Chart.DataSet.prototype.getEntryValue2D = function(key) {
	var value = [];
	for (var i=0;i<this.dataSets.length;i++) {
		var set = this.dataSets[i];
		for (var j=0;j<set.entries.length;j++) {
			if (set.entries[j].key==key) {
				value[i] = set.entries[j].value;
			}
		}
		if (!value[i]) {
			value[i]=0;
		}
	}
	return value;
}

hui.ui.Chart.DataSet.prototype.keysToValues = function(keys) {
	var values = [];
	for (var i=0;i<keys.length;i++) {
		values[i] = this.getEntryValue(keys[i].key);
	}
	return values;
}

hui.ui.Chart.DataSet.prototype.keysToValues2D = function(keys) {
	var values = [];
	for (var i=0;i<keys.length;i++) {
		values[i] = this.getEntryValue2D(keys[i].key);
	}
	return values;
}

hui.ui.Chart.DataSet.prototype.getValueRange = function(keys) {
	var vals = [];
	if (this.isMultiDimensional()) {
		var vals2D = this.keysToValues2D(keys);
		for (var i=0;i<vals2D.length;i++) {
			var sum = 0;
			for (var j=0;j<vals2D[i].length;j++) {
				sum+=vals2D[i][j];
			}
			vals[i] = sum;
		}
	} else {
		vals = this.keysToValues(keys);
	}
	var min = Number.MAX_VALUE;
	var max = Number.MIN_VALUE;
	for (var i=0;i<vals.length;i++) {
		if (vals[i]<min) {
			min = vals[i];
		}
		if (vals[i]>max) {
			max = vals[i];
		}
	}
	return {min:min,max:max};
}

hui.ui.Chart.DataSet.prototype.getSubLegends = function() {
	var value = [];
	for (var i=0;i<this.dataSets.length;i++) {
		value[i] = this.dataSets[i].legend;
	}
	return value;
}






/*********************************************************************/
/*                             Renderer                              */
/*********************************************************************/

hui.ui.Chart.Renderer = function(graph) {
	this.graph = graph;
	this.crisp = false;
	this.legends = [];
	this.state = { numColumns:0, currColumn:0, xLabels:[], yLabels:[], innerBody:{}, coordinateSystem: false, currColor:0 };
}

hui.ui.Chart.Renderer.prototype.registerLegend = function(color,label) {
	this.legends[this.legends.length] = {color:color,label:label};
}

hui.ui.Chart.Renderer.prototype.render = function(canvasId) {
	
	this.canvas = document.getElementById(canvasId);
	this.canvas.parentNode.onmousemove = function(e) {
		//window.status = e.clientX+" / "+e.clientY;
	}
	this.ctx = this.canvas.getContext("2d");
	// Extract basic info about the chart
	for (var i=0;i<this.graph.dataSets.length;i++) {
		var set = this.graph.dataSets[i];
		if (set.style.type=='line' || set.style.type=='column') {
			this.state.coordinateSystem = true;
		}
		if (set.style.type=='column') {
			this.state.numColumns++;
		}
	}
	this.state.xLabels = this.graph.xAxis.labels;
	this.state.yLabels = hui.ui.Chart.Util.generateYLabels(this.graph);
	this.state.innerBody = this.getInnerBody();

	// Render the coordinate system (below)
	if (this.state.coordinateSystem) {
		this.renderBody();
	}
	
	// Loop through data sets and render them
	var xLabels = this.state.xLabels;
	for (var i=0;i<this.graph.dataSets.length;i++) {
		var set = this.graph.dataSets[i];
		if (set.style.type=='line') {
			var values = set.keysToValues(xLabels);
			this.renderLineGraph( { values:values, style:set.style , legend:set.legend } );
		} else if (set.style.type=='column') {
			if (set.isMultiDimensional()) {
				var values = set.keysToValues2D(xLabels);
				var legend = set.getSubLegends();
			} else {
				var values = set.keysToValues(xLabels);
				var legend = set.legend;
			}
			this.renderColumnGraph( { values:values, style:set.style , legend: legend} );	
		} else if (set.style.type=='pie') {
			var values = set.keysToValues(xLabels);
			this.renderPie( { values:values, style:set.style } );
		}
	}
	
	// Render the coordinate system (above)
	if (this.shouldRenderCoordinateSystem) {
		this.renderPostBody();
	}
	
	// Render possible lengends
	this.renderLegends();
}

/**
 * Renders a legend box
 */
hui.ui.Chart.Renderer.prototype.renderLegends = function() {
	if (this.legends.length>0) {
		var position = this.graph.style.legends.position;
		var box = document.createElement('div');
		box.style.position='absolute';
		box.style.zIndex = 5;
		box.style.width=this.graph.body.width+'px';
		
		var html='<div style="float: right; border:1px solid #ddd; background: #fff; margin-right: '+(5-this.graph.style.legends.left)+'px; margin-top: '+(5+this.graph.style.legends.top)+'px; position: relative; padding: 3px;">';
		for (var i=0;i<this.legends.length;i++) {
			if (position=='bottom') {
				var style = 'padding: 2px; padding-right: 8px; float: left; white-space: nowrap;';
				if (i==this.legends.length-1) style+='padding-right: 3px';
			} else {
				var style = 'padding: 2px;';
			}
			html+='<div style="'+style+'"><div style="float: left; border: 1px solid #aaa; background: '+this.legends[i].color+'; width: 12px; height: 12px; margin-right: 5px;"></div>'+this.legends[i].label+'</div>'
		}
		html+='</div>';
		box.innerHTML = html;
		if (position=='right') {
			this.canvas.parentNode.insertBefore(box,this.canvas);
		} else if (position=='bottom') {
			this.canvas.parentNode.appendChild(box);
			var y = document.createElement('div');
			y.appendChild(box);
			this.canvas.parentNode.appendChild(y);
		}
	}
}

/**
 * Renders the body of the chart
 */
hui.ui.Chart.Renderer.prototype.renderBody = function() {

	var body = this.graph.body;

	if (this.graph.style.background) {
		this.ctx.fillStyle='rgb(240,240,240)';
		this.ctx.fillRect(body.paddingLeft,body.paddingTop,body.width-body.paddingLeft-body.paddingRight,body.height-body.paddingTop-body.paddingBottom);
	}
	var innerBody = this.state.innerBody;
	
	var mod = 1;
	/* Build X-axis*/
	var xLabels = this.state.xLabels;
	if (xLabels.length>this.graph.xAxis.maxLabels) {
		mod = Math.ceil(xLabels.length/this.graph.xAxis.maxLabels);
	}
	this.ctx.strokeStyle='rgb(255,255,255)';
	for (var i=0;i<xLabels.length;i++) {
		var left = i*((innerBody.width)/(xLabels.length-1))+innerBody.left;
		var left = Math.round(left);

		// Draw grid
		if (this.graph.xAxis.grid) {
			this.ctx.beginPath();
			this.ctx.moveTo(.5+left,body.paddingTop+.5);
			this.ctx.lineTo(.5+left,body.paddingTop+.5+body.height-body.paddingTop-body.paddingBottom);
			this.ctx.stroke();
			this.ctx.closePath();
		}
		if ((i % mod) ==0) {
		// Draw label
		var label = document.createElement('span');
		label.appendChild(document.createTextNode(xLabels[i].label));
		label.style.position='absolute';
		label.style.marginLeft=left-25+'px';
		label.style.textAlign = 'center';
		label.style.width = '50px';
		label.style.font='9px Tahoma';
		label.style.marginTop=body.height-body.paddingBottom+4+'px';
		this.canvas.parentNode.insertBefore(label,this.canvas);
		}
	}
	this.ctx.strokeStyle='rgb(255,255,255)';
	
	/* Build Y-axis*/
	var yLabels = this.state.yLabels.concat();
	yLabels.reverse();
	for (var i=0;i<yLabels.length;i++) {
		// Draw grid
		var top = i*((body.height-body.innerPaddingVertical*2-body.paddingTop-body.paddingBottom)/(yLabels.length-1))+body.paddingTop+body.innerPaddingVertical;
		top = Math.round(top);
		if (!this.graph.yAxis.above) {
			this.ctx.beginPath();
			this.ctx.moveTo(.5+body.paddingLeft,top+.5);
			this.ctx.lineTo(.5+body.width-body.paddingRight,top+.5);
			this.ctx.stroke();
			this.ctx.closePath();
		}
		// Draw label
		var label = document.createElement('span');
		label.appendChild(document.createTextNode(yLabels[i]));
		label.style.position='absolute';
		label.style.textAlign='right';
		label.style.width=body.paddingLeft-3+'px';
		label.style.font='9px Tahoma';
		label.style.marginTop=top-5+'px';
		this.canvas.parentNode.insertBefore(label,this.canvas);
	}
	// Draw a line at 0 if 
	if (!this.graph.yAxis.above && yLabels[0]>0 && yLabels[yLabels.length-1]<0) {
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
}

hui.ui.Chart.Renderer.prototype.renderPostBody = function() {
	var body = this.graph.body;
	if (this.graph.yAxis.above) {

		this.ctx.strokeStyle='rgb(240,240,240)';
		var yLabels = this.state.yLabels.concat();
		yLabels.reverse();
		for (var i=0;i<yLabels.length;i++) {
			var top = i*((body.height-body.innerPaddingVertical*2-body.paddingTop-body.paddingBottom)/(yLabels.length-1))+body.paddingTop+body.innerPaddingVertical;
			top = Math.round(top);
			this.ctx.lineWidth = 1;
			this.ctx.beginPath();
			this.ctx.moveTo(.5+body.paddingLeft,top+.5);
			this.ctx.lineTo(.5+body.width-body.paddingRight,top+.5);
			this.ctx.stroke();
			this.ctx.closePath();
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
	}
	if (this.graph.style.border) {
		this.ctx.lineWidth = 1;
		this.ctx.strokeStyle='rgb(230,230,230)';
		this.ctx.strokeRect(body.paddingLeft+.5,body.paddingTop+.5,body.width-body.paddingLeft-body.paddingRight,body.height-body.paddingTop-body.paddingBottom);
	}
}

hui.ui.Chart.Renderer.prototype.getInnerBody = function() {
	var body = this.graph.body;
	var xLabels = this.state.xLabels;
	var space = 0;
	if (this.state.numColumns>0) {
		space = (body.width-2*body.innerPaddingHorizontal-body.paddingLeft-body.paddingRight)/xLabels.length;
	}
	var innerBody = {
		left:(body.innerPaddingHorizontal+body.paddingLeft+space/2),
		top:(body.paddingTop+body.innerPaddingVertical),
		width:(body.width-2*body.innerPaddingHorizontal-body.paddingLeft-body.paddingRight-space),
		height:(body.height-body.innerPaddingVertical*2-body.paddingTop-body.paddingBottom)
	};
	return innerBody;
}

hui.ui.Chart.Renderer.prototype.renderLineGraph = function(data) {
	var values = data.values;
	var xLabels = this.state.xLabels;
	var yLabels = this.state.yLabels;
	var yMin = yLabels[0];
	var yMax = yLabels[yLabels.length-1];
	var body = this.graph.body;
	var innerBody = this.state.innerBody;
	if (data.style.colors) {
		var color = data.style.colors[0];
	} else {
		var color = this.graph.style.colors[this.state.currColor];
		if (this.state.currColor+2>this.graph.style.colors.length) {
			this.state.currColor = 0;
		} else {
			this.state.currColor++;
		}
	}
	this.ctx.strokeStyle = color;
	this.ctx.lineWidth = data.width ? data.width : 3;
	this.ctx.lineCap = this.ctx.lineJoin = 'round';
	this.ctx.beginPath();
	for (var i=0;i<xLabels.length;i++) {
		var amount = (values[i]==undefined ? 0 : values[i]);
		var value = (amount-yMin)/(yMax-yMin);
		//alert(value);
		var top = body.height-value*(innerBody.height)-body.innerPaddingVertical-body.paddingBottom;
		var left = i*(innerBody.width/(xLabels.length-1))+innerBody.left;
		if (i==0) {
			this.ctx.moveTo(left+.5,top+.5);
		} else {
			this.ctx.lineTo(left+.5,top+.5);
		}
	}
	this.ctx.stroke();
	this.ctx.closePath();
	
	if (data.legend) {
		this.registerLegend(color,data.legend);
	}
}


hui.ui.Chart.Renderer.prototype.renderColumnGraph = function(data) {
	var values = data.values;
	var xLabels = this.state.xLabels;
	var yLabels = this.state.yLabels;
	var yMin = yLabels[0];
	var yMax = yLabels[yLabels.length-1];
	var body = this.graph.body;
	var colors = data.style.colors ? data.style.colors : this.graph.style.colors;
	this.state.currColumn++;
	var innerBody = this.state.innerBody;
	var space = (body.width-body.paddingLeft-body.paddingRight)/xLabels.length*this.graph.xAxis.concentration;
	var thickness = space/this.state.numColumns;
	this.ctx.lineCap = this.ctx.lineJoin = 'round';
	this.ctx.beginPath();
	for (var i=0;i<xLabels.length;i++) {
		if (values[i]) {
			var colorIndex = 0;
			var currTop = 0;
			if (values[i] instanceof Array) {
				for (var j=0;j<values[i].length;j++) {
					var val = values[i][j];
					currTop+=this.renderOneColumn(val,colors[colorIndex],body,innerBody,yMin,yMax,currTop,i,xLabels,space,thickness);
					
					if (colorIndex+2>colors.length) {
						colorIndex = 0;
					} else {
						colorIndex++;
					}
				}
			} else {
				currTop+=this.renderOneColumn(values[i],colors[colorIndex],body,innerBody,yMin,yMax,currTop,i,xLabels,space,thickness);
			}
		}
	}
	this.ctx.stroke();
	this.ctx.closePath();
	
	if (data.legend && data.legend instanceof Array) {
		for (var i=0; i < data.legend.length; i++) {
			this.registerLegend(colors[i],data.legend[i]);
		};
	} else if (data.legend) {
		this.registerLegend(colors[0],data.legend);
	}
}

hui.ui.Chart.Renderer.prototype.renderOneColumn = function(val,color,body,innerBody,yMin,yMax,currTop,i,xLabels,space,thickness) {
	var value = (val-yMin)/(yMax-yMin);
	if (yMin<=0 && val<=0) {
		var top = innerBody.top+(innerBody.height)*yMax/(yMax-yMin)+currTop;
		var height = innerBody.height*Math.abs(val)/(yMax-yMin);
	} else if (yMin<=0) {
		var top = body.height-body.innerPaddingVertical-body.paddingBottom-value*(innerBody.height)-currTop;
		var height = (innerBody.height)*Math.abs(val)/(yMax-yMin);
	}
	else {
		var top = body.height-value*(body.height-body.innerPaddingVertical*2-body.paddingTop-body.paddingBottom)-body.innerPaddingVertical-body.paddingBottom-currTop;
		var height = (body.height-body.paddingBottom-top);
	}
	var left = i*((innerBody.width)/(xLabels.length-1))+innerBody.left;
	
	this.ctx.fillStyle = color;
	if (this.crisp) {
		this.ctx.fillRect(Math.round(left-space/2+thickness*(this.state.currColumn-1)),Math.floor(top),Math.ceil(thickness),Math.ceil(height));
	} else {
		this.ctx.fillRect(left-space/2+thickness*(this.state.currColumn-1),top,thickness,height);
	}
	return height;
}

hui.ui.Chart.Renderer.prototype.renderPie = function(data) {
	
	var values = data.values;
	var colors = data.style.colors ? data.style.colors : this.graph.style.colors;
	var total = hui.ui.Chart.Util.arraySum(values);

	var colorIndex = 0;
	var current = Math.PI*1.5;
	var cTop = this.graph.body.height/2+this.graph.style.pie.top;
	var cLeft = this.graph.body.width/2+this.graph.style.pie.left;
	var radius = this.graph.body.height/2*this.graph.style.pie.radiusFactor;

	for (var i=0;i<values.length;i++) {
		this.ctx.beginPath();
		var color = colors[colorIndex]
		this.ctx.fillStyle = color;
		var rads = values[i]/total*(Math.PI*2);
		this.ctx.moveTo(cLeft,cTop);
		this.ctx.arc(cLeft,cTop,radius,current,current+rads,false);
		this.ctx.lineTo(cLeft,cTop);
		this.ctx.fill();
		this.ctx.closePath();
		current+=rads;
		
		if (!true) {
			this.registerLegend(color,this.state.xLabels[i].label);
		} else {
			this.registerLegend(color,values[i]+' '+this.state.xLabels[i].label);
		}
		if (colorIndex+2>colors.length) {
			colorIndex = 0;
		} else {
			colorIndex++;
		}
	}
	
}





/*********************************************************************/
/*                           Utitlities                              */
/*********************************************************************/

hui.ui.Chart.Util = function() {}

hui.ui.Chart.Util.generateYLabels = function(graph) {
	var range = hui.ui.Chart.Util.getYrange(graph);
	var labels = [];
	for (var i=0;i<=graph.yAxis.steps;i++) {
		labels[labels.length] = Math.round(range.min+(range.max-range.min)/graph.yAxis.steps*i);
	}
	return labels;
}

hui.ui.Chart.Util.getYrange = function(graph) {
	var min=graph.yAxis.min;
	var max=graph.yAxis.max;
	for (var i=0;i<graph.dataSets.length;i++) {
		var range = graph.dataSets[i].getValueRange(graph.xAxis.labels);
		if (range.min<min) {
			min=range.min;
		}
		if (range.max>max) {
			max=range.max;
		}
	}
	var factor = max/graph.yAxis.steps;
	if (factor<graph.yAxis.factor) {
		factor = Math.ceil(factor);
	} else {
		factor = graph.yAxis.factor;
	}
	if (max!=Number.MIN_VALUE) {
		max = Math.ceil(max/factor/graph.yAxis.steps)*factor*graph.yAxis.steps;
	} else {
		max = graph.yAxis.steps;
	}
	return {min:min,max:max};
}

hui.ui.Chart.Util.arraySum = function(values) {
	var total = 0;
	for (var i=0;i<values.length;i++) {
		total+=values[i];
	}
	return total;
}