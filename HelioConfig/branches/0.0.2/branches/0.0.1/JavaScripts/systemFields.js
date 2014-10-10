$(document).ready(function() {
	var oTable = $("#systemFieldsTable").dataTable({
		"aoColumns":[
		     {sClass:"deleteButtonCell"},
		     {sClass:"globalUpdateCell"},
		     {sClass:"fieldNameCell"},
		     {sClass:"fieldDescriptionCell"},
		     {sClass:"fieldTypeCell"},
		     {sClass:"isRequiredCell"},
		     {sClass:"defaultValueCell"},
		     {sClass:"isEditableCell"}]
	});
	
	$("#systemFieldsTable").on("change",".required",function(){
		var hidden = $(this).siblings(".hiddenRequired");
	    hidden.val( $(this).is(':checked') ? '1' : '0' );
	    var currentRow = $(this).closest("tr");
		var cellsArray = currentRow.children("td");
		var currentIndex = $(this).index();
		if (validate(cellsArray,hidden.val(),currentIndex)){
			var fieldID = currentRow.find(".fieldID").val();
			var fieldName = currentRow.find(".fieldNameCell").text();
			var fieldDescription = currentRow.find(".fieldDescriptionCell").text();
			var fieldTypeName = currentRow.find(".fieldTypeCell").text();
			var isRequired = currentRow.find(".hiddenRequired").val();
			var defaultValue = currentRow.find(".defaultValueCell").text();
			var isEditable = currentRow.find(".hiddenEditable").val();
			var systemID = $("#systemIDField").val()
			var dataObject = {"fieldID": fieldID,"fieldName":fieldName,"fieldDescription":fieldDescription,"fieldTypeName":fieldTypeName,"isRequired":isRequired,"defaultValue":defaultValue,"isEditable":isEditable}
			if (confirm("Do you really want to make the following update?: "+JSON.stringify(dataObject,null,4))){	
				dataObject.updateField = "1";
				dataObject.systemID = systemID;
				$.ajax({
					url:"systemFields.php",
					data: dataObject,
					success:
						function(data){
							alert("Update successful!");
						},
					error:
						function(jqXHR, textStatus, errorThrown) {
							alert("Update failed! "+errorThrown);
						}
				});
			}else{
				location.reload();
			}
		}
	});
	
	$("#systemFieldsTable").on("change",".editable",function(){
		var hidden = $(this).siblings(".hiddenEditable");
	    hidden.val( $(this).is(':checked') ? '1' : '0' );
	    var currentRow = $(this).closest("tr");
		var cellsArray = currentRow.children("td");
		var currentIndex = $(this).index();
		if (validate(cellsArray,hidden.val(),currentIndex)){
			var fieldID = currentRow.find(".fieldID").val();
			var fieldName = currentRow.find(".fieldNameCell").text();
			var fieldDescription = currentRow.find(".fieldDescriptionCell").text();
			var fieldTypeName = currentRow.find(".fieldTypeCell").text();
			var isRequired = currentRow.find(".hiddenRequired").val();
			var defaultValue = currentRow.find(".defaultValueCell").text();
			var isEditable = currentRow.find(".hiddenEditable").val();
			var systemID = $("#systemIDField").val()
			var dataObject = {"fieldID": fieldID,"fieldName":fieldName,"fieldDescription":fieldDescription,"fieldTypeName":fieldTypeName,"isRequired":isRequired,"defaultValue":defaultValue,"isEditable":isEditable}
			if (confirm("Do you really want to make the following update?: "+JSON.stringify(dataObject,null,4))){	
				dataObject.updateField = "1";
				dataObject.systemID = systemID;
				$.ajax({
					url:"systemFields.php",
					data: dataObject,
					success:
						function(data){
							alert("Update successful!");
						},
					error:
						function(jqXHR, textStatus, errorThrown) {
							alert("Update failed! "+errorThrown);
						}
				});
			}else{
				location.reload();
			}
		}
	});
	
	oTable.find('td.defaultValueCell').editable(editCallback,{
		submit:"OK",
		tooltip: "Click to edit...",
		indicator: "Saving...",
		event: "dblclick"
		
	});
		
	$("#addField").click(function(){
		var newData = $("#systemFieldsTable").dataTable().fnAddData([
		    "<button class='deleteButton'>Delete Field</button>",
		    "<button class='globalChangeButton'>Global Change</button>",
			"(Insert Field Name)",
			"(Insert Field Description)",
			"(Insert Field Type)",
			"<input type='checkbox' name='isRequiredCheckbox[]'><input class='hiddenRequired' type='hidden' name='isRequired[]' value='0'>",
			"(Insert Default Value)",
			"<input class='editable' type='checkbox' name='isEditableCheckbox[]' checked>" +
			"<input class='hiddenEditable' type='hidden' name='isEditable[]' value='1'>" +
			"<input class='fieldID' type='hidden' name='fieldID' value='0'>"]
		);
		var oSettings=oTable.fnSettings();
		$('td.fieldNameCell,td.fieldDescriptionCell,td.fieldTypeCell,td.defaultValueCell',oSettings.aoData[newData[0]].nTr).editable(editCallback,{
			submit:"OK",
			tooltip: "Click to edit...",
			indicator: "Saving...",
			event: "dblclick"
			
		});
    });
	
	
	$("#systemFieldsTable").on("click",".deleteButton",function(){
		if (confirm("Are you sure you want to delete this field?")){
			var row = $(this).closest("tr");
        	var fieldID = row.find(".fieldID").val();
        	var systemID = $("#systemIDField").val();
        	if (fieldID!=0){
	        	var dataObject = {"systemID":systemID,"fieldID":fieldID,"deleteField":true}
	        	$.ajax({
					url:"systemFields.php",
					data: dataObject,
					success:
						function(data){
							alert("Success! Field has been deleted"+data );
				        	$("#systemFieldsTable").dataTable().fnDeleteRow(row[0]);
						},
					error:
						function(jqXHR, textStatus, errorThrown) {
							alert("Error! "+errorThrown+" Field has NOT been deleted");
						}
				});
        	}else if (fieldID==0){
	        	$("#systemFieldsTable").dataTable().fnDeleteRow(row[0]);
        	}
		}
	});
	
	$("#systemFieldsTable").on("click",".globalChangeButton",function(){
		var fieldIDArray = $(this).closest("tr").find(".fieldID");
		var fieldNameArray = $(this).closest("tr").find(".fieldNameCell");
		var systemID = $("#systemIDField").val();
		var newValue = prompt("WARNING! This will updated the value of "+fieldNameArray.html()+" for ALL configurations in this system. \n\n\n New value for "+fieldNameArray.html()+":");
		if (newValue!=null){
			var dataObject = {"systemID":systemID,"fieldID": fieldIDArray.val(),"newValue":newValue,"globalChange":"1"};
			$.ajax({
				url:"systemFields.php",
				data: dataObject,
				success:
					function(data){
						alert("Global update successful!");
					},
				error:
					function(jqXHR, textStatus, errorThrown) {
						alert("Failed! "+errorThrown);
					}
			});
		}
		
	});
});

function editCallback(value,settings){
	var currentRow = $(this).closest("tr");
	var cellsArray = currentRow.children("td");
	var currentIndex = $(this).index();
	if (validate(cellsArray,value,currentIndex)){
		// get the cell values for this field, taking 'value' if the cell is currently being modified
		var fieldID = currentRow.find(".fieldID").val();
		var fieldName = $(this).hasClass("fieldNameCell") ? value : currentRow.find(".fieldNameCell").text();
		var fieldDescription = $(this).hasClass("fieldDescriptionCell") ? value : currentRow.find(".fieldDescriptionCell").text();
		var fieldTypeName = $(this).hasClass("fieldTypeCell") ? value : currentRow.find(".fieldTypeCell").text();
		var isRequired = currentRow.find(".hiddenRequired").val();
		var defaultValue = $(this).hasClass("defaultValueCell") ? value : currentRow.find(".defaultValueCell").text();
		var isEditable = currentRow.find(".hiddenEditable").val();
		var systemID = $("#systemIDField").val()
		var dataObject = {"fieldID": fieldID,"fieldName":fieldName,"fieldDescription":fieldDescription,"fieldTypeName":fieldTypeName,"isRequired":isRequired,"defaultValue":defaultValue,"isEditable":isEditable}
		if (confirm("Do you really want to make the following update?: "+JSON.stringify(dataObject,null,4))){	
			dataObject.updateField = "1";
			dataObject.systemID = systemID;
			$.ajax({
				url:"systemFields.php",
				data: dataObject,
				success:
					function(data){
						alert("Update successful!");
					},
				error:
					function(jqXHR, textStatus, errorThrown) {
						alert("Update failed! "+errorThrown);
					}
			});
		}
	}
	return value;	
}

function validate(cellsArray,currentValue,currentIndex){
	var isValid=true;
	$.each(cellsArray,function(index,value){
		if ($(value).hasClass("fieldNameCell")){
			if (index==currentIndex){
				if (currentValue=="(Insert Field Name)" || currentValue==""){
					isValid=false;
					return false;
				}
			}else{
				if (value.innerText=="(Insert Field Name)" || value.innerText==""){
					isValid=false;
					return false;
				}
			}
		}
		if ($(value).hasClass("fieldDescriptionCell")){
			if (index==currentIndex){
				if (currentValue=="(Insert Field Description)" || currentValue==""){
					isValid=false;
					return false;
				}
			}else{
				if (value.innerText=="(Insert Field Description)" || value.innerText==""){
					isValid=false;
					return false;
				}
			}
		}
		if ($(value).hasClass("fieldTypeCell")){
			if (index==currentIndex){
				if (currentValue=="(Insert Field Type)" || currentValue==""){
					isValid=false;
					return false;
				}
			}else{
				if (value.innerText=="(Insert Field Type)" || value.innerText==""){
					isValid=false;
					return false;
				}
			}
		}
		if ($(value).hasClass("defaultValueCell")){
			if (index==currentIndex){
				if (currentValue=="(Insert Default Value)" || currentValue==""){
					isValid=false;
					return false;
				}
			}else{
				if (value.innerText=="(Insert Default Value)" || value.innerText==""){
					isValid=false;
					return false;
				}
			}
		}
	});
	return isValid;
}