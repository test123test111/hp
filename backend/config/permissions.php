<?php
return [
	'manager/admin' => '管理员－查看列表',
    'manager/create' => '管理员－创建',
    'manager/delete' => '管理员－删除',
    'manager/update' => '管理员修改',
    'manager/reset-password'=>'管理员－修改密码',

    'auth/permlist'=>['管理员－权限列表','权限'],
    'auth/rolelist'=>['管理员－角色列表','权限'],
    'auth/flushperms'=>['管理员－更新权限','权限'],
    'auth/assign'=>['管理员－分配权限','权限'],
    'auth/assignment'=>['管理员－分配角色','权限'],
    'auth/update'=>['管理员－修改权限','权限'],
    'auth/delete'=>['管理员－删除权限','权限'],

    'printer/list'=>['仓储-打印机:列表','打印机'],
    'printer/create'=>['仓储-打印机:创建打印机','打印机'],
    'printer/update'=>['仓储-打印机:修改打印机','打印机'],
    'printer/view'=>['仓储-打印机:查看打印机人员','打印机'],


    'check/index'=>['仓储-验货:首页','验货'],
    'check/scanpack'=>['仓储-验货:扫描快递单','验货'],
    'check/print'=>['仓储-验货:打印订单条形码','验货'],
    'check/batchprint'=>['仓储-验货:批量打印订单条形码','验货'],
    'check/batchinput'=>['仓储-验货:批量入库','验货'],

    'input/index'=>['仓储-入库:首页','入库'],
    'input/valid'=>['仓储-入库:验证箱子有效','入库'],
    'input/update'=>['仓储-入库:编辑封装箱','入库'],
    'input/print'=>['仓储-入库:打印封装想标签','入库'],
    'input/print'=>['仓储-入库:打印封装想标签','入库'],
    'input/create'=>['仓储-入库:创建封装箱','入库'],
    'input/sealbox'=>['仓储-入库:封箱入库','入库'],
    'input/addorder'=>['仓储-入库:添加订单到封装箱','入库'],
    'input/deleteorder'=>['仓储-入库:从封装箱删除订单','入库'],

    'stock/index'=>['仓储-在库:首页','在库'],
    'stock/update'=>['仓储-在库:编辑封装箱','在库'],
    'stock/valid'=>['仓储-在库:验证箱子有效','在库'],
    'stock/print'=>['仓储-在库:打印订单','在库'],
    'stock/delete'=>['仓储-在库:删除订单','在库'],
    'stock/batchprint'=>['仓储-在库:批量打印订单','在库'],

    'output/index'=>['仓储-出库:首页','出库'],
    'output/update'=>['仓储-出库:编辑待出库箱子','出库'],
    'output/scanexpress'=>['仓储-出库:扫描快递单','出库'],
    'output/valid'=>['仓储-出库:验证待出库箱子','出库'],
    'output/scanorder'=>['仓储-出库:扫描订单','出库'],
    'output/moveorder'=>['仓储-出库:移动订单入箱','出库'],
    'output/do'=>['仓储-出库:执行出库','出库'],
    'output/rmorder' =>['仓储-出库:移出','出库'],
];