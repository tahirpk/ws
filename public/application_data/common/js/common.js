function populateForm(id){
	document.getElementById('frm_'+id).style.display = '';
	var counter = 0;
	for(counter=0;counter<ids.length;counter++){
		if(ids[counter] == id){continue;}
		document.getElementById('frm_'+ids[counter]).style.display = 'none';
	}
}

function deleteRow(url,msg){
	if(window.confirm(msg)){
		window.location = url;
	}
	return false;
}

function createTD(tdclass,tdalign) {
	var td = document.createElement("TD");
	td.className = tdclass;
	td.align = tdalign;
	return td;
}

function createINPUT(iid,iname,itype,ivalue,iclass) {
	var input = document.createElement("INPUT");
	input.id = iid;
	input.name = iname;
	input.type = itype;
	input.value = ivalue;
	input.className = iclass;
	return input;
}

function delRow(tid,row){
	var i = document.getElementById(row).rowIndex;
	document.getElementById(tid).deleteRow(i);
	var rowCount = $('#'+tid+' tr').length;
	rowCount = rowCount-1;
	if(rowCount == 0) {
		$('#'+tid).hide();
		$('#add-more').hide();
		$('#sub_button').hide();
		$('#error').hide();
	}
}

function getDropDownYear(rowid,startyear,name,arryname) {
	var sel = document.createElement('select');
	sel.className = 'profile-dd-year';
	sel.name = arryname+'['+rowid+']['+name+']';
	var d = new Date();
	var curr_year = d.getFullYear();
	var a = 0;
	for(var i =startyear; i<=curr_year;i++){
		sel.options[a] = new Option(i,i);
		a++;
	}
	return sel;
}

function getDropDownYearNext(rowid,name,arryname) {
	var sel = document.createElement('select');
	sel.className = 'profile-dd-year';
	sel.name = arryname+'['+rowid+']['+name+']';
	var d = new Date();
	var curr_year = d.getFullYear();
	var a = 0;
	for(var i =curr_year; i<=curr_year+10;i++){
		sel.options[a] = new Option(i,i);
		a++;
	}
	return sel;
}


function getDropDown(rowid,counter,name,arryname) {
	var sel = document.createElement('select');
	sel.className = 'profile-dd-date';
	sel.name = arryname+'['+rowid+']['+name+']';
	for(var i =1; i<=counter;i++){
		sel.options[i-1] = new Option(i,i);
	}
	return sel;
}

function getField(rowid,name,arryname) {
	var sel = document.createElement('input');
	sel.className = '';
	sel.name = arryname+'['+rowid+']['+name+']';
	return sel;
}

function addslashes(str) {
	str=str.replace(/\\/g,'\\\\');
	str=str.replace(/\'/g,'\\\'');
	str=str.replace(/\"/g,'\\"');
	str=str.replace(/\0/g,'\\0');
	return str;
}
function stripslashes(str) {
	str=str.replace(/\\'/g,'\'');
	str=str.replace(/\\"/g,'"');
	str=str.replace(/\\0/g,'\0');
	str=str.replace(/\\\\/g,'\\');
	return str;
}
