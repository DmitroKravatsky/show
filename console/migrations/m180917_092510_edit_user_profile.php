<?php

use yii\db\Migration;

/**
 * Class m180917_092510_edit_user_profile
 */
class m180917_092510_edit_user_profile extends Migration
{
    private $tableName = '{{%user_profile}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn($this->tableName, 'name', $this->string(20));
        $this->alterColumn($this->tableName, 'last_name', $this->string(20));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn($this->tableName, 'name', $this->string(20)->notNull());
        $this->alterColumn($this->tableName, 'last_name', $this->string(20)->notNull());
    }
}
