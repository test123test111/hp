<?php
$url = "http://customer.yltd.com/order/list";
?>
<html>
	<body>
		<p>尊敬的<b><?php echo $record['email']; ?></b>:</p>
		<br><br>
		有您如下物料入库，请您知悉，欢迎随时登录系统查阅详情。
		<br><br>
		<table class="email_tab">
            <thead>
                <tr bg="#edeeee">
                    <td>序号</td>
                    <td>物料编号</td>
                    <td>物料名称</td>
                    <td>入库时间</td>
                    <td>入库数量</td>
                    <td>物料类别</td>
                    <td>分享与否</td>
                    <td>送货单位</td>
                    <td>备注信息</td>
                </tr>
            </thead>
            <tbody>
                <tr bg="#fff">
                    <td>1</td>
                    <td><?php echo $record['code']; ?></td>
                    <td><?php echo $record['name']; ?></td>
                    <td><?php echo $record['stock_time']; ?></td>
                    <td><?php echo $record['quantity']; ?></td>
                    <td><?php echo $record['property']; ?></td>
                    <td><?php echo $record['share']; ?></td>
                    <td><?php echo $record['delivery']; ?></td>
                    <td><?php echo $record['info']; ?></td>
                </tr>
            </tbody>
        </table>
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
		th,td{font-family:arial,verdana,sans-serif;}
		img{ border:0}
		header,footer,section,aside,article,nav,hgroup,figure,figcaption{display:block}

		.email_tab{border-spacing:0;border-collapse:collapse;table-layout:fixed;width:98%;border:1px solid #d1d2d2;border-right:0 none;margin:10px 1%;}
		.email_tab td{font-size:14px;border-right:1px solid #d1d2d2;padding:10px;text-align:center;}
		.email_tab thead td{color:#333;background:#edeeee}
		</style>

		<style id="ntes_link_color" type="text/css">a,td a{color:#064977}</style>
	</body>
</html>