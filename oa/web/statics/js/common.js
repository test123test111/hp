if (!window.am) {
	window.am = {};
};

(function(am, $) {

	$.extend(am, {
		bind: function() {
			FastClick.attach(document.body);
			Handlebars.registerHelper('staticPath', function() {
				return 'http://img.taoshij.com';
			});
			
			var $body = $('body'),
				domId = 0,
				downloadSource = am.getQuery('for') || 'stock';
				
			$(document).on('touchstart', 'a,button', function() {
				$(this).addClass('touch-style');
			}).on('touchend', 'a,button', function() {
				$(this).removeClass('touch-style');
			}).on('click', '#closeDownloadBar', function() {
				$(this).parent().hide();
			}).on("click","#downloadbar",function(event){
				if(event.target.nodeName != 'I'){
					window.location.assign('download.html?for='+downloadSource);
				}
			}).on('ajaxComplete',function () {
				$(".lazy").lazyload();
	        });
		},
		tip: function(argument) {
			var $alert = $('#alert');
			$alert.show().find('p').html(argument);
			setTimeout(function() {
				$alert.hide();
			}, 1500);
		},
		// 获取地址栏参数
        // 示例： 地址栏为 http://localhost/a.html?b=1&c=2
        // getQuery('b'); // 输出1
        getQuery: function(name) {
            var pattern = new RegExp("[?&]" + name + "\=([^&]+)", "g");
            var matcher = pattern.exec(window.location.search);
            var items = null;
            if (matcher) {
                items = decodeURIComponent(matcher[1]);
            };
            return items;
        },
		tabsFixed: function() {
			var resizeTimer = null,
				$tabs = $('#tabs'),
				tabsTop = $tabs.get(0).offsetTop;

			//窗口大小调整时,重新获取dom元素位置
			$(window).resize(function() {
				tabsTop = $tabs.get(0).offsetTop;
			});	

			$(window).on('scroll', function() {
				if (resizeTimer) {
					clearTimeout(resizeTimer)
				}
				resizeTimer = setTimeout(function() {
					if( $(window).scrollTop() > tabsTop ){
						$tabs.addClass('fixed');
					}else{
						$tabs.removeClass('fixed');
					}
				}, 400);
			});
		},
		// 格式化时间差 XX分，小时，天前
		beforeDate: function(differ) {
			var _date = "";
            if (differ < 60) {
                _date = parseInt(differ, 10) + '秒前';
            } else if (differ < 3600) {
                _date = parseInt(differ / 60, 10) + '分钟前';
            } else if (differ < 24 * 3600) {
                _date = parseInt(differ / 3600, 10) + '小时前';
            } else {
                _date = parseInt(differ / (24 * 3600), 10) + '天前';
            };

            return _date;
        },
		// 格式化日期全局类
        DATE: function(date, format) {
        	var self = this;

        	//判断时间是否毫秒，秒则*1000
        	if( date < 10000000000 ){
        		date = date.getTime ? date*1000 : new Date(date*1000);
        	}else{
        		date = date.getTime ? date : new Date(date);
        	}
            
            var _date = "",now = new Date();

            if (format) {
	            var o = {
	                "M+": date.getMonth() + 1, //month
	                "d+": date.getDate(), //day
	                "h+": date.getHours(), //hour
	                "m+": date.getMinutes(), //minute
	                "s+": date.getSeconds(), //second
	                //quarter
	                "q+": Math.floor((date.getMonth() + 3) / 3),
	                "S": date.getMilliseconds() //millisecond
	            };
	            if (/(y+)/.test(format)) {
	                format = format.replace(RegExp.$1, (date.getFullYear() + "").substr(4 - RegExp.$1.length));
	            };
	            for (var k in o) {
	                if (new RegExp("(" + k + ")").test(format)) {
	                    format = format.replace(RegExp.$1, RegExp.$1.length == 1 ? o[k] : ("00" + o[k]).substr(("" + o[k]).length));
	                };
	            };

	            _date = format;
	        } else {
	        	
	        	// +1000, 用来忽略掉小于1秒的情况
	            var differ = (now - date + 1000) / 1000;
	            console.log(date,differ)
	            if (differ < 0) {
	                return self.DATE(date, now.getFullYear() === date.getFullYear() ? 'MM-dd hh:mm' : 'yyyy-MM-dd hh:mm');
	            };
	            if (differ < 60) {
	                _date = parseInt(differ, 10) + '秒前';
	            } else if (differ < 60 * 60) {
	                _date = parseInt(differ / 60, 10) + '分钟前';
	            } else {
	                var formater;
	                if (differ < 24 * 60 * 60 && now.getDate() == date.getDate()) {
	                    formater = '今天 hh:mm';
	                } else {
	                    formater = now.getFullYear() === date.getFullYear() ? 'MM-dd hh:mm' : 'yyyy-MM-dd hh:mm';
	                };
	                _date = self.DATE(date, formater);
	            };
	        }

            return _date;
        },
        // 同一天就展示成 12月2日 12:00 — 14:00 ; 不同一天则展示成 12月2日 23:00 — 12月3日 01:00
        mergeTime: function( startTime,endTime ) {
        	var self = this;
        	var durTime = endTime - startTime;
        	
			if( durTime < 24 * 3600 ){
				liveTime = self.DATE(startTime,'MM月dd日 hh:mm') + ' - ' + self.DATE(endTime,'hh:mm');
			}else{
				liveTime = self.DATE(startTime,'MM月dd日 hh:mm') + ' - ' + self.DATE(endTime,'MM月dd日 hh:mm');	
			}

			return liveTime;
        },
        //根据频幕宽度改变meta viewport的initial-scale值
        fixMeta: function() {
        	var $viewport = $('#viewport'),
				scale = $(window).width() > 700 ? 1:0.5,
				content = 'width=device-width,height=device-height,initial-scale='+scale+',user-scalable=no';
			$viewport.attr('content',content);
        }
	});
	am.fixMeta();
	am.bind();

})(am, Zepto);