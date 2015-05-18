(function(window, $) {
  
  //userName bug fix
  $("#userName").on("click",function(){

      $(this).find("#open").removeClass("open").addClass("open");
      $("#dropMenu li").on("click",function(){
        window.location.assign($(this).find("a").attr("href"));
      });
      return false;
  });

  //分类搜索
  bind();

  function bind(){
    $(document).on('click', '#searchMenu li', function() {

      var classifiedVal = $(this).find("a").html();
      $("#classifiedBtn").children("em").html(classifiedVal);
         
    });
  }
  
})(window, jQuery);