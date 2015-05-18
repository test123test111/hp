;
(function(window, $) {
	var searchType = "",
		searchVal= "";

	bind();

	function bind() {
		$(document).on("click","#searchBtn",function(){
			
			searchType = $("#classifiedBtn").children("em").html();
			searchVal = $("#searchContent").val();

			if(searchVal == ""){
				$("#tipModal").modal();
				$("#noticeContent").html("请添加搜索内容!");
			}else{
				if(searchType == "订单号"){
					$("#searchType").attr("name","order_id");

				}else{
					$("#searchType").attr("name","logistic_no");

				}
				$("#searchType").val(searchVal);
				$("#searchBarForm").trigger("submit");
			}
		}).on("click","#expressType li",function(){
				
			var expressType = $(this).find("a").html(),
				benginTime = $("#beginT").val(),
				endTime = $("#endT").val();

			if(expressType == "全部"){
				expressType = "";
			}
			window.location.assign("/logistic/domestic?logistic_provider=" + expressType +"&begin_time=" + benginTime + "&end_time=" + endTime);

		});

		$("#printExpressBtn").off("click").on("click",function(event){
			event.preventDefault();
			var parameter = window.location.search;
			window.open("/logistic/print" + parameter , "_blank");
		});
	}

})(window, jQuery);