$(document).ready(function(){
    setInterval("getNeedHandleOrder();", 5000); 
});
function getNeedHandleOrder(){
    var request_url = '/ajax/neworder'
    $.ajax({
      url:request_url,
      dataType:"json",
      type:"GET",
      success:function(json){
          if(json > 0){
              $("#unhandleOrder").html(json);
          }
      }
    });
}