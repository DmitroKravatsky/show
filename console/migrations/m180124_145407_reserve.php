<?php

use yii\db\Migration;

/**
 * Class m180124_145407_reserve
 */
class m180124_145407_reserve extends Migration
{
    /**
     * @var string
     */
    private $tableName = '{{%reserve}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = ($this->db->driverName === 'mysql')
            ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : null;

        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'payment_system' => "ENUM('yandex_money', 'web_money', 'tincoff', 'privat24', 'sberbank', 'qiwi')",
            'currency' => "ENUM('usd', 'uah', 'rub', 'eur')",
            'sum' => $this->float()->notNull(),
            'image' => $this->string(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
