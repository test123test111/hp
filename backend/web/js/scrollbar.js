(function($) {
	getBuyerInfo()；
	function getBuyerInfo() {
		$.ajax({
			type: 'GET',
			url: 'u/buyerChannel/info',
			dataType: 'json',
			data: {
				"buyer_id" : buyerId
			},
			success: function(data) {
				if( !data.errno ){
					Handlebars.registerHelper('livingImg', function() {
						return this.imgs[0];
					});
					Handlebars.registerHelper('livedImg', function() {
						return this.imgs[0];
					});
					Handlebars.registerHelper('displayTime', function() {
						return this.end_time;
					});						

					var buyerCompiled = aimeilive["buyer"],
						buyerTemplate = buyerCompiled(data.rst);
					$("#buyer").append(buyerTemplate);

				}else{
					am.tip(data.err);
				}
			},
			complete: function() {
				am.tabsFixed();			
				$loadingBar = $("#loadingBar");
				
				var liveEndTime;
				$(".countDown").each(function(index, elm) {
					liveEndTime = parseInt( $(this).html() ) * 1000;
					fnTimeCountDown( liveEndTime, $(this) );
				});
			}
		});
	}

})(jQuery);
