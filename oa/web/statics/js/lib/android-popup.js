/*安卓小米浏览器和微信内置浏览器不支持已有的popup样式 定位到visibility:hidden;opacity:0;不能点透，另外加载样式的方式解决 */
;
(function(window, $) {
	var userAgent = navigator.userAgent.toLowerCase();
	if ( /(android)/i.test(userAgent) ) {
		/* JavaScript动态加载Css文件 */
		var cssNode = document.createElement('link');
		cssNode.rel = 'stylesheet';
		cssNode.href = 'css/android-weixin-popup.css?t='+new Date().getTime();
		document.head.appendChild(cssNode);
	};

})(window, Zepto);	