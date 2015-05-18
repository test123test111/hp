;
(function(window, $) {

	bind();

	function bind() {

		$(document).off("click","#refund").on("click","#refund",function(){
			$("#refundTabLevelTwo").show();
		}).on("click",".pay-state",function(){
			$("#refundTabLevelTwo").hide();
		});	

		$(".form_datetime").datetimepicker({
	        format: "yyyy-mm-dd hh:ii"
	    });	
			
	}

})(window, jQuery);