$(function() {
	$( "#dialog-form" ).dialog({
		autoOpen: false,
		height: 300,
		width: 350,
		modal: true,
		buttons: {
			"OK": function() {
				$( "#groupIDForm" ).submit();
			},
			"Cancel": function() {
				$( this ).dialog( "close" );
			}
		}
	});
	$( "#addNewSystem" ).click(
		function() {
			$( "#dialog-form" ).dialog( "open" );
	});
});