<?php
ini_set('display_errors', 'On');
ini_set('display_startup_errors', 'On');
require(__DIR__ . '/../library/Jf/Db/Builder.php');
$builder = new \Jf\Db\Builder();
echo $builder->select(array('t1' => 'table1'))
        ->columns(array('a1', 'a2', 'a3'))
        ->leftJoin(array('t2'=> 'table2'), array('t1.a1' => 't2.a2'))
        ->assemble();


