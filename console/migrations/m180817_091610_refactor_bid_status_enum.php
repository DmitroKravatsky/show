<?php

use yii\db\Migration;

/**
 * Class m180817_091610_refactor_bid_status_enum
 */
class m180817_091610_refactor_bid_status_enum extends Migration
{
    private $tableName = '{{%bid}}';

    public function up()
    {
        $this->alterColumn(
            $this->tableName,
            'status',
            "ENUM('new', 'paid_by_user', 'paid_by_us', 'done', 'rejected') DEFAULT 'new'"
        );

    }

    public function down()
    {
        $this->alterColumn(
            $this->tableName,
            'status',
            "ENUM('accepted', 'paid', 'done', 'rejected') DEFAULT 'accepted'"
        );
    }

}
