var sortedOn = -1;
	
function sortList(column,dataType) {
    if (!(document.getElementById && document.cloneNode)) {
    //alert('Doesn\'t work with this browser!!');
    return;
    }
	var table = document.getElementById('results');
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
	if (column == sortedOn) {
		rowIndex.reverse();
	}
	else {
		sortedOn = column;
		if (dataType == 'number') {
			rowIndex.sort(RowCompareNumbers);
		}
		else if (dataType == 'decimal') {
			rowIndex.sort(RowCompareDecimal);
		}
		else {
			rowIndex.sort(RowCompare);
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
				
	// change class of cells
	var newRows=table.childNodes[1].childNodes;
	var flavor=2;
	for (var i=0; i<newRows.length; i++) {
		newRowCells=newRows[i].childNodes;
		for (var x=0; x<newRowCells.length; x++) {
			var cell=newRowCells[x];
			var style = (column==x) ? 'Hilited' : 'Standard';
			if (cell.className.indexOf('Standard1')!=-1) cell.className=cell.className.replace(/Standard1/i,style+flavor);
			if (cell.className.indexOf('Standard2')!=-1) cell.className=cell.className.replace(/Standard2/i,style+flavor);
			if (cell.className.indexOf('Hilited1')!=-1) cell.className=cell.className.replace(/Hilited1/i,style+flavor);
			if (cell.className.indexOf('Hilited2')!=-1) cell.className=cell.className.replace(/Hilited2/i,style+flavor);
		}
		(flavor==1) ? flavor=2 : flavor=1;
	}
	
	// change class of headers + remove direction
	headers=table.childNodes[0].childNodes[0].childNodes;
	var style = '';
	for (var i=0; i<headers.length; i++) {
		style = (column==i) ? 'Hilited' : 'Standard';
		header=headers[i];
		var bg=header.style.backgroundImage;
		if (bg.indexOf('Standard')!=-1) {
			bg=bg.replace(/Standard/i,style);
		}
		else {
			bg=bg.replace(/Hilited/i,style);
		}
		header.style.backgroundImage=bg;
		headerAttr=header.getAttributeNode('class');
		header.className='ListHeader'+style;
		var imgs = header.getElementsByTagName('img');
		if (imgs.length>0) {
			img=imgs[0];
			if (img.className=='ListHeaderDirection') {
				img.parentNode.removeChild(img);
			}
		}
		headerLinkAttr=header.getElementsByTagName('a')[0].className='ListHeader'+style;
	}
}
		
function RowCompare(a, b) {
	
	var aVal = a.value;
	var bVal = b.value;
	return (aVal == bVal ? 0 : (aVal > bVal ? 1 : -1));
}

function RowCompareNumbers(a, b) {
	var test='0123456789.,+-';
	var aVal = parseInt(getValidCharacters(a.value,test));
	var bVal = parseInt(getValidCharacters(b.value,test));
	if (isNaN(aVal)) aVal=0;
	if (isNaN(bVal)) bVal=0;
	return (aVal - bVal);
}

function RowCompareDecimal(a, b) {
	var test='0123456789.,+-';
	var aVal = parseFloat(getValidCharacters(a.value,test));
	var bVal = parseFloat(getValidCharacters(b.value,test));
	if (isNaN(aVal)) aVal=0;
	if (isNaN(bVal)) bVal=0;
	return (aVal - bVal);
}
		
function getValidCharacters(input,chars) {
	output='';
	for (i=0;i<input.length;i++) {
		if (chars.indexOf(input.charAt(i))!=-1) {
			output=output+input.charAt(i);
		}
	}
	return output;
}
