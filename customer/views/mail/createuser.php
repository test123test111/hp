<?php
$url = "http://hp.yt-logistics.cn";
?>
<html>
	<body>
		<p>尊敬的<b><?php echo $record['email']; ?></b>:</p>
		<br />
		已成功为您在物流仓储系统中创建用户权限。
		<br>
		登录邮箱：<?php echo $record['email']; ?>
		<br>
		登录密码：<?php echo $record['password']; ?>
		<br>
		<p>
			特别提醒：为保护您的权限不受到侵害，请不要随意泄露用户名和密码。
			首次登录，请修改密码后，再进行后续的系统操作。
			欢迎您的使用！
		</p>
		您可以<a href="$url">
			点击登录系统<br />
		</a>
		如果上面的链接无法点击，请复制下面网址到您浏览器地址栏
		<?php echo $url; ?>
		<br><br>
		感谢您的合作！
		<br><br>
		&nbsp;
		</p>
		<hr>
		<br>
		此邮件为系统自动发送，请不要直接回复。

		<style type="text/css">
		body{font-size:14px;font-family:arial,verdana,sans-serif;line-height:1.666;padding:0;margin:0;overflow:auto;white-space:normal;word-wrap:break-word;min-height:100px}
		td, input, button, select, body{font-family:Helvetica, 'Microsoft Yahei', verdana}
		pre {white-space:pre-wrap;white-space:-moz-pre-wrap;white-space:-pre-wrap;white-space:-o-pre-wrap;word-wrap:break-word}
		th,td{font-family:arial,verdana,sans-serif;line-height:1.666}
		img{ border:0}
		header,footer,section,aside,article,nav,hgroup,figure,figcaption{display:block}
		</style>

		<style id="ntes_link_color" type="text/css">a,td a{color:#064977}</style>
	</body>
</html>