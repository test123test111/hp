<?php

use yii\db\Schema;
use yii\db\Migration;

class m150324_034930_create_table_printer_user extends Migration
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

        $this->createTable('printer_user', [
          'id' => ' int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY UNIQUE KEY ',
          'printer_id' => 'int(11) NOT NULL',
          'type' => 'tinyint(4) NOT NULL DEFAULT "0"',
          'user_id' => 'int(11) NOT NULL',
          'status' => 'tinyint(4) NOT NULL DEFAULT "0"',
          'created_uid' => 'int(11) NOT NULL',
          'modified_uid' => 'int(11) NOT NULL',
          'created_time' => 'int(11) NOT NULL',
          'modified_time' => 'int(11) NOT NULL',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('printer_user');
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
