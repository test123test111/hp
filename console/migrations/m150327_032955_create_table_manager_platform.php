<?php

use yii\db\Schema;
use yii\db\Migration;

class m150327_032955_create_table_manager_platform extends Migration
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

        $this->createTable('manager_platform', [
          'id' => ' int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY UNIQUE KEY ',
          'manager_id' => 'int(11) NOT NULL',
          'platform' => 'varchar(32) NOT NULL DEFAULT "cangchu"',
          'created_time' => 'int(11) NOT NULL',
          'modified_time' => 'int(11) NOT NULL',
        ], $tableOptions);

        $this->createIndex('manager_id','manager_platform',['manager_id']);
        $this->createIndex('platform','manager_platform',['platform']);
    }

    public function down()
    {
        $this->dropTable('manager_platform');
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
