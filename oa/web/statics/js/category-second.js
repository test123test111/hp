;
(function(window, $) {
	var stockId = am.getQuery('stockId') || localStorage.getItem("stockId"),
		pcateId = am.getQuery('pcateId') || localStorage.getItem("pcateId"),
		scateId = 0,
		scateVal = "";

	getStock();
	getPrimaryCategory();
	bind();

	function bind(){
		$(document).on("click","#secondList li",function(){
			scateId = $(this).data("id");
			scateVal = $(this).find("p").html();
			
			localStorage.setItem("scateId",scateId);
			localStorage.setItem("scateVal",scateVal);
		});
	}

	function getStock() {
		$.ajax({
			type: 'GET',
			url: '/u/index/getNoCateStock',
			dataType: 'json',
			data:{
				"stockid" : stockId
			},
			success: function(data) {
				if( !data.errno ){

					var stockCompiled = aimeilive["stockInfo"],
						stockTemplate = stockCompiled(data.rst);
					$("#categoryGame").prepend(stockTemplate);

				}else{
					am.tip(data.err);
				}
			}
		});
	}
	function getPrimaryCategory() {
		$.ajax({
			type: 'GET',
			url: '/u/index/getSecondCate',
			dataType: 'json',
			data:{
				"pcateid" : pcateId
			},
			success: function(data) {
				if( !data.errno ){

					var categoryCompiled = aimeilive["categorySecond"],
						categoryTemplate = categoryCompiled(data.rst);
					$("#categoryGame").append(categoryTemplate);

				}else{
					am.tip(data.err);
				}
			}
		});
	}

})(window, Zepto);