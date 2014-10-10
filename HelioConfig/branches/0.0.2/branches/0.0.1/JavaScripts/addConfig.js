$(document).ready(function() {
	
	var oTable = $("#configTable").dataTable({
		"aoColumns":[
				     {sClass:"deleteButtonCell"},
				     {sClass:"fieldNameCell"},
				     {sClass:"fieldDescriptionCell"},
				     {sClass:"fieldTypeCell"},
				     {sClass:"valueCell"},
				     {sClass:"fieldIDCell"}]
	});
	
	$("#addField").click(function(){
		var systemID = $("#systemID").val();
		var existingFieldIDs = new Array();
		$("tbody>tr").each(function(){
			var fieldID = $(this).find(".fieldID").val();
			existingFieldIDs.push(fieldID);
		});
		var dataObject = {"existingFields":existingFieldIDs,"systemID":systemID,"getOptionalFields":1};
		$.ajax({
			url:"optionalFields.php",
			data: dataObject,
			//dataType:"jsonp",
			success:
				function(data){
					var optionalFieldsString = "<option></option>";
					var jsonData = JSON.parse(data);
					$(jsonData).each(function(index,value){
						optionalFieldsString = optionalFieldsString+"<option value="+value.fieldID+">"+value.fieldName+"</option>";
					})
					var chooseOptionalField = oTable.fnAddData([
					    "<button type='button' class='deleteButton'>Delete</button>",
					    "<select class='optionalFieldDropdown' name='optionalFieldDropdown'>" +
					    optionalFieldsString +
					    "</select>","","","",""                                        
					]);
				},
			error:
				function(jqXHR, textStatus, errorThrown) {
					alert("Update failed! "+errorThrown);
				}
		});
		
		
    });

	oTable.on("click",".deleteButton",function(){
		var row = $(this).closest("tr");
		oTable.fnDeleteRow(row[0]);
	});
	
	oTable.on("change",".optionalFieldDropdown",function(){
		var row = $(this).closest("tr");
		var fieldID = $(this).val();
		var dataObject = {"fieldID":fieldID,"getField":1};
		$.ajax({
			url:"optionalFields.php",
			data: dataObject,
			//dataType:"jsonp",
			success:
				function(data){
					var jsonData = JSON.parse(data);
					oTable.fnDeleteRow(row[0]);
					oTable.fnAddData(["<input type='hidden' name='fieldID[]' class='fieldID' value='"+fieldID+"'></input>" +
					                  "<button type='button' class='deleteButton'>Delete</button>" +
					                  "<input type='hidden' name='isRequired[]' value='"+jsonData[4]+"'></input>" +
					                  "<input type='hidden' name='isEditable[]' value='"+jsonData[5]+"'></input>",
					                  "<input type='hidden' name='fieldName[]' value='"+jsonData[0]+"'>"+jsonData[0],
					                  "<input type='hidden' name='fieldDescription[]' value='"+jsonData[1]+"'>"+jsonData[1],
					                  "<input type='hidden' name='fieldType[]' value='"+jsonData[2]+"'>"+jsonData[2],
					                  "<input type='text' name='value[]' value='"+jsonData[3]+"'>",""
					]);
				},
			error:
				function(jqXHR, textStatus, errorThrown) {
					alert("Update failed! "+errorThrown);
				}
		});
	});
	
	
	
	
});