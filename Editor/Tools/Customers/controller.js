hui.ui.listen({
	mailinglistId : 0,
	groupId : 0,
	personId : 0,
	dragDrop : [
		{drag:'person',drop:'persongroup'}
	],
	
	$ready : function() {
		sendEmail.setEnabled(false);
		var person = hui.location.getParameter('person');
		if (person) {
			this.loadPerson(person);
		}
		hui.ui.tellContainers('changeSelection','tool:Customers');
	},
	
	$resolveImageUrl : function(img,width,height) {
		return '../../../services/images/?id='+img.id+'&width='+width+'&height='+height+'&format=jpg';
	},
	
	$select$selector : function(obj) {
		list.resetState();
		if (obj.value=='mailinglist') {
			list.setSource(mailinglistListSource);
		} else if (obj.value=='persongroup') {
			list.setSource(groupListSource);
		} else {
			list.setSource(personListSource);
		}
		sendEmail.setEnabled(obj.kind=='mailinglist');
	},
	$valueChanged$search : function() {
		list.resetState();
	},
	$open$selector : function(obj) {
		if (obj.kind=='persongroup') {
			this.loadGroup(obj.value);
		} else if (obj.kind=='mailinglist') {
			this.loadMailinglist(obj.value);
		}
	},
	$open$list : function(obj) {
		if (obj.kind=='mailinglist') {
			this.loadMailinglist(obj.id);
		} else if (obj.kind=='persongroup') {
			this.loadGroup(obj.id);
		} else if (obj.kind=='person') {
			this.loadPerson(obj.id);
		}
	},
	$drop$person$persongroup : function(dragged,target) {
		var data = {
			personId : dragged.id,
			personGroupId : target.value
		}
		hui.ui.request({
			json:{data:data},
			message : {
				start : {en:'Adding to group...', da:'Tilføjer til gruppe...'},
				delay : 300,
				success : {en:'The person has been added to the group',da:'Personen er blevet tilføjet til gruppen'}
			},
			url : 'actions/AddPersonToGroup.php',
			onSuccess : function() {
				personGroupSource.refresh();
				list.refresh();
			}
		});
	},
	
	
	
	
	$click$newPerson : function() {
		this.personId = 0;
		personFormula.reset();
		personEmails.reset();
		personPhones.reset();
		personEditor.show();
		deletePerson.setEnabled(false);
		personFirstname.focus();
	},
	$click$newMailinglist : function() {
		this.mailinglistId = 0;
		mailinglistFormula.reset();
		mailinglistEditor.show();
		deleteMailinglist.setEnabled(false);
		mailinglistTitle.focus();
	},
	$click$newGroup : function() {
		this.groupId = 0;
		groupFormula.reset();
		groupEditor.show();
		deleteGroup.setEnabled(false);
		groupTitle.focus();
	},
	
	////////////////////// Mailing list //////////////////////////

	loadMailinglist : function(id) {
		mailinglistFormula.reset();
		deleteMailinglist.setEnabled(false);
		saveMailinglist.setEnabled(false);
		hui.ui.request({
			json : {data:{id:id}},
			message : {start:{en:'Loading mailing list...',da:'Henter postliste...'},delay:300},
			url : '../../Services/Model/LoadObject.php',
			onJSON : function(data) {
				this.mailinglistId = data.id;
				mailinglistTitle.setValue(data.title);
				mailinglistNote.setValue(data.note);
				mailinglistEditor.show();
				deleteMailinglist.setEnabled(true);
				saveMailinglist.setEnabled(true);
				mailinglistTitle.focus();
			}.bind(this)
		});		
	},
	
	$submit$mailinglistFormula : function() {
		var title = mailinglistTitle.getValue();
		var note = mailinglistNote.getValue();
		if (title.length==0) {
			mailinglistTitle.focus();
			return;
		}
		var data = {id:this.mailinglistId,title:title,note:note};
		mailinglistFormula.reset();
		mailinglistEditor.hide();
		hui.ui.request({
			message : {start:{en:'Saving mailinglist...',da:'Gemmer postliste...'},delay:300},
			json : {data:data},
			url : 'actions/SaveMailinglist.php',
			onSuccess : function() {
				mailinglistSource.refresh();
				list.refresh();
			}
		});
	},
	
	$click$deleteMailinglist : function() {
		mailinglistEditor.hide();
		mailinglistFormula.reset();
		hui.ui.request({
			message : {start:{en:'Deleting mailing list...',da:'Sletter postliste...',delay:300}},
			json : {data:{id:this.mailinglistId}},
			url : '../../Services/Model/DeleteObject.php',
			onSuccess : function() {
				mailinglistSource.refresh();
				list.refresh();				
			}
		});
	},
	
	$click$cancelMailinglist : function() {
		this.mailinglistId = 0;
		mailinglistFormula.reset();
		mailinglistEditor.hide();
	},

	//////////////////////////// Person /////////////////////////
	
	loadPerson : function(id) {
		personFormula.reset();
		deletePerson.setEnabled(false);
		savePerson.setEnabled(false);
		hui.ui.request({
			message : {start:{en:'Loading person...',da:'Henter person...'},delay:300},
			json : {data:{id:id}},
			url : 'data/LoadPerson.php',
			onSuccess : 'loadPerson'
		});
	},

	$submit$personFormula : function() {
		var person = {
			id : this.personId,
			firstname : personFirstname.getValue(),
			middlename : personMiddlename.getValue(),
			surname : personSurname.getValue(),
			note : personNote.getValue(),
			jobtitle : personJobtitle.getValue(),
			nickname : personNickname.getValue(),
			initials : personInitials.getValue(),
			streetname : personStreetname.getValue(),
			zipcode : personZipcode.getValue(),
			city : personCity.getValue(),
			country : personCountry.getValue(),
			webaddress : personWebaddress.getValue(),
			searchable : personSearchable.getValue(),
			sex : personSex.getValue(),
			image_id : (personImage.getObject() ? personImage.getObject().id : null)
		}
		var emails = personEmails.getObjects();
		var phones = personPhones.getObjects();
		var groups = personGroups.getValues();
		var mailinglists = personMailinglists.getValues();
		var data = {person:person,emails:emails,phones:phones,groups:groups,mailinglists:mailinglists};
		personFormula.reset();
		personEditor.hide();
		hui.ui.request({
			json : {data:data},
			message : {start:{en:'Saving person...',da:'Gemmer person...'},delay:300},
			url : 'actions/SavePerson.php',
			onSuccess : function() {
				list.refresh();
			}
		});
	},
	
	$success$loadPerson : function(data) {
		this.personId = data.person.id;
		personFirstname.setValue(data.person.firstname);
		personMiddlename.setValue(data.person.middlename);
		personSurname.setValue(data.person.surname);
		personNote.setValue(data.person.note);
		personNickname.setValue(data.person.nickname);
		personJobtitle.setValue(data.person.jobtitle);
		personInitials.setValue(data.person.initials);
		personStreetname.setValue(data.person.streetname);
		personZipcode.setValue(data.person.zipcode);
		personCity.setValue(data.person.city);
		personCountry.setValue(data.person.country);
		personSearchable.setValue(data.person.searchable);
		personWebaddress.setValue(data.person.webaddress);
		personSex.setValue(data.person.sex);
		personImage.setObject(data.person.imageId>0 ? {id:data.person.imageId} : null);
		personEmails.setObjects(data.emails);
		personPhones.setObjects(data.phones);
		personMailinglists.setValues(data.mailinglists);
		personGroups.setValues(data.groups);
		
		deletePerson.setEnabled(true);
		savePerson.setEnabled(true);
		
		personEditor.show();
		personFirstname.focus();
	},
	$click$deletePerson : function() {
		personEditor.hide();
		personFormula.reset();
		hui.ui.request({
			message : {start:{en:'Deleting person...',da:'Sletter person...'},delay:300},
			json : {data:{id:this.personId}},
			url : '../../Services/Model/DeleteObject.php',
			onSuccess : function() {
				list.refresh();
			}
		});
	},
	$click$cancelPerson : function() {
		this.personId = 0;
		personFormula.reset();
		personEditor.hide();
	},
	

	////////////////////////////// Group ///////////////////////////

	loadGroup : function(id) {
		groupFormula.reset();
		deleteGroup.setEnabled(false);
		saveGroup.setEnabled(false);
		saveGroup.setEnabled(false);
		hui.ui.request({
			json : {data:{id:id}},
			message : {start:{en:'Loading group...',da:'Henter gruppe...'},delay:300},
			url : '../../Services/Model/LoadObject.php',
			onJSON : function(data) {
				this.groupId = data.id;
				groupTitle.setValue(data.title);
				groupNote.setValue(data.note);
				groupEditor.show();
				deleteGroup.setEnabled(true);
				saveGroup.setEnabled(true);
				groupTitle.focus();
			}.bind(this)
		});
	},
	
	$submit$groupFormula : function() {
		var title = groupTitle.getValue();
		var note = groupNote.getValue();
		if (title.length==0) {
			groupTitle.focus();
			return;
		}
		groupEditor.hide();
		groupFormula.reset();
		var data = {id:this.groupId,title:title,note:note};
		hui.ui.request({
			json : {data:data},
			message : {start:{en:'Saving group...',da:'Gemmer gruppe...',delay:300}},
			url : 'actions/SaveGroup.php',
			onSuccess : function() {
				personGroupSource.refresh();
				list.refresh();
				personGroups.refresh();
			}
		});
	},
	
	$click$deleteGroup : function() {
		groupEditor.hide();
		groupFormula.reset();
		hui.ui.request({
			message : {start:{en:'Deleting group...',da:'Sletter gruppe...'},delay:300},
			json : {data:{id:this.groupId}},
			url : '../../Services/Model/DeleteObject.php',
			onSuccess : function() {
				personGroupSource.refresh();
				list.refresh();
			}
		});
	},
	
	
	
	$click$cancelGroup : function() {
		this.groupId = 0;
		groupFormula.reset();
		groupEditor.hide();
	},
	
	
	////////////////////////// Send email ////////////////////////
	
	$click$sendEmail : function() {
		var data = {id:selector.getValue().value};
		hui.ui.request({
			json : {data:data},
			url : 'data/GetMailinglistEmails.php',
			onJSON : function(data) {
				hui.log(data)
				var mails = []
				for (var i=0; i < data.length; i++) {
					mails.push(data[i].address);
				}
				if (mails.length>0) {
					document.location.href='mailto:?bcc='+mails.join(',');
				}
			}
		});
	}
});