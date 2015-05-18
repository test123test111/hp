/* by zhangxinxu 2010-07-27 
* http://www.zhangxinxu.com/
*/
var fnTimeCountDown = function(d,o){
	var f = {
		zero: function(n){
			var n = parseInt(n, 10);
			if(n > 0){
				if(n <= 9){
					n = "0" + n;	
				}
				return String(n);
			}else{
				return "00";	
			}
		},
		dv: function(){
			d = d || Date.UTC(2050, 0, 1); 
			var future = new Date(d), now = new Date();
			var dur = Math.round((future.getTime() - now.getTime()) / 1000) /* + future.getTimezoneOffset() * 60 */, pms = {
				sec: "00",
				mini: "00",
				hour: "00",
				day: "00"
				// month: "00",
				// year: "0"
			};
			if(dur > 0 ){
				pms.sec = f.zero(dur % 60);
				pms.mini = Math.floor((dur / 60)) > 0? f.zero(Math.floor((dur / 60)) % 60) : "00";
				pms.hour = Math.floor((dur / 3600)) > 0? f.zero(Math.floor((dur / 3600)) % 24) : "00";
				pms.day = Math.floor((dur / 86400)) > 0? f.zero(Math.floor((dur / 86400)) % 30) : "00";
				// pms.month = Math.floor((dur / 2629744)) > 0? f.zero(Math.floor((dur / 2629744)) % 12) : "00";
				// pms.year = Math.floor((dur / 31556926)) > 0? Math.floor((dur / 31556926)) : "0";
				
			}
			return pms;
		},
		ui: function(){
			var timer = null;
			// if(o.sec){
			// 	o.sec.innerHTML = f.dv().sec;
			// }
			// if(o.mini){
			// 	o.mini.innerHTML = f.dv().mini;
			// }
			// if(o.hour){
			// 	o.hour.innerHTML = f.dv().hour;
			// }
			
			if( o.html() != '00:00:00' ){
				if( f.dv().day < 1 ){
					o.html(f.dv().hour+':'+f.dv().mini+':'+f.dv().sec);
				}else{
					o.html(f.dv().day+'天'+f.dv().hour+'小时');
				}
			}else{
				var pathurl = window.location.pathname;
				if( pathurl == '/order-list.html' ){
					o.siblings('button').remove();
					o.parents('div').find('strong').html('订单已取消');
				}else if( pathurl == '/counter.html' ){
					window.location.assign('order-list.html');
				}else {
					o.addClass('timeover').html('已结束');
					clearTimeout(timer);
				}
				return;
			}
			// if(o.day){
			// 	o.day.innerHTML = f.dv().day;
			// }
			// if(o.month){
			// 	o.month.innerHTML = f.dv().month;
			// }
			// if(o.year){
			// 	o.year.innerHTML = f.dv().year;
			// }
			timer = setTimeout(f.ui, 1000);
		}
	};	
	f.ui();
};