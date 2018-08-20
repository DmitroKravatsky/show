<?php

use yii\db\Migration;

/**
 * Class m180820_133338_add_processed_by_to_bid_history
 */
class m180820_133338_add_processed_by_to_bid_history extends Migration
{
    private $tableName = '{{%bid_history}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'processed_by', $this->integer()->after('bid_id'));
        $this->addForeignKey(
            'fk-bid_history_user',
            $this->tableName,
            'processed_by',
            '{{%user}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-bid_history_user', $this->tableName);
        $this->dropColumn($this->tableName, 'processed_by');
    }
}
