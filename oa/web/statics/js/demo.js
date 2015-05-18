
(function(window, $) {
	init();
	function init(){

		var mySwiper = new Swiper('.swiper-container',{
		    mode: 'vertical',
		    grabCursor: true,
		    onInit:function(swiper, direction) {
		      var index = swiper.activeIndex;
		      var height = $(window).innerHeight();
		      var obj = $(".swiper-slide");
		      obj.not(".swiper-slide-visible").find(".aimei").addClass('hide');
		      obj.eq(index).find(".aimei").removeClass("hide");
		  },

		    onSlideChangeEnd:function(swiper, direction) {
		      var index = swiper.activeIndex;
		      var obj = $(".swiper-slide");
		      //companyHeightReset();
		      obj.eq(index).siblings().find(".aimei").addClass('hide');
		      obj.eq(index).find(".aimei").removeClass("hide");
		  }
	 	});
	}
	

})(window, Zepto);