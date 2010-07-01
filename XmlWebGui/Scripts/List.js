In2iGui.List = function (id,sortable,selectable) {
	this.id = id;
	this.outerTable=document.getElementById("list_outer_"+id);
	this.sortable=sortable;
	this.selectable=selectable!='';
	this.sortedColumn = -1;
}

In2iGui.List.prototype.toggleCheckboxes = function() {
	var inputs = this.outerTable.getElementsByTagName('input');
	for(i=0;i<inputs.length;i++) {
		if(inputs[i].type=='checkbox') {
			inputs[i].checked=(!inputs[i].checked);
		}
	}
};

In2iGui.List.prototype.sortColumn = function(column,dataType) {
	if (!(document.getElementById && document.cloneNode)) {
		return;
	}
	if (this.selectable) column++;
	var table = document.getElementById('list_'+this.id+'_content');
	var tbody = table.childNodes[1];
	var rows = tbody.childNodes;
	
	// Create index
	var rowIndex = new Array();
	for (var i=0, length=rows.length; i<length; i++) {
		rowIndex[i] = new Object;
		rowIndex[i].oldIndex = i;
		rowIndex[i].value=rows[i].cloneNode(true).childNodes[column].getAttributeNode('xwgindex').value;
	}

	// Sort index
	if (column == this.sortedColumn) {
		rowIndex.reverse();
	}
	else {
		this.sortedColumn = column;
		if (dataType == 'number') {
			rowIndex.sort(this.rowCompareNumbers);
		}
		else if (dataType == 'decimal') {
			rowIndex.sort(this.rowCompareDecimal);
		}
		else {
			rowIndex.sort(this.rowCompare);
		}
	}
	
	// Insert new rows
	var newTbody = document.createElement('tbody');
	var theRow;
	for (var i=0, length=rowIndex.length; i<length; i++) {
		theRow = rows[rowIndex[i].oldIndex].cloneNode(true);
		newTbody.appendChild(theRow);
	}
	table.replaceChild(newTbody,tbody);
	
	this.rebuildStyle(table);
}

In2iGui.List.prototype.rebuildStyle = function(table) {
	// change class of cells
	var newRows=table.childNodes[1].childNodes;
	var flavor=2;
	for (var i=0; i<newRows.length; i++) {
		var row = newRows[i];
		if (row.className.indexOf('1')!=-1) row.className=row.className.replace(/1/i,flavor);
		if (row.className.indexOf('2')!=-1) row.className=row.className.replace(/2/i,flavor);
		newRowCells=newRows[i].childNodes;
		for (var x=0; x<newRowCells.length; x++) {
			var cell=newRowCells[x];
			var style = (this.sortedColumn==x) ? 'Hilited' : 'Standard';
			if (cell.className.indexOf('Standard')!=-1) cell.className=cell.className.replace(/Standard/i,style);
			if (cell.className.indexOf('Hilited')!=-1) cell.className=cell.className.replace(/Hilited/i,style);
		}
		(flavor==1) ? flavor=2 : flavor=1;
	}
	
	// change class of headers + remove direction
	headers=table.childNodes[0].childNodes[0].childNodes;
	var style = '';
	for (var i=0; i<headers.length; i++) {
		style = (this.sortedColumn==i) ? 'Hilited' : 'Standard';
		header=headers[i];
		headerAttr=header.getAttributeNode('class');
		header.className='ListHeader'+style;
		var imgs = header.getElementsByTagName('img');
		if (imgs.length>0) {
			img=imgs[0];
			if (img.className=='ListHeaderDirection') {
				img.parentNode.removeChild(img);
			}
		}
		var link = header.getElementsByTagName('a')[0];
		if (link) {
			link.className='ListHeader ListHeader'+style;
		}
	}
}
		
In2iGui.List.prototype.rowCompare = function(a,b) {
	var aVal = a.value;
	var bVal = b.value;
	return (aVal == bVal ? 0 : (aVal > bVal ? 1 : -1));
}

In2iGui.List.prototype.rowCompareNumbers = function(a, b) {
	var test='0123456789.,+-';
	var aVal = parseInt(In2iGui.List.prototype.getValidCharacters(a.value,test));
	var bVal = parseInt(In2iGui.List.prototype.getValidCharacters(b.value,test));
	if (isNaN(aVal)) aVal=0;
	if (isNaN(bVal)) bVal=0;
	return (aVal - bVal);
}

In2iGui.List.prototype.rowCompareDecimal = function(a, b) {
	var test='0123456789.,+-';
	var aVal = parseFloat(this.getValidCharacters(a.value,test));
	var bVal = parseFloat(this.getValidCharacters(b.value,test));
	if (isNaN(aVal)) aVal=0;
	if (isNaN(bVal)) bVal=0;
	return (aVal - bVal);
}

In2iGui.List.prototype.parseNumber = function(value) {
	var test='0123456789.,+-';
	var out = parseFloat(this.getValidCharacters(value,test));
	if (isNaN(out)) out=0;
	return out;
}

In2iGui.List.prototype.getValidCharacters = function(input,chars) {
	output='';
	for (i=0;i<input.length;i++) {
		if (chars.indexOf(input.charAt(i))!=-1) {
			output=output+input.charAt(i);
		}
	}
	return output;
}
