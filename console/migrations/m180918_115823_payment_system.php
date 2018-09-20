<?php

use yii\db\Migration;

/**
 * Class m180918_115823_payment_system
 */
class m180918_115823_payment_system extends Migration
{
    private $paymentSystemTable = '{{%payment_system}}';
    private $reserveTable = '{{%reserve}}';
    private $bidTable = '{{%bid}}';
    private $bidHistory = '{{%bid_history}}';
    private $walletTable = '{{%wallet}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = ($this->db->driverName === 'mysql') ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : null;

        // create payment_system table
        $this->createTable($this->paymentSystemTable, [
            'id'         => $this->primaryKey(),
            'name'       => $this->string(50)->notNull(),
            'currency'   => "ENUM('usd', 'uah', 'rub', 'eur', 'wmx')",
            'visible'    => $this->boolean()->defaultValue(true),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ], $tableOptions );
        $this->batchInsert($this->paymentSystemTable, ['id', 'name', 'currency', 'created_at'], [
            [1, 'Webmoney WMX', 'wmx', time()],
            [2, 'Webmoney RUB', 'rub', time()],
            [3, 'Webmoney USD', 'usd', time()],
            [4, 'Webmoney UAH', 'uah', time()],
            [5, 'Webmoney EUR', 'eur', time()],
            [6, 'ВТБ 24 RUB', 'rub', time()],
            [7, 'Яндекс.Деньги RUB', 'rub', time()],
            [8, 'Сбербанк RUB', 'rub', time()],
            [9, 'Приват24 UAH', 'uah', time()],
            [10, 'РНК Банк RUB', 'rub', time()],
            [11, 'Visa/Master руб RUB', 'rub', time()]
        ]);

        // edit reserve table
        $this->truncateTable($this->reserveTable);
        $this->addColumn($this->reserveTable, 'payment_system_id', $this->integer()->notNull()->after('id'));
        $this->dropColumn($this->reserveTable, 'payment_system');
        $this->dropColumn($this->reserveTable, 'currency');
        $this->addForeignKey(
            'fk-reserve-payment_system_id',
            $this->reserveTable,
            'payment_system_id',
            $this->paymentSystemTable,
            'id',
            'CASCADE',
            'RESTRICT'
        );
        $this->batchInsert($this->reserveTable, ['payment_system_id', 'sum', 'created_at'], [
            [1, 100, time()],
            [2, 14553.4, time()],
            [3, 776, time()],
            [4, 7896, time()],
            [5, 1234.3, time()],
            [6, 8433, time()],
            [7, 5500, time()],
            [8, 3700.1, time()],
            [9, 9999, time()],
            [10, 300, time()],
            [11, 100, time()]
        ]);

        // truncate bid history table
        $this->truncateTable($this->bidHistory);

        // edit bid table
        $this->db->createCommand()->checkIntegrity(false)->execute();
        $this->truncateTable($this->bidTable);
        $this->dropColumn($this->bidTable, 'name');
        $this->dropColumn($this->bidTable, 'last_name');
        $this->dropColumn($this->bidTable, 'phone_number');
        $this->dropColumn($this->bidTable, 'email');
        $this->dropColumn($this->bidTable, 'from_payment_system');
        $this->dropColumn($this->bidTable, 'to_payment_system');
        $this->dropColumn($this->bidTable, 'from_currency');
        $this->dropColumn($this->bidTable, 'to_currency');
        $this->addColumn($this->bidTable, 'from_payment_system_id', $this->integer()->notNull()->after('id'));
        $this->addColumn($this->bidTable, 'to_payment_system_id', $this->integer()->notNull()->after('from_payment_system_id'));
        $this->addForeignKey(
            'fk-bid-from_payment_system_id',
            $this->bidTable,
            'from_payment_system_id',
            $this->paymentSystemTable,
            'id',
            'CASCADE',
            'RESTRICT'
        );
        $this->addForeignKey(
            'fk-bid-to_payment_system_id',
            $this->bidTable,
            'to_payment_system_id',
            $this->paymentSystemTable,
            'id',
            'CASCADE',
            'RESTRICT'
        );
        $this->db->createCommand()->checkIntegrity()->execute();

        //wallet table
        $this->dropColumn($this->walletTable, 'payment_system');
        $this->addColumn($this->walletTable, 'payment_system_id', $this->integer()->notNull()->after('created_by'));
        $this->addForeignKey(
            'fk-wallet-payment_system_id',
            $this->walletTable,
            'payment_system_id',
            $this->paymentSystemTable,
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
        // reserve table
        $this->dropForeignKey('fk-reserve-payment_system_id', $this->reserveTable);
        $this->dropColumn($this->reserveTable, 'payment_system_id');
        $this->addColumn($this->reserveTable, 'payment_system', "ENUM('yandex_money', 'web_money', 'tincoff', 'privat24', 'sberbank', 'qiwi')");
        $this->addColumn($this->reserveTable, 'currency', "ENUM('usd', 'uah', 'rub', 'eur')");

        // bid table
        $this->dropForeignKey('fk-bid-from_payment_system_id', $this->bidTable);
        $this->dropForeignKey('fk-bid-to_payment_system_id', $this->bidTable);
        $this->dropColumn($this->bidTable, 'from_payment_system_id');
        $this->dropColumn($this->bidTable, 'to_payment_system_id');
        $this->addColumn($this->bidTable, 'name', $this->string(20)->notNull());
        $this->addColumn($this->bidTable, 'last_name', $this->string(20)->notNull());
        $this->addColumn($this->bidTable, 'phone_number', $this->string(20)->notNull());
        $this->addColumn($this->bidTable, 'email', $this->string()->notNull());
        $this->addColumn($this->bidTable,  'from_payment_system', "ENUM('yandex_money', 'web_money', 'tincoff', 'privat24', 'sberbank', 'qiwi')");
        $this->addColumn($this->bidTable,  'to_payment_system', "ENUM('yandex_money', 'web_money', 'tincoff', 'privat24', 'sberbank', 'qiwi')");
        $this->addColumn($this->bidTable,  'from_currency', "ENUM('usd', 'uah', 'rub', 'eur')");
        $this->addColumn($this->bidTable,  'to_currency', "ENUM('usd', 'uah', 'rub', 'eur')");

        // wallet table
        $this->dropForeignKey('fk-wallet-payment_system_id', $this->walletTable);
        $this->dropColumn($this->walletTable, 'payment_system_id');
        $this->addColumn($this->walletTable, 'payment_system', "ENUM('yandex_money', 'web_money', 'tincoff', 'privat24', 'sberbank', 'qiwi')");

        // payment_system table
        $this->dropTable($this->paymentSystemTable);
    }
}
