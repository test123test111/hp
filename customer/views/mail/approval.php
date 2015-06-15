<?php
$id = $record->order_id;
$url = "http://customer.yltd.com/order/viewapproval/{$id}";
if($record->type == 0){
	$type = '物料';
}else{
	$type = '费用';
}
?>
<html>
	<body>
		<p>订单<b><?php echo $order->viewid; ?></b>中包含需要您审批的<?php echo $type; ?>
		<br><br>
		您可以<a href="$url">
			点击查看订单详情进行审批<br />
		</a>
		如果上面的链接无法点击，请复制下面网址到您浏览器地址栏
		<?php echo $url; ?>
		<br><br>
		Thanks!
		<br><br>
		-HHG
		<br><a href="#">www.yltd.com</a>
		<br>
		&nbsp;
		</p>
		<hr>
		<br>
		这是一封由系统自动发送的邮件，请勿回复!

		 

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