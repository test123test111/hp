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

    $(".left_box dt.havesub").click(function(){
        $(this).parent().find("dd").toggle();
    });
    // add new address
    $(".updatePw").live("click",function(){
        editPw();
    });
});
// edit address
function editPw(){
    var addr_url = "/owner/displaypassword";
    safeCode = $('meta[name=csrf-token]');
    $.ajax({
        url:addr_url,
        dataType:"html",
        type:"POST",
        data:{"_csrf":safeCode.attr('content')},
        success:function(json){
            $.layer({
                type: 1,
                shade: [0.5, '#000'],
                area: ['380', '150'],
                title: '地址',
                btns: 2,
                btn: ['确定', '取消'],
                border: [5, 0.2, '#000'],
                yes: function(){
                    postPw();
                },
                page: {html:json}
            });
        }

    });
}
function postPw(){
    var addr_url = '/owner/update';
    safeCode = $('meta[name=csrf-token]');
    var password = $("#password_owner").val();
    if(password == ""){
        alert("请输入要设置的新密码!");return false;
    }
    if(password.length < 6){
        alert("密码长度最少6位!");return false;
    }
    $.ajax({
        url:addr_url,
        dataType:"html",
        type:"POST",
        data:{"Hhg[password]":password,'_csrf':safeCode.attr('content')},
        success:function(json){
            if(json == 0){
                alert('设置成功');
                window.location.reload();
            }
        }
    });
}
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
                $("#noticeCenter").removeClass("notice").addClass("notice_active");  
            }else{
                $("#noticeCenter").removeClass("notice_active").addClass("notice");  
            }
        }
    });
}

