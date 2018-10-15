<?php

use yii\db\Migration;

/**
 * Class m181015_125441_add_min_transaction_sum_to_payment_system
 */
class m181015_125441_add_min_transaction_sum_to_payment_system extends Migration
{
    private $tableName = '{{%payment_system}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'min_transaction_sum', $this->float()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'min_transaction_sum');
    }
}
