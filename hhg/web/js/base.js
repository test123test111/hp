$(function(){
    $(".user span").click(function(e){
        e.stopPropagation();
        $(this).toggleClass("open");
        $(".user .sub").toggle();
    });
    $(document).click(function(){
        $(".user span").removeClass("open");
        $(".user .sub").hide();
    });
    setInterval("getNeedApproval();", 5000); 
});

$(document).ready(function(){
    $(".materiel_table tr td").mouseover(function(){
        $(this).parent().find("td").css("background-color","#f7f7f7");
    });
})
$(document).ready(function(){
    $(".materiel_table tr td").mouseout(function(){
        var bgc = $(this).parent().attr("bg");
        $(this).parent().find("td").css("background-color",bgc);
    });
})

$(document).ready(function(){
    var color="#edeeee"
    $(".materiel_table tr:even td").css("background-color",color);
    $(".materiel_table tr:odd").attr("bg","#fff");
    $(".materiel_table tr:even").attr("bg",color);
})

function gotop(){
    $('body,html').animate({scrollTop:0},200);
}
function getNeedApproval(){
    var request_url = '/ajax/neworder';
    $.ajax({
        url:request_url,
        dataType:"json",
        type:"GET",
        success:function(json){
            if(json > 0){

            }
        }
    });
}

