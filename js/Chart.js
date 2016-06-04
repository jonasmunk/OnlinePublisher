/**
 * A chart (line / column etc.)
 * <pre><strong>options:</strong> {
 *  element : «Element | ID»,
 *  name : «String»,
 *  (TODO many more)
 * }
 * </pre>
 * @constructor
 * @param {Object} options The options
 */
hui.ui.Chart = function(options) {
  this.options = options = options || {};
  this.element = hui.get(options.element);
  this.body  = {
    width : undefined, 
    height : undefined, 
    paddingTop : 10, 
    paddingBottom : 30, 
    paddingLeft : 10, 
    paddingRight : 10, 
    innerPaddingVertical : 10, 
    innerPaddingHorizontal : 10 
  };
  this.style = {
    border : true, 
    background : true, 
    colors : ['#36a','#69d','#acf'],
    legends : { position: 'right' , left: 0, top: 0 },
    pie : { radiusFactor: 0.9 , valueInLegend: false , left: 0, top: 0 }
  };
  this.xAxis = { labels:[], grid:true, concentration: 0.8 , maxLabels: 12};
  this.yAxis = { min:0, max:0, steps:8, above:false , factor: 10};
  
  this.dataSets = [];
  this.data = null;
  
  hui.ui.extend(this);
    
  if (this.options.source) {
    this.options.source.listen(this);
  }
};

hui.ui.Chart.create = function(options) { 
  options.element = hui.build('div',{
    'class' : 'hui_chart',
    parent : hui.get(options.parent),
    style : 'width: 100%; height: 100%;'
  });
  return new hui.ui.Chart(options);
};

hui.ui.Chart.prototype = {
  addDataSet : function(dataSet) {
    this.dataSets[this.dataSets.length] = dataSet;
  },
  setXaxisLabels : function(labels) {
    for (var i=0; i < labels.length; i++) {
      this.xAxis.labels[this.xAxis.labels.length] = {key:labels[i],label:labels[i]};
    }
  },
  setData : function(data) {
    if (!data.dataSets) {
      this.data = hui.ui.Chart.Util.convertData(data);
    } else {
      this.data = data;
    }
  },
  render : function() {
    var renderer = new hui.ui.Chart.Renderer(this);
    renderer.render();
  },
  $$layout : function() {
    this.render();
  },
  $objectsLoaded : function(data) {
    this.setData(data);
    this.render();
  }
};


//////////////////////// Data ////////////////////

hui.ui.Chart.Data = function(options) {
  this.xAxis = hui.override({ labels:[], grid:true, concentration:0.8 , maxLabels:12},options.xAxis);
  this.yAxis = hui.override({ min:0, max:0, steps:8, above:false , factor: 10},options.yAxis);
  this.dataSets = [];
};

hui.ui.Chart.Data.prototype = {
  addDataSet : function(set) {
    this.dataSets.push(set);
  }
};


///////////////////// Data set ////////////////////

hui.ui.Chart.DataSet = function(options) {
  options = options || {};
  this.dataSets = [];
  this.entries = options.entries || [];
  this.legend = null;
  this.style = {type:options.type || 'line'};
};

hui.ui.Chart.DataSet.prototype = {
  
  addDataSet : function(dataSet) {
    this.dataSets[this.dataSets.length] = dataSet;
  },
  setLegend : function(legend) {
    this.legend = legend;
  },
  isMultiDimensional : function() {
    return this.dataSets.length>0;
  },
  addEntry : function(key,value) {
    this.entries[this.entries.length] = {key:key,value:value};
  },
  setValues : function(graph,values) {
    for (var i=0; i < graph.xAxis.labels.length; i++) {
      if (values[i]) {
        this.addEntry(graph.xAxis.labels[i].key,values[i]);
      }
    }
  },
  getEntryValue : function(key) {
    var value = 0;
    for (var i=0;i<this.entries.length;i++) {
      if (this.entries[i].key==key) {
        return this.entries[i].value;
      }
    }
    return value;
  },
  getEntryValue2D : function(key) {
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
  },
  keysToValues : function(keys) {
    var values = [];
    for (var i=0;i<keys.length;i++) {
      values[i] = this.getEntryValue(keys[i].key);
    }
    return values;
  },
  keysToValues2D : function(keys) {
    var values = [];
    for (var i=0;i<keys.length;i++) {
      values[i] = this.getEntryValue2D(keys[i].key);
    }
    return values;
  },
  getValueRange : function(keys) {
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
    var min = Number.MAX_VALUE,
      max = Number.MIN_VALUE;
    for (var j=0;j<vals.length;j++) {
      min = Math.min(min,vals[j]);
      max = Math.max(max,vals[j]);
    }
    return {min:min,max:max};
  },
  getSubLegends : function() {
    var value = [];
    for (var i=0;i<this.dataSets.length;i++) {
      value[i] = this.dataSets[i].legend;
    }
    return value;
  }
};

/*********************************************************************/
/*                             Renderer                              */
/*********************************************************************/

hui.ui.Chart.Renderer = function(chart) {
  this.chart = chart;
  this.crisp = false;
  this.legends = [];
  this.state = { numColumns:0, currColumn:0, xLabels:[], yLabels:[], body:{left:0}, innerBody:{}, coordinateSystem: false, currColor:0 };
  this.width = null;
  this.height = null;
};

hui.ui.Chart.Renderer.prototype = {
  _registerLegend : function(color,label) {
    this.legends[this.legends.length] = {color:color,label:label};
  },
  _buildInnerBody : function() {
    var body = this.chart.body;
    var xLabels = this.state.xLabels;
    var space = 0;
    if (this.state.numColumns>0) {
      space = ( this.width - 2 * body.innerPaddingHorizontal - body.paddingLeft - body.paddingRight ) / xLabels.length;
    }
    var innerBody = {
      left : (body.innerPaddingHorizontal + this.state.body.left + space/2),
      top : (body.paddingTop + body.innerPaddingVertical),
      width : (this.state.body.width-2 * body.innerPaddingHorizontal - space),
      height : (this.state.body.height - body.innerPaddingVertical * 2 )
    };
    return innerBody;
  },
  _buildBody : function() {
    var body = this.chart.body,
    left = body.paddingLeft + this.state.yLabelWidth;
    return {
      left : left,
      top : body.paddingTop,
      width : this.width - left - body.paddingRight,
      height : this.height - body.paddingTop - body.paddingBottom,
      right : this.width - body.paddingRight,
      bottom : this.height - body.paddingBottom
    };
  }
};

hui.ui.Chart.Renderer.prototype.render = function() {
  
  this.width = this.chart.body.width || this.chart.element.clientWidth;
  this.height = this.chart.body.height || this.chart.element.clientHeight;
  
  hui.dom.clear(this.chart.element);
  this.canvas = hui.build('canvas',{parent:this.chart.element,width:this.width,height:this.height});
  if (!this.canvas.getContext) {
    return;
  }
  this.ctx = this.canvas.getContext("2d");
  
  if (!hui.isDefined(this.chart.data)) {
    return;
  }
  
  var i;
  
  // Extract basic info about the chart
  for (i=0;i<this.chart.data.dataSets.length;i++) {
    var set = this.chart.data.dataSets[i];
    if (set.style.type=='line' || set.style.type=='column') {
      this.state.coordinateSystem = true;
    }
    if (set.style.type=='column') {
      this.state.numColumns++;
    }
  }
  
  this.state.xLabels = this.chart.data.xAxis.labels;
  this.state.yLabels = hui.ui.Chart.Util.generateYLabels(this.chart);
  this.state.yLabelWidth = 0;
  for (i = 0; i < this.state.yLabels.length; i++) {
    this.state.yLabelWidth = Math.max(this.state.yLabelWidth, String(this.state.yLabels[i]).length * 5);
  }
  this.state.yLabelWidth+=5;
  this.state.body = this._buildBody();
  this.state.innerBody = this._buildInnerBody();

  // Render the coordinate system (below)
  if (this.state.coordinateSystem) {
    this.renderBody();
  }
  
  // Loop through data sets and render them
  var xLabels = this.state.xLabels;
  for (i=0;i<this.chart.data.dataSets.length;i++) {
    var set = this.chart.data.dataSets[i];
    var values, legend;
    if (set.style.type=='line') {
      values = set.keysToValues(xLabels);
      this.renderLineGraph( { values:values, style:set.style , legend:set.legend } );
    } else if (set.style.type=='column') {
      if (set.isMultiDimensional()) {
        values = set.keysToValues2D(xLabels);
        legend = set.getSubLegends();
      } else {
        values = set.keysToValues(xLabels);
        legend = set.legend;
      }
      this.renderColumnGraph( { values:values, style:set.style , legend: legend} ); 
    } else if (set.style.type=='pie') {
      values = set.keysToValues(xLabels);
      this.renderPie( { values:values, style:set.style } );
    }
  }
  
  // Render the coordinate system (above)
  if (this.shouldRenderCoordinateSystem) {
    this.renderPostBody();
  }
  
  // Render possible lengends
  this.renderLegends();
};

/**
 * Renders a legend box
 */
hui.ui.Chart.Renderer.prototype.renderLegends = function() {
  if (this.legends.length>0) {
    var position = this.chart.style.legends.position;
    var box = hui.build('div',{style:{position:'absolute',zIndex:5,width:this.width+'px'}});
    
    var html='<div class="hui_chart_legends" style="margin-right: '+(5-this.chart.style.legends.left)+'px; margin-top: '+(5+this.chart.style.legends.top)+'px;">';
    for (var i=0;i<this.legends.length;i++) {
      if (position=='bottom') {
        var style = 'padding: 2px; padding-right: 8px; float: left; white-space: nowrap;';
        if (i==this.legends.length-1) {
          style+='padding-right: 3px';
        }
      } else {
        var style = 'padding: 2px;';
      }
      html+='<div class="hui_chart_legend" style="'+style+'"><em style="background: '+this.legends[i].color+';"></em><span>'+this.legends[i].label+'</span></div>'
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
};

/**
 * Renders the body of the chart
 */
hui.ui.Chart.Renderer.prototype.renderBody = function() {
  
  var body = this.chart.body,
    stroke = 'rgb(255,255,255)',
    background = 'rgb(240,240,240)',
    state = this.state,
    innerBody = this.state.innerBody;
    
        stroke = '#eee'; // TODO Make this configurable
        background = '#fff';
    

  if (this.chart.style.background) {
    this.ctx.fillStyle = background;
    this.ctx.fillRect(
      state.body.left,
      state.body.top,
      state.body.width,
      state.body.height
    );
  }
  
  var mod = 1;
  /* Build X-axis*/
  var xLabels = this.state.xLabels;
  if (xLabels.length>this.chart.data.xAxis.maxLabels) {
    mod = Math.ceil(xLabels.length/this.chart.data.xAxis.maxLabels);
  }
  this.ctx.strokeStyle=stroke;
  for (var i=0;i<xLabels.length;i++) {
    var left = i*((innerBody.width)/(xLabels.length-1))+innerBody.left;
    var left = Math.round(left);

    if (mod<10 || (i % mod) ==0) {
      // Draw grid
      if (this.chart.data.xAxis.grid) {
        this.ctx.beginPath();
        this.ctx.moveTo(.5+left,state.body.top+.5);
        this.ctx.lineTo(.5+left,state.body.top+.5+state.body.height);
        this.ctx.stroke();
        this.ctx.closePath();
      }
    }
    if ((i % mod) ==0) {
      // Draw label
      var label = hui.build('span',{
        'class' : 'hui_chart_label',
        text : xLabels[i].label,
        before : this.canvas,
        style : {
          marginLeft : left-25+'px',
          marginTop : state.body.bottom + 4 + 'px',
                    color : '#999'
        }
      });
    }
  }
  this.ctx.strokeStyle=stroke;
  
  /* Build Y-axis*/
  var yLabels = this.state.yLabels.concat();
  yLabels.reverse();
  for (var i=0; i < yLabels.length ; i++) {
    // Draw grid
    var top = i*((state.body.height-body.innerPaddingVertical*2)/(yLabels.length-1))+body.paddingTop+body.innerPaddingVertical;
    top = Math.round(top);
    if (!this.chart.data.yAxis.above) {
      this.ctx.beginPath();
      this.ctx.moveTo(.5+state.body.left,top+.5);
      this.ctx.lineTo(.5+state.body.right,top+.5);
      this.ctx.stroke();
      this.ctx.closePath();
    }
    // Draw label
    var label = hui.build('span',{text:yLabels[i],style:{
      position: 'absolute',
      textAlign : 'right',
      width : this.state.yLabelWidth-5+'px',
      font : '9px Tahoma',
      marginTop : top-5+'px',
      marginLeft : body.paddingLeft+'px',
            color : '#999'
    }});
    this.canvas.parentNode.insertBefore(label,this.canvas);
  }
  
  // Draw a line at 0 if 
  if (!this.chart.data.yAxis.above && yLabels[0] > 0 && yLabels[yLabels.length-1] < 0) {
    var top = (state.body.height - body.innerPaddingVertical*2) * yLabels[0] / (yLabels[0] - yLabels[yLabels.length-1]) + body.paddingTop + body.innerPaddingVertical;
    top = Math.round(top);
    this.ctx.lineWidth = 2;
    this.ctx.strokeStyle=stroke;
    this.ctx.beginPath();
    this.ctx.moveTo(.5+state.body.left,top);
    this.ctx.lineTo(.5+state.body.right,top);
    this.ctx.stroke();
    this.ctx.closePath();
  }
};

hui.ui.Chart.Renderer.prototype.renderPostBody = function() {
  var body = this.chart.body;
  if (this.chart.data.yAxis.above) {

    this.ctx.strokeStyle='rgb(240,240,240)';
    var yLabels = this.state.yLabels.concat();
    yLabels.reverse();
    for (var i=0;i<yLabels.length;i++) {
      var top = i*((this.height-body.innerPaddingVertical*2-body.paddingTop-body.paddingBottom)/(yLabels.length-1))+body.paddingTop+body.innerPaddingVertical;
      top = Math.round(top);
      this.ctx.lineWidth = 1;
      this.ctx.beginPath();
      this.ctx.moveTo(.5+body.paddingLeft,top+.5);
      this.ctx.lineTo(.5+this.width-body.paddingRight,top+.5);
      this.ctx.stroke();
      this.ctx.closePath();
    }
    if (yLabels[0]>0 && yLabels[yLabels.length-1]<0) {
      var top = (this.height-body.innerPaddingVertical*2-body.paddingTop-body.paddingBottom)*yLabels[0]/(yLabels[0]-yLabels[yLabels.length-1])+body.paddingTop+body.innerPaddingVertical;
      top = Math.round(top);
      this.ctx.lineWidth = 2;
      this.ctx.strokeStyle='rgb(255,255,255)';
      this.ctx.beginPath();
      this.ctx.moveTo(.5+body.paddingLeft,top);
      this.ctx.lineTo(.5+this.width-body.paddingRight,top);
      this.ctx.stroke();
      this.ctx.closePath();
    }
  }
  if (this.chart.style.border) {
    this.ctx.lineWidth = 1;
    this.ctx.strokeStyle='rgb(230,230,230)';
    this.ctx.strokeRect(body.paddingLeft+.5,body.paddingTop+.5,this.width-body.paddingLeft-body.paddingRight,this.height-body.paddingTop-body.paddingBottom);
  }
};

hui.ui.Chart.Renderer.prototype.renderLineGraph = function(data) {
  var values = data.values;
  var xLabels = this.state.xLabels;
  var yLabels = this.state.yLabels;
  var yMin = yLabels[0];
  var yMax = yLabels[yLabels.length-1];
  var body = this.chart.body;
  var innerBody = this.state.innerBody;
  if (data.style.colors) {
    var color = data.style.colors[0];
  } else {
    var color = this.chart.style.colors[this.state.currColor];
    if (this.state.currColor+2>this.chart.style.colors.length) {
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
    var amount = (values[i] == undefined ? 0 : values[i]);
    var value = (amount-yMin)/(yMax-yMin);
    var top = this.height-value*(innerBody.height)-body.innerPaddingVertical-body.paddingBottom;
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
    this._registerLegend(color,data.legend);
  }
};


hui.ui.Chart.Renderer.prototype.renderColumnGraph = function(data) {
  var values = data.values;
  var xLabels = this.state.xLabels;
  var yLabels = this.state.yLabels;
  var yMin = yLabels[0];
  var yMax = yLabels[yLabels.length-1];
  var body = this.chart.body;
  var colors = data.style.colors ? data.style.colors : this.chart.style.colors;
  this.state.currColumn++;
  var innerBody = this.state.innerBody;
  var space = (this.width-body.paddingLeft-body.paddingRight)/xLabels.length*this.chart.data.xAxis.concentration;
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
      this._registerLegend(colors[i],data.legend[i]);
    };
  } else if (data.legend) {
    this._registerLegend(colors[0],data.legend);
  }
};

hui.ui.Chart.Renderer.prototype.renderOneColumn = function(val,color,body,innerBody,yMin,yMax,currTop,i,xLabels,space,thickness) {
  var value = (val-yMin)/(yMax-yMin);
  if (yMin<=0 && val<=0) {
    var top = innerBody.top+(innerBody.height)*yMax/(yMax-yMin)+currTop;
    var height = innerBody.height*Math.abs(val)/(yMax-yMin);
  } else if (yMin<=0) {
    var top = this.height-body.innerPaddingVertical-body.paddingBottom-value*(innerBody.height)-currTop;
    var height = (innerBody.height)*Math.abs(val)/(yMax-yMin);
  }
  else {
    var top = this.height-value*(this.height-body.innerPaddingVertical*2-body.paddingTop-body.paddingBottom)-body.innerPaddingVertical-body.paddingBottom-currTop;
    var height = (this.height-body.paddingBottom-top);
  }
  var left = i*((innerBody.width)/(xLabels.length-1))+innerBody.left;
  
  this.ctx.fillStyle = color;
  if (this.crisp) {
    this.ctx.fillRect(Math.round(left-space/2+thickness*(this.state.currColumn-1)),Math.floor(top),Math.ceil(thickness),Math.ceil(height));
  } else {
    this.ctx.fillRect(left-space/2+thickness*(this.state.currColumn-1),top,thickness,height);
  }
  return height;
};

hui.ui.Chart.Renderer.prototype.renderPie = function(data) {
  
  var values = data.values;
  var colors = data.style.colors ? data.style.colors : this.chart.style.colors;
  var total = hui.ui.Chart.Util.arraySum(values);

  var colorIndex = 0;
  var current = Math.PI*1.5;
  var cTop = this.height/2+this.chart.style.pie.top;
  var cLeft = this.width/2+this.chart.style.pie.left;
  var radius = this.height/2*this.chart.style.pie.radiusFactor;

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
      this._registerLegend(color,this.state.xLabels[i].label);
    } else {
      this._registerLegend(color,values[i]+' '+this.state.xLabels[i].label);
    }
    if (colorIndex+2>colors.length) {
      colorIndex = 0;
    } else {
      colorIndex++;
    }
  }
  
};





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
};

hui.ui.Chart.Util.getYrange = function(graph) {
  var min = graph.yAxis.min,
    max = graph.yAxis.max,
    data = graph.data;
  for (var i=0;i<data.dataSets.length;i++) {
    var range = data.dataSets[i].getValueRange(data.xAxis.labels);
    min = Math.min(min,range.min);
    max = Math.max(max,range.max);
  }
  var factor = max/graph.yAxis.steps;
  if (factor < graph.yAxis.factor) {
    factor = Math.ceil(factor);
  } else {
    factor = graph.yAxis.factor;
  }
  if (max != Number.MIN_VALUE) {
    max = Math.ceil(max/factor/graph.yAxis.steps)*factor*graph.yAxis.steps;
  } else {
    max = graph.yAxis.steps;
  }
  return {min:min,max:max};
};

hui.ui.Chart.Util.arraySum = function(values) {
  var total = 0;
  for (var i=0;i<values.length;i++) {
    total+=values[i];
  }
  return total;
};

/** Converts a simple data-representation into a class-based stucture */
hui.ui.Chart.Util.convertData = function(obj) {
  var labels = [],keys = [];
  for (var i=0; i < obj.sets.length; i++) {
    var set = obj.sets[i];
    if (hui.isArray(set.entries)) {
      for (var j=0; j < set.entries.length; j++) {
        var entry = set.entries[j];
        if (!hui.array.contains(keys,entry.key)) {
          keys.push(entry.key)
          labels.push({key:entry.key,label:entry.label || entry.key});          
        }
      }
    } else {
      for (var key in set.entries) {
        if (!hui.array.contains(keys,key)) {
          keys.push(key)
          labels.push({key:key,label:key});
        }
      }
    }
  }
  var options = {xAxis:{labels:labels}};
  if (obj.axis && obj.axis.x && obj.axis.x.time===true) {
    options.xAxis.resolution = 'time';
  }
  if (obj.axis && obj.axis.x && hui.isArray(obj.axis.x.labels)) {
    options.xAxis.labels = obj.axis.x.labels;
  }
  var data = new hui.ui.Chart.Data(options);
    
  for (var i=0; i < obj.sets.length; i++) {
    var set = obj.sets[i];
    var dataSet = new hui.ui.Chart.DataSet({type:set.type});
    if (hui.isArray(set.entries)) {
      for (var j=0; j < set.entries.length; j++) {
        var entry = set.entries[j];
        dataSet.addEntry(entry.key,entry.value);
      };
    } else {
      for (var key in set.entries) {
        dataSet.addEntry(key,set.entries[key]);
      }
    }
    data.addDataSet(dataSet);
  }
  hui.log(data)
  return data;
};