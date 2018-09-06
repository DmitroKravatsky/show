<?php

use yii\db\Migration;

/**
 * Class m180906_073339_change_bid_statuses
 */
class m180906_073339_change_bid_statuses extends Migration
{
    private $bidTable = '{{%bid}}';
    private $bidHistoryTable = '{{%bid_history}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn(
            $this->bidTable,
            'status',
            "ENUM('new', 'paid_by_client', 'in_progress', 'paid_by_us', 'done', 'rejected', 'paid_by_us_done') DEFAULT 'new'"
        );
        $this->update(
            $this->bidTable,
            ['status' => 'paid_by_us_done'],
            ['or', ['status' => 'paid_by_us'], ['status' => 'done']]
        );
        $this->alterColumn(
            $this->bidTable,
            'status',
            "ENUM('new', 'paid_by_client', 'in_progress', 'rejected', 'paid_by_us_done') DEFAULT 'new'"
        );

        $this->alterColumn(
            $this->bidHistoryTable,
            'status',
            "ENUM('new', 'paid_by_client', 'in_progress', 'paid_by_us', 'done', 'rejected', 'paid_by_us_done') DEFAULT 'new'"
        );
        $this->update(
            $this->bidHistoryTable,
            ['status' => 'paid_by_us_done'],
            ['or', ['status' => 'paid_by_us'], ['status' => 'done']]
        );
        $this->alterColumn(
            $this->bidHistoryTable,
            'status',
            "ENUM('new', 'paid_by_client', 'in_progress', 'rejected', 'paid_by_us_done') DEFAULT 'new'"
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn(
            $this->bidTable,
            'status',
            "ENUM('new', 'paid_by_client', 'in_progress', 'rejected', 'paid_by_us_done', 'paid_by_us', 'done') DEFAULT 'new'"
        );

        $this->update(
            $this->bidTable,
            ['status' => 'done'],
            ['status' => 'paid_by_us_done']
        );
        $this->alterColumn(
            $this->bidTable,
            'status',
            "ENUM('new', 'paid_by_client', 'in_progress', 'rejected', 'paid_by_us', 'done') DEFAULT 'new'"
        );

        $this->alterColumn(
            $this->bidHistoryTable,
            'status',
            "ENUM('new', 'paid_by_client', 'in_progress', 'rejected', 'paid_by_us_done', 'paid_by_us', 'done') DEFAULT 'new'"
        );

        $this->update(
            $this->bidHistoryTable,
            ['status' => 'done'],
            ['status' => 'paid_by_us_done']
        );
        $this->alterColumn(
            $this->bidHistoryTable,
            'status',
            "ENUM('new', 'paid_by_client', 'in_progress', 'rejected', 'paid_by_us', 'done') DEFAULT 'new'"
        );
    }
}
