<?php

use yii\db\Schema;
use yii\db\Migration;

class m150324_022656_create_table_printer extends Migration
{
    public function init()
    {
        $this->db = 'backenddb';
        parent::init();
    }
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('printer', [
          'id' => ' int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY UNIQUE KEY ',
          'type' => 'tinyint(4) NOT NULL DEFAULT "0"',
          'desc' => 'varchar(255) NOT NULL DEFAULT ""',
          'status' => 'tinyint(4) NOT NULL DEFAULT "0"',
          'created_uid' => 'int(11) NOT NULL',
          'modified_uid' => 'int(11) NOT NULL',
          'created_time' => 'int(11) NOT NULL',
          'modified_time' => 'int(11) NOT NULL',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('printer');
    }
    
    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }
    
    public function safeDown()
    {
    }
    */
}
