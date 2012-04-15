//
// Copyright (c) 2006 by Conor O'Mahony.
// For enquiries, please email GubuSoft@GubuSoft.com.
// Please keep all copyright notices below.
// Original author of TreeView script is Marcelino Martins.
//
// This document includes the TreeView script.
// The TreeView script can be found at http://www.TreeView.net.
// The script is Copyright (c) 2006 by Conor O'Mahony.
//
// This configuration file is used to demonstrate how to add checkboxes to your tree.
// If your site will not display checkboxes, pick a different configuration file as 
// the example to follow and adapt.  You can find general instructions for this file 
// at www.treeview.net.  Intructions on how to add checkboxes to a tree are provided 
// in this file.
//

USETEXTLINKS = 1  
STARTALLOPEN = 0
HIGHLIGHT = 0
PRESERVESTATE = 1

// NOTE:  If you are going to set USEICONS = 1, then you will want to edit the gif 
// files and remove the white space on the right
USEICONS = 0

// In this case we want the whole tree to be built, even those branches that are 
// closed. The reason is that otherwise some form elements might not be built at 
// all before the user presses the "Get Values" button.
BUILDALL = 1


// Some auxiliary functions for the contruction of the tree follow.  You will 
// certainly want to change these functions for your own purposes.
//
// These functions are directly related with the additional JavaScript in the 
// page holding the tree (demoCheckboxLeftFrame.html), where the form handling 
// code resides.

// If you want to add checkboxes to the folder, you will have to create a function 
// similar to this one and then call it in the tree construction section below.
function generateCheckBox(parentfolderObject, itemLabel, checkBoxDOMId) {
  var newObj;

  // For an explanation of insDoc and gLnk, read the online instructions.
  // They are the basis upon which TreeView is based.
  newObj = insDoc(parentfolderObject, gLnk("R", itemLabel, "javascript:parent.op()"))

  // The trick to show checkboxes in a tree that was made to display links is to 
  // use the prependHTML. There are general instructions about this member
  // in the online documentation.
  newObj.prependHTML = "<td valign=middle><input type=checkbox id="+checkBoxDOMId+"></td>"
}



/*// The following code constructs the tree.
foldersTree = gFld("modules:", "index.html")
foldersTree.treeID = "checkboxTree"
aux1 = insFld(foldersTree, gFld("<input type= 'checkbox' id='aux1' onclick='select_all(2,\"aux1_\")'/>Day of the week", ""))
for(a=0;a<2;a++){
	aux2 = insFld(aux1, gFld("<input type= 'checkbox' id='aux1_"+a+"'/>Day of the week 1", ""))

}*/

function select_all(count,parent){
	if(document.getElementById(parent).checked==true){
		for(a=0;a<count;a++){
			document.getElementById(parent+a).checked=true;		 
		}		
	}else{
		for(a=0;a<count;a++){
			document.getElementById(parent+a).checked=false;		 
		}	
   }
	
}

function un_select(count,parent){
		if(document.getElementById(parent).checked==true ){
			document.getElementById(parent).checked=false;
		}
		allchecked = true;
		for(a=0;a<count;a++){
			if(document.getElementById(parent+a).checked==false){
			allchecked = false;
			}
		}
		if(allchecked==true){document.getElementById(parent).checked=true  }
}
