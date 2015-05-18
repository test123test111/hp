;
(function(window, $) {

	bind();

	function bind() {

		$(document).off("click","#refund").on("click","#refund",function(){
			$("#refundTabLevelTwo").show();
		}).on("click",".pay-state",function(){
			$("#refundTabLevelTwo").hide();
		});			
	}

})(window, jQuery);