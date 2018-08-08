<?php

use yii\db\Migration;

/**
 * Class m180808_090311_edit_bid
 */
class m180808_090311_edit_bid extends Migration
{
    private $tableName = '{{%bid}}';

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn($this->tableName, 'processed', $this->boolean()->defaultValue(false)->after('to_sum'));
        $this->addColumn($this->tableName, 'processed_by', $this->integer()->after('processed'));

        $this->addForeignKey(
            'fk-processed-bid-user',
            $this->tableName,
            'processed_by',
            '{{%user}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropForeignKey('fk-processed-bid-user', $this->tableName);
        $this->dropColumn($this->tableName, 'processed');
        $this->dropColumn($this->tableName, 'processed_by');
    }
}
