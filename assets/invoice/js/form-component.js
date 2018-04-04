var Script = function() {

	//chosen select
	//$(".chzn-select").chosen();

	//date picker

	if (top.location != location) {
		top.location.href = document.location.href;
	}
	$(function() {
		window.prettyPrint && prettyPrint();
		$('#sales_date').datepicker({
			format: 'dd/mm/yyyy'
		});
	});

	//Toggle Button
	//$('#radiobutton').radiobutton();

	window.prettyPrint && prettyPrint();


}();