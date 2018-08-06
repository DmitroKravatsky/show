<?php

use yii\db\Migration;

/**
 * Class m180806_115324_add_status_to_bid
 */
class m180806_115324_add_status_to_bid extends Migration
{
    /**
     * @var string
     */
    private $tableName = '{{%bid}}';

    /**
     * @return bool|void
     */
    public function up()
    {
        $this->alterColumn($this->tableName, 'status', "ENUM('accepted', 'paid', 'done', 'rejected', 'in_progress') DEFAULT 'accepted'");
    }

    /**
     * @return bool|void
     */
    public function down()
    {
        $this->alterColumn($this->tableName, 'status', "ENUM('accepted', 'paid', 'done', 'rejected') DEFAULT 'accepted'");
    }
}
