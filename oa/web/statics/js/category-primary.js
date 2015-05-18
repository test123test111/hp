;
(function(window, $) {
	var stockId = localStorage.getItem("stockId") || 0,
		judgeNum = localStorage.getItem("judgeNum") || 0,
		userId = am.getQuery("userId") || localStorage.getItem("userId"),
		pcateId = 0,
		pcateVal = "";
		
	if(judgeNum == 0){
		getRandomStock();
	}else{
		getStock();
	}
	
	getPrimaryCategory();
	bind();

	function bind(){
		localStorage.setItem("judgeNum",0);
		localStorage.setItem("userId",userId);
		$(document).on("click","#primaryList li",function(){
			pcateId = $(this).data("id");
			pcateVal = $(this).find("p").html();

			localStorage.setItem("pcateId",pcateId);
			localStorage.setItem("pcateVal",pcateVal);
		}).on("click","#changeBtn",function(){
			localStorage.setItem("judgeNum",0);
			window.location.assign("category-primary.html");
		});
	}

	function getRandomStock() {
		$.ajax({
			type: 'GET',
			url: '/u/index/getNoCateStock',
			dataType: 'json',
			success: function(data) {
				if( !data.errno ){

					var stockCompiled = aimeilive["stockInfo"],
						stockTemplate = stockCompiled(data.rst);
					$("#categoryGame").prepend(stockTemplate);

					stockId = $("#stockHeader").data("id");
					localStorage.setItem("stockId",stockId);

				}else{
					am.tip(data.err);
				}
			}
		});
	}
	function getStock() {
		$.ajax({
			type: 'GET',
			url: '/u/index/getNoCateStock',
			dataType: 'json',
			data: {
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
			type: 'get',
			url: '/u/index/getParentCate',
			dataType: 'json',
			success: function(data) {
				if( !data.errno ){

					var categoryCompiled = aimeilive["categoryPrimary"],
						categoryTemplate = categoryCompiled(data.rst);
					$("#categoryGame").append(categoryTemplate);

				}else{
					am.tip(data.err);
				}
			}
		});
	}

})(window, Zepto);