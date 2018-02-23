<?php

use yii\db\Migration;

/**
 * Class m180219_080403_wallet
 */
class m180219_080403_wallet extends Migration
{
    /**
     * @var string
     */
    private $tableName = '{{%wallet}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = ($this->db->driverName === 'mysql') ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : null;

        $this->createTable($this->tableName, [
            'id'             => $this->primaryKey(),
            'created_by'     => $this->integer(11)->notNull(),
            'name'           => $this->string(64),
            'number'         => $this->string(32)->notNull(),
            'payment_system' => "ENUM('yandex_money', 'web_money', 'tincoff', 'privat24', 'sberbank', 'qiwi')",
            'created_at'     => $this->integer(),
            'updated_at'     => $this->integer()
        ], $tableOptions);

        $this->addForeignKey('fk-wallet_template-user', $this->tableName, 'created_by', 'user', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk-wallet_template-user', $this->tableName);
        $this->dropTable($this->tableName);
    }
}
