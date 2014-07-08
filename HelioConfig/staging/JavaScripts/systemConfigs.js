$(document).ready(function() {
	var oTable = $("#configsTable").dataTable();
	oTable.on("change",".active",function(event){
		var activeConfigID = $(this).val();
		var systemID = $("#systemID").val();
		var dataObject = {"activeConfigID":activeConfigID,"systemID":systemID,"newActive":1};
		$.ajax({
			url:"systemConfigs.php",
			data: dataObject,
			success: 
				function(data){
					alert("Update successful!");
				},
			error:
				function(jqXHR, textStatus, errorThrown) {
					location.reload();
					alert("Update failed! "+errorThrown);
				}
		});
	});
	
	
});