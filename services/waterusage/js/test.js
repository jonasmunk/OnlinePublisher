hui.ui.listen({
	$ready : function() {
		this.numberInput = new hui.ui.Input({element:'number'});
		this.numberInput.listen({
			$valueChanged : function(value) {
				this._fetch(value);
			}.bind(this)
		})
		
		this.numberInput.setValue('26108502');
		this._fetch('20116254');
	},
	_fetch : function(number) {
		hui.ui.request({
			url : 'load.php',
			parameters : {number:number},
			$object : function(obj) {
				this._renderRecord(obj.usage);
				this._renderChart(obj.usage);
				this._renderInfo(obj.info);
			}.bind(this),
			$failure : function() {
				alert('Something terrible happened')
			},
			$exception : function(e) {
				throw e;
			}
		});		
	},
	_renderChart : function(obj) {
		hui.get('chart').innerHTML = '';
		var chart = new hui.ui.Chart.create({parent:'chart'});
		var entries = {};
		for (var i=0; i < obj.length; i++) {
			entries[obj[i].date] = obj[i].value;
		};
		chart.setData({
			sets : [
				{type:'line', entries:entries} //w {'January':10,'February':20,'March':15,'April':45,'May':56}
			]
		});
		
		chart.render();
	},
	_renderRecord : function(data) {
		var record = hui.get('record');
		var body = hui.get.firstByTag(record,'tbody');
		hui.dom.clear(body)
		for (var i=0; i < data.length; i++) {
			var row = hui.build('tr',{parent:body});
			hui.build('th',{parent:row,text:data[i].date});
			hui.build('td',{parent:row,text:data[i].value});
		};
	},
	_renderInfo : function(info) {
		info = info || {};
		var box = hui.get('info');
		var address = info.street;
		if (info.zipcode) {
			address+=', '+info.zipcode;
		}
		if (info.city) {
			address+=', '+info.city;
		}
		hui.dom.setText(hui.get('infoNumber'),info.number);
		hui.dom.setText(hui.get('infoAddress'),address);
		hui.dom.setText(hui.get('infoMail'),info.email);
		hui.dom.setText(hui.get('infoPhone'),info.phone);
		address+=', Danmark'
		var map = hui.get('map');
		var loc = 'http://maps.googleapis.com/maps/api/staticmap?center='+address.replace(/[\W]+/g,'%20')+'&zoom=16&size=640x300&sensor=false';
		map.style.backgroundImage = 'url("'+loc+'")';
		//hui.build('img',{src:loc,parent:map})
	}
})