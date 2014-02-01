$(document).ready(function() {
	$("#addField").click(function(){
		$("#systemTable").dataTable().fnAddData([
			"<button type='button' class='deleteButton'>Delete</button>",
			"<input type='text' required='required' name='fieldName[]'>",
			"<textarea required='required'  name='fieldDescription[]'></textarea>",
			"<input type='text' class='typeName' required='required' name='fieldType[]'>",
			"<input type='checkbox' name='isRequiredCheckbox[]'><input class='hiddenRequired' type='hidden' name='isRequired[]' value='0'></td>",
			"<input type='text' class='defaultValue' required='required' name='defaultValue[]'>",
			"<input class='editable' type='checkbox' name='isEditableCheckbox[]' checked><input class='hiddenEditable' type='hidden' name='isEditable[]' value='1'><input type='hidden' name='fieldID[]' value='0'>"]
		);
    });
	$("#systemTable").on("click",".deleteButton",function(){
        if (confirm("Are you sure you want to delete this field?")){
        	var row = $(this).closest("tr");
        	$("#systemTable").dataTable().fnDeleteRow(row[0]);
        }		
		event.preventDefault();
	});
	$("#systemTable").on("change",".required",function(){
		var hidden = $(this).siblings(".hiddenRequired");
	    hidden.val( $(this).is(':checked') ? '1' : '0' );
	});
	$("#systemTable").on("change",".editable",function(){
		var hidden = $(this).siblings(".hiddenEditable");
	    hidden.val( $(this).is(':checked') ? '1' : '0' );
	});
	/*
	$("#systemTable").on("change",".typeName",function(){
		if ($(this).val=="config"){
			var currentRow = $(this).parent("tr");
		}
	});*/
	
	$("#systemTable").dataTable({
		//"bJQueryUI":true
	});
});