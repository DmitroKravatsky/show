<?php

use yii\db\Migration;

/**
 * Class m180912_090511_add_in_progress_by_manager_to_bid_history
 */
class m180912_090511_add_in_progress_by_manager_to_bid_history extends Migration
{
    private $tableName = '{{%bid_history}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'in_progress_by_manager', $this->integer()->after('processed_by'));
        $this->addForeignKey(
            'fk-bid_history-in_progress_by_manager',
            $this->tableName,
            'in_progress_by_manager',
            'user',
            'id',
            'CASCADE',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-bid_history-in_progress_by_manager', $this->tableName);
        $this->dropColumn($this->tableName, 'in_progress_by_manager');
    }
}
