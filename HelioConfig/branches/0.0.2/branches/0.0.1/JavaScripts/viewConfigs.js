$(document).ready(function() {
	var oTable = $("#configTable").dataTable({
		"aoColumns":[
		     {sClass:"deleteButtonCell"},
		     {sClass:"fieldNameCell"},
		     {sClass:"fieldDescriptionCell"},
		     {sClass:"fieldTypeCell"},
		     {sClass:"valueCell"},
		     {sClass:"checkErrorCell"}]
	});
	
	oTable.find('tr.editable').find('td.valueCell').editable(editCallback,{
		submit:"OK",
		tooltip: "Click to edit...",
		indicator: "Saving...",
		event: "dblclick"
		
	});
	
	function editCallback(value,settings){
		var row =  $(this).closest("tr");
		var valueFieldID = row.find(".valueFieldID").val();
		var dataObject = {"valueFieldID":valueFieldID,"newValue":value,"updateValue":1}
		$.ajax({
			url:"viewConfigs.php",
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
		return value;	
	}
});