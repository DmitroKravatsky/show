<?php

use yii\db\Migration;

/**
 * Class m180830_085723_alter_recovery_code_column
 */
class m180830_085723_alter_recovery_code_column extends Migration
{
    private $tableName = '{{%user}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn($this->tableName, 'recovery_code', $this->string(4));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn($this->tableName, 'recovery_code', $this->integer(4));
    }
}
