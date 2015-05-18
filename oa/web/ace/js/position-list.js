; 
(function(window,$){
        bind();

        function bind(){
           $("#tabLevelOne .active").each(function(index,elem){
                
                $("#status").val($(this).data("status"));
           });

           $("#tabLevelTwo .active").each(function(index,elem){

                $("#type").val($(this).data("type"));
           }); 

           $(document).on("click","#tabLevelOne li",function(){
               
                $("#status").val($(this).data("status"));
                $("#searchBarForm").submit();

           }).on("click","#tabLevelTwo li",function(){

                $("#type").val($(this).data("type"));
                $("#searchBarForm").submit();

           });  
        }
})(window,jQuery);