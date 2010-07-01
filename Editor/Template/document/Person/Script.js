function showPerson(request) {
	var xml = request.responseXML;
	if (!xml.getElementsByTagName('object').length>0) {
		showNoPerson();
	}
	else {
		// Build properties
		var props='<td valign="top" nowrap="nowrap">';
		// name
		var name = buildName(xml);
		if (name!='') {
			props+='<div class="PersonProperty PersonName">'+name+'</div>';
		}
		// Jobtitle
		var jobtitle = getFirstElementValueByName(xml,'jobtitle');
		if (document.forms.PersonForm.show_jobtitle.value=='true' && jobtitle!='') {
			props+='<div class="PersonProperty">'+jobtitle+'</div>';
		}
		// Sex
		var sex = getFirstElementValueByName(xml,'sex');
		if (document.forms.PersonForm.show_sex.value=='true' && sex!='') {
			props+='<div class="PersonProperty"><span class="PersonLabel">K&oslash;n:</span> '+(sex=='male' ? 'Mand' : 'Kvinde')+'</div>';
		}
		// Address
		var address = buildAddress(xml);
		if (address!='') {
			props+='<div class="PersonProperty PersonAddress">'+address+'</div>';
		}
		// Private phone
		var privatePhone = getFirstElementValueByNameAndAttributeValue(xml,'phone','context','private');
		if (document.forms.PersonForm.show_phoneprivate.value=='true' && privatePhone!='') {
			props+='<div class="PersonProperty"><span class="PersonLabel">Tlf.:</span> '+privatePhone+' <span class="PersonExtra">(privat)</span></div>';
		}
		// Work phone
		var jobPhone = getFirstElementValueByNameAndAttributeValue(xml,'phone','context','job');
		//alert(jobPhone);
		if (document.forms.PersonForm.show_phonejob.value=='true' && jobPhone!='') {
			props+='<div class="PersonProperty"><span class="PersonLabel">Tlf.:</span> '+jobPhone+' <span class="PersonExtra">(arbejde)</span></div>';
		}
		// Private email
		var privateEmail = getFirstElementValueByNameAndAttributeValue(xml,'email','context','private');
		if (document.forms.PersonForm.show_emailprivate.value=='true' && privateEmail!='') {
			props+='<div class="PersonProperty"><span class="PersonLabel">E-mail:</span> <a href="#">'+privateEmail+'</a> <span class="PersonExtra">(privat)</span></div>';
		}
		// Work email
		var workEmail = getFirstElementValueByNameAndAttributeValue(xml,'email','context','job');
		if (document.forms.PersonForm.show_emailjob.value=='true' && workEmail!='') {
			props+='<div class="PersonProperty"><span class="PersonLabel">E-mail:</span> <a href="#">'+workEmail+'</a> <span class="PersonExtra">(arbejde)</span></div>';
		}
		// Webaddress
		var webaddress = getFirstElementValueByName(xml,'webaddress');
		if (document.forms.PersonForm.show_webaddress.value=='true' && webaddress!='') {
			props+='<div class="PersonProperty"><span class="PersonLabel">Web:</span> <a href="#">'+webaddress+'</a></div>';
		}
		props+='</td>';
	
		// Build image
		var imagePreview = '';
		var image = buildImage(xml);
		if (document.forms.PersonForm.show_image.value=='true' && image) {
			imagePreview+='<td valign="top" width="1%">'+image+'</td>';
		}
		var align = document.forms.PersonForm.align.value;
		var pre = '<table class="Person" align="'+align+'" width="1%"><tr>'+imagePreview+props+'</tr></table>';
		document.getElementById('PersonPreview').innerHTML=pre;
	}
}

function showNoPerson() {
	document.getElementById('PersonPreview').innerHTML='<img src="Graphics/PersonNotFound.gif" border="0"/>';
}

/*
 * Return the HTML for the address of a person
*/
function buildAddress(xml) {
	var linje1 = '';
	var linje2 = '';
	var street = getFirstElementValueByName(xml,'streetname');
	if (document.forms.PersonForm.show_streetname.value=='true' && street!='') {
		linje1=street;
	}
	var zipcode = getFirstElementValueByName(xml,'zipcode');
	if (document.forms.PersonForm.show_zipcode.value=='true' && zipcode!='') {
		linje2=appendWordToString(linje2,zipcode,'<br>');
	}
	var city = getFirstElementValueByName(xml,'city');
	if (document.forms.PersonForm.show_city.value=='true' && city!='') {
		linje2=appendWordToString(linje2,city,' ');
	}
	var country = getFirstElementValueByName(xml,'country');
	if (document.forms.PersonForm.show_country.value=='true' && country!='') {
		linje2=appendWordToString(linje2,country,' ');
	}
	if (linje1.length>0 && linje2.length>0) linje1+='<br/>';
	return linje1+linje2;
}

/*
 * Return the full name of a person
*/
function buildName(xml) {
	var firstname = getFirstElementValueByName(xml,'firstname');
	var middlename = getFirstElementValueByName(xml,'middlename');
	var surname = getFirstElementValueByName(xml,'surname');
	var fullname = '';
	if (document.forms.PersonForm.show_firstname.value=='true') {
		fullname=appendWordToString(fullname,firstname,' ');
	}
	if (document.forms.PersonForm.show_middlename.value=='true') {
		fullname=appendWordToString(fullname,middlename,' ');
	}
	if (document.forms.PersonForm.show_surname.value=='true') {
		fullname=appendWordToString(fullname,surname,' ');
	}
	var extra = '';
	// Initials
	var initials = getFirstElementValueByName(xml,'initials');
	if (document.forms.PersonForm.show_initials.value=='true' && initials!='') {
		extra+=initials;
	}
	var nickname = getFirstElementValueByName(xml,'nickname');
	if (document.forms.PersonForm.show_nickname.value=='true' && nickname!='') {
		extra=appendWordToString(extra,nickname,'/');
	}
	if (extra.length>0) {
		if (fullname.length>0) {
			fullname+=' ('+extra+')';
		}
		else {
			fullname=extra;
		}
	}
	return fullname;
}

function buildImage(xml) {
	var image = getFirstElementByName(xml,'image');
	if (image) {
		var filename = getFirstElementValueByName(image,'filename');
		var width = getFirstElementValueByName(image,'width');
		var height = getFirstElementValueByName(image,'height');
		if (width>height) {
			wdth = 128;
			hght = Math.round(height/width*128);
		}
		else {
			wdth = Math.round(width/height*128);
			hght = 128;
		}
		return '<img src="../../../images/128/'+filename+'.png" width="'+wdth+'" height="'+hght+'" class="Person"/>';
	}
	else {
		return null;
	}
}

/*
 * Appends a word to a string if the string is not empty
 * returns the word otherwise
*/
function appendWordToString(str,word,separator) {
	if (str.length>0) {
		return str+separator+word;
	}
	else {
		return word;
	}
}
