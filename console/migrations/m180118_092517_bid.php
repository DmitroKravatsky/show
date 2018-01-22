<?php

use yii\db\Migration;

/**
 * Class m180118_092517_bid
 */
class m180118_092517_bid extends Migration
{
    /**
     * @var string
     */
    private $tableName = '{{%bid}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = ($this->db->driverName === 'mysql')
            ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : null;

        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'created_by' => $this->integer(11)->notNull(),
            'status' => "ENUM('accepted', 'paid', 'done', 'rejected') DEFAULT 'accepted'",
            'from_sum' => $this->decimal()->notNull(),
            'to_sum' => $this->decimal()->notNull(),
            'from_wallet' => "ENUM('yandex_money', 'web_money', 'tincoff', 'privat24', 'sberbank', 'qiwi')",
            'to_wallet' => "ENUM('yandex_money', 'web_money', 'tincoff', 'privat24', 'sberbank', 'qiwi')",
            'from_wallet_number' => $this->string(20),
            'to_wallet_number' => $this->string(20),
            'from_card_number' => $this->string(20),
            'to_card_number' => $this->string(20),
            'from_currency' => "ENUM('usd', 'uah', 'rub', 'eur')",
            'to_currency' => "ENUM('usd', 'uah', 'rub', 'eur')",
            'name' => $this->string(20)->notNull(),
            'last_name' => $this->string(20)->notNull(),
            'phone_number' => $this->string(20)->notNull(),
            'email' => $this->string()->notNull(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ], $tableOptions);

        $this->addForeignKey('fk-bid-user', $this->tableName, 'created_by', 'user', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk-bid-user', $this->tableName);
        $this->dropTable($this->tableName);
    }
}
