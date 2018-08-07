<?php

use yii\db\Migration;

/**
 * Class m180806_152002_bid_history
 */
class m180806_152002_bid_history extends Migration
{
    private $tableName = '{{%bid_history}}';

    public function up()
    {
        $tableOptions = ($this->db->driverName === 'mysql') ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : null;

        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'bid_id' => $this->integer()->notNull(),
            'status' => "ENUM('accepted', 'paid', 'done', 'rejected', 'in_progress')",
            'time' => $this->integer()
        ], $tableOptions);

        $this->addForeignKey(
            'fk-bid_history-bid',
            $this->tableName,
            'bid_id',
            '{{%bid}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
    }

    public function down()
    {
        $this->dropForeignKey('fk-bid_history-bid', $this->tableName);
        $this->dropTable($this->tableName);
    }
}
