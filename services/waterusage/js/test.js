hui.ui.listen({
	$ready : function() {
		hui.ui.request({
			url : 'services/waterusage/load.php',
			parameters : {number:'20116254'},
			$object : function(obj) {
				this._renderRecord(obj);
				this._renderChart(obj);
			}.bind(this),
			$failure : function() {
				alert('Something terrible happened')
			}
		});
		return;
		
		this._renderChart();
		this._renderRecord([
			{date:'15/4-2010',value:3242},
			{date:'15/4-2011',value:54343},
			{date:'15/4-2012',value:65464}
		]);
	},
	_renderChart : function(entries) {
		var chart = new hui.ui.Chart.create({parent:'chart'});
				
		chart.setData({
			sets : [
				{type:'column', entries:entires} //w {'January':10,'February':20,'March':15,'April':45,'May':56}
			]
		});
		
		chart.render();
	},
	_renderRecord : function(data) {
		var record = hui.get('record');
		var body = hui.get.firstByTag(record,'tbody');
		for (var i=0; i < data.length; i++) {
			var row = hui.build('tr',{parent:body});
			hui.build('th',{parent:row,text:data[i].date});
			hui.build('td',{parent:row,text:data[i].value});
		};
	}
})