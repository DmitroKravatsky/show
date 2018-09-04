<?php

use yii\db\Migration;

/**
 * Class m180904_085450_add_register_by_bid_to_user
 */
class m180904_085450_add_register_by_bid_to_user extends Migration
{
    private $tableName = '{{%user}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'register_by_bid', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'register_by_bid');
    }
}
