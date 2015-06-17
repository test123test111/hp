$(function(){
    // select address
    $("#exportStockBtn").live("click",function(){
        $("#searchImport").attr('action','/stock/import');
        $("#searchImport").submit();
    });
});