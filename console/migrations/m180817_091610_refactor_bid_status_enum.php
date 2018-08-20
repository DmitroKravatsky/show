<?php

use yii\db\Migration;

/**
 * Class m180817_091610_refactor_bid_status_enum
 */
class m180817_091610_refactor_bid_status_enum extends Migration
{
    private $bidTable = '{{%bid}}';
    private $bidHistoryTable = '{{%bid_history}}';

    public function safeUp()
    {
        $this->alterColumn(
            $this->bidTable,
            'status',
            "ENUM('new', 'paid_by_client', 'paid_by_us', 'done', 'rejected') DEFAULT 'new'"
        );
        $this->alterColumn(
            $this->bidHistoryTable,
            'status',
            "ENUM('new', 'paid_by_client', 'paid_by_us', 'done', 'rejected') DEFAULT 'new'"
        );

    }

    public function safeDown()
    {
        $this->alterColumn(
            $this->bidTable,
            'status',
            "ENUM('accepted', 'paid', 'done', 'rejected') DEFAULT 'accepted'"
        );
        $this->alterColumn(
            $this->bidHistoryTable,
            'status',
            "ENUM('accepted', 'paid', 'done', 'rejected') DEFAULT 'accepted'"
        );
    }

}
