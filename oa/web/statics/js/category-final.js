;
(function(window, $) {
	var stockId = am.getQuery('stockId') || localStorage.getItem("stockId"),
		scateVal = am.getQuery('scateVal') || localStorage.getItem("scateVal"),
		pcateVal = am.getQuery('pcateVal') || localStorage.getItem("pcateVal"),
		pcateId = am.getQuery('pcateId') || localStorage.getItem("pcateId"),
		scateId = am.getQuery('scateId') || localStorage.getItem("scateId"),
		userId = am.getQuery('userId') || localStorage.getItem("userId");

	getStock();
	bind();

	function bind(){
		$("#categoryPrimary").html(pcateVal);
		$("#categorySecond").html(scateVal);

		$(document).on("click","#nextBtn",function(){
			localStorage.setItem("judgeNum",0);
			nextStock();
		}).on("click","#fixBtn",function(){
			localStorage.setItem("judgeNum",1);
			window.location.assign("category-primary.html");
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

	function nextStock() {
		$.ajax({
			type: 'POST',
			url: '/u/index/setStockInfo',
			dataType: 'json',
			data:{
				"sid" : stockId,
				"pcateid" : pcateId,
				"scateid" : scateId,
				"user" : userId
			},
			success: function(data) {
				if( !data.errno ){

					localStorage.setItem("judgeNum",0);
					window.location.assign("category-primary.html");

				}else{
					am.tip(data.err);
				}
			}
		});
	}
})(window, Zepto);