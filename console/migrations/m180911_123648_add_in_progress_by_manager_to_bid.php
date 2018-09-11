<?php

use yii\db\Migration;

/**
 * Class m180911_123648_add_in_progress_by_manager_to_bid
 */
class m180911_123648_add_in_progress_by_manager_to_bid extends Migration
{
    private $tableName = '{{%bid}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'in_progress_by_manager', $this->integer()->after('processed_by'));
        $this->addForeignKey(
            'fk-bid-in_progress_by_manager',
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
       $this->dropForeignKey('fk-bid-in_progress_by_manager', $this->tableName);
       $this->dropColumn($this->tableName, 'in_progress_by_manager');
    }
}
