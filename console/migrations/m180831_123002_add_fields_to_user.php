<?php

use yii\db\Migration;

/**
 * Class m180831_123002_add_fields_to_user
 */
class m180831_123002_add_fields_to_user extends Migration
{
    private $tableName = '{{%user}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'status_online', $this->smallInteger()->defaultValue(false));
        $this->addColumn($this->tableName, 'last_login', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       $this->dropColumn($this->tableName, 'status_online');
       $this->dropColumn($this->tableName, 'last_login');
    }
}
