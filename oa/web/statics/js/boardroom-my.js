

(function (window, $) {
    function getUrlParam(sParam)
    {
        var sPageURL = window.location.search.substring(1);
        var sURLVariables = sPageURL.split('&');
        for (var i = 0; i < sURLVariables.length; i++)
        {
            var sParameterName = sURLVariables[i].split('=');
            if (sParameterName[0] == sParam)
            {
                return sParameterName[1];
            }
        }
    }

    bind();

    function bind() {
        $(document).on("click", "#cancelBtn", function () {
            var data = $(this).parents('li').attr('data');
            $('#tip').attr('data', data);
            $("#mask").show();
            $("#tip").show();

        }).on("click", "#tipCnacelBtn", function () {
            $("#mask").hide();
            $("#tip").hide();
        }).on("click", "#reserveBtn", function () {
            window.location.assign("oa-confirm-meeting.html");
        }).on("click", "#removeConfirmBtn", function () {
            var record_id = $("#tip").attr('data');
            var user_id = getUrlParam('user_id');
            $.post('/wboardroom/remove', 
                {id: record_id, user_id: user_id},function(reponse) {
                location.reload();
            });
        }).on("click", "#sign", function () {
            var record_id = $(this).parents('li').attr('data');
            var user_id = getUrlParam('user_id');
            $.post('/wboardroom/sign', 
                {id: record_id, user_id: user_id},function(reponse) {
                location.reload();
            });
        }).on("click", "#my", function () {

            var user_id = getUrlParam('user_id');
            window.location.href = '/statics/boardroom-my.html?user_id='+user_id;
        }).on("click", ".has-select", function () {
            var room_id = $(this).attr('data');
            var user_id = getUrlParam('user_id');
            window.location.href = '/statics/boardroom-choice.html?user_id='+user_id+'&room_id='+room_id;
        });;
    }
    var user_id = getUrlParam('user_id');
    $.get('/wboardroom/my', {user_id: user_id}, function (response) {
        var html = '';
        $.each(response.records, function(key, item) {
              html += "<li class=\"clearfix\" data="+item.id+"><p>"+item.floor+'  '+item.room_name+"</p>";
            if (item.status == 3) {
                html +=  "<div><span class=\"gray-font\">"+item.time_range+"(时间到啦)</span><button class=\"green-bg\" id=\"sign\">打卡</button></div></li>";
            } else if (item.has_sign == 1) {
                html +=  "<div><span class=\"gray-font\">"+item.time_range+"(正在进行中)</span><button>约着</button></div></li>";
            } else if (item.has_sign == 0) {
                html +=  "<div><span class=\"gray-font\">"+item.time_range+"</span><button id=\"cancelBtn\" class=\"red-bg\">取消</button></div>";
            }
           });
        $('#records').append(html);
        
        var rooms_html = '';
        $.each(response.rooms, function(key , item) {
          rooms_html += "<li class=\"clearfix has-select\" data="+item.id+"><label><p>"
             +item.floor +"  "+item.name+"("+item.people_num+")"
             +"</p><span class=\"green-font\">应该空的吧</span></label></li>";
        });
        $('#rooms').append(rooms_html);
    }, 'json');

})(window, Zepto);
