hui.ui.listen({
	
	number : null,
	data : null,
	
	$ready : function() {
		this.numberInput = new hui.ui.Input({element:'number'});
		/*this.numberInput.listen({
			$valueChanged : function(value) {
				this._fetch(value);
			}.bind(this)
		})*/
		
		this.numberInput.setValue();
		
		this.dateInput = new hui.ui.Input({element:'date',validator:this._validateDate});
		this.dateInput.setValue(new Date().dateFormat('d-m-Y'));
		this.valueInput = new hui.ui.Input({element:'value',validator:this._validateNumber});
		this.submit = new hui.ui.Input({element:'submit',name:'submit'});
		
		hui.listen(hui.get('edit'),'click',this._editInfo.bind(this));
		
		new hui.ui.Input({element:'sendNumber',name:'sendNumber'})

		this.numberInput.focus();
		//this.$click$sendNumber();
	},
	
	
	$click$submit : function() {
		var data = {
			number : this.number,
			date : this.dateInput.value,
			value : this.valueInput.value
		}
		var valid = true;
		if (hui.isBlank(data.date)) {
			this.dateInput.setError('Skal udfyldes');
			this.dateInput.focus()
			valid = false;
		} else {
			this.dateInput.setError();
		}
		if (hui.isBlank(data.value)) {
			this.valueInput.setError('Skal udfyldes');
			if (valid) {
				this.valueInput.focus()
			}
			valid = false;
		} else {
			this.valueInput.setError();
		}
		if (!valid) {
			return;
		}
		hui.ui.showMessage({text:'Gemmer værdi...',busy:true})
		hui.ui.request({
			url : op.context+'services/waterusage/register.php',
			parameters : data,
			$success : function() {
				this._fetch(this.number,function() {
					hui.ui.showMessage({text:'Den nye værdi er gemt',icon:'common/success',duration:2000})
				});
			}.bind(this),
			$failure : function() {
				hui.ui.showMessage({text:'Det lykkedes desværre ikke at gemme værdien',icon:'common/warning',duration:3000})
			}
		})
	},
	
	$click$sendNumber : function() {
		this._fetch(this.numberInput.getValue());
	},
	
	_fetch : function(number,callback) {
		this.number = number;
		hui.ui.request({
			url : op.context+'services/waterusage/load.php',
			message : {start:'Henter information...'},
			parameters : {number:number},
			$object : function(obj) {
				hui.log(obj)
				this.data = obj;
				this._renderRecord(obj.usage);
				this._renderChart(obj.usage);
				this._renderInfo(obj.info);
				hui.get('result').style.display='block';
				hui.ui.reLayout();
				this.valueInput.focus();
				if (callback) {
					callback();
				}
			}.bind(this),
			$failure : function() {
				hui.get('result').style.display='none';
				hui.ui.showMessage({text:'Målernummeret kunne ikke findes',icon:'common/warning',duration:3000})
				this.numberInput.focus();
			}.bind(this),
			$exception : function(e) {
				throw e;
			}
		});		
	},
	
	_editInfo : function() {
		if (!this.infoBox) {
			var box = this.infoBox = hui.ui.Box.create({title:'Kontaktinformation',absolute:true,width:300,padding:10,closable:true,modal:true});
			var form = this.infoForm = hui.ui.Formula.create({name:'infoForm'});
			var group = form.buildGroup({above:true},[
				{type:'TextField',label:'E-mail-adresse',options:{key:'email'}},
				{type:'TextField',label:'Telefon',options:{key:'phone'}}
			])
			var buttons = group.createButtons();
			buttons.add(hui.ui.Button.create({text:'Annuller',name:'cancelInfo'}));
			buttons.add(hui.ui.Button.create({text:'Gem',highlighted:true,submit:true}));
			box.add(form)
			box.addToDocument();
		}
		this.infoForm.setValues(this.data.info);
		this.infoBox.show();
		this.infoForm.focus();
	},
	$click$cancelInfo : function() {
		this.infoBox.hide();
	},
	$submit$infoForm : function(form) {
		var values = form.getValues(),
			box = this.infoBox;
		values.number = this.number;
		hui.ui.request({
			url : op.context+'services/waterusage/update_info.php',
			parameters : values,
			message : {start:'Gemmer information...',success:'De nye kontaktoplysninger er gemt'},
			$success : function() {
				box.hide();
				this._fetch(this.number);
			}.bind(this),
			$failure : function() {
				hui.ui.showMessage({text:'Det lykkedes desværre ikke at gemme informationen',icon:'common/warning',duration:3000});
				box.hide();
			}
		})
	},
	
	////// Rendering //////
	
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
			hui.build('td',{parent:row,text:data[i].perweek});
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
	},
	
	
	
	
	////// Validators //////
	
	_validateDate : {
		_dateFormats : ['d-m-Y','d/m-Y','d/m/Y','d-m-Y H:i:s','d/m-Y H:i:s','d/m/Y H:i:s','d-m-Y H:i','d/m-Y H:i','d/m/Y H:i','d-m-Y H','d/m-Y H','d/m/Y H','d-m','d/m','d','Y','m-d-Y','m-d','m/d'],
		validate : function(value) {
			for (var i=0; i < this._dateFormats.length; i++) {
				var fmt = this._dateFormats[i];
				var parsed = Date.parseDate(value,fmt);
				
				if (parsed) {
					return {valid:true,value:parsed.dateFormat('d-m-Y')};
				}
			};
			return {valid:false,value:''};
		}
	},
	_validateNumber : {
		validate : function(value) {
			var valid = false;
			if (value) {
				var pattern = /[0-9]+/g;
				var matches = value.match(pattern);
				if (matches) {
					value = matches.join('');
				} else {
					value = '';
					valid = false;
				}			
			}
			return {valid:true,value:value}
		}
	}
})