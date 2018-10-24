<?php

use yii\db\Migration;

/**
 * Class m181024_073451_exchange_rates
 */
class m181024_073451_exchange_rates extends Migration
{
    private $exchangeRatesTable = '{{%exchange_rates}}';
    private $paymentSystemTable = '{{%payment_system}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = ($this->db->driverName === 'mysql') ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : null;

        $this->createTable($this->exchangeRatesTable, [
            'id' => $this->primaryKey(),
            'from_payment_system_id' => $this->integer()->notNull(),
            'to_payment_system_id' => $this->integer()->notNull(),
            'value' => $this->float()->notNull(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ], $tableOptions);

        $this->addForeignKey(
            'fk-exchange_rates-from_payment_system_id',
            $this->exchangeRatesTable,
            'from_payment_system_id',
            $this->paymentSystemTable,
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->addForeignKey(
            'fk-exchange_rates-to_payment_system_id',
            $this->exchangeRatesTable,
            'to_payment_system_id',
            $this->paymentSystemTable,
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->batchInsert($this->exchangeRatesTable, ['from_payment_system_id', 'to_payment_system_id', 'value', 'created_at'], [
            [1, 2, 428.38, time()],
            [1, 3, 28.1494, time()],
            [1, 4, 20, time()],
            [1, 5, 5.99, time()],
            [1, 6, 27.2294, time()],
            [1, 7, 29.1496, time()],
            [1, 8, 31.4344, time()],
            [1, 9, 28.1344, time()],
            [1, 10, 33.634, time()],
            [1, 11, 32, time()],
            [2, 3, 65.31, time()],
            [2, 4, 2.23, time()],
            [2, 5, 73, time()],
            [2, 6, 1.1, time()],
            [2, 7, 2, time()],
            [2, 8, 2.2, time()],
            [2, 9, 1.3, time()],
            [2, 10, 1.2, time()],
            [2, 11, 1.1, time()],
            [3, 4, 66, time()],
            [3, 5, 72, time()],
            [3, 6, 1.1, time()],
            [3, 7, 1.1, time()],
            [3, 8, 1.1, time()],
            [3, 9, 1.1, time()],
            [3, 10, 1.1, time()],
            [3, 11, 1.1, time()],
            [4, 5, 70, time()],
            [4, 6, 1.1, time()],
            [4, 7, 1.1, time()],
            [4, 8, 1.1, time()],
            [4, 9, 2.3, time()],
            [4, 10, 1.1, time()],
            [4, 11, 1.1, time()],
            [5, 6, 71, time()],
            [5, 7, 1.1, time()],
            [5, 8, 1.1, time()],
            [5, 9, 2.23, time()],
            [5, 10, 1.1, time()],
            [5, 11, 1.1, time()],
            [6, 7, 1.1, time()],
            [6, 8, 1.1, time()],
            [6, 9, 2.2, time()],
            [6, 10, 1.1, time()],
            [6, 11, 1.1, time()],
            [7, 8, 1.1, time()],
            [7, 9, 2.23, time()],
            [7, 10, 1.1, time()],
            [7, 11, 1.1, time()],
            [8, 9, 2, time()],
            [8, 10, 1.1, time()],
            [8, 11, 1.1, time()],
            [9, 10, 0.23, time()],
            [9, 11, 1.1, time()],
            [10, 11, 1.1, time()],
            [11, 10, 1.1, time()],
            [11, 9, 1.1, time()],
            [11, 8, 1.1, time()],
            [11, 7, 1.1, time()],
            [11, 6, 1.1, time()],
            [11, 5, 0.21, time()],
            [11, 4, 1.1, time()],
            [11, 3, 0.3, time()],
            [11, 2, 1.1, time()],
            [11, 1, 0.032, time()],
            [10, 9, 0.032, time()],
            [10, 8, 0.032, time()],
            [10, 7, 0.032, time()],
            [10, 6, 0.032, time()],
            [10, 5, 0.032, time()],
            [10, 4, 0.032, time()],
            [10, 3, 0.032, time()],
            [10, 2, 0.032, time()],
            [10, 1, 0.032, time()],
            [9, 8, 0.032, time()],
            [9, 7, 0.032, time()],
            [9, 6, 0.032, time()],
            [9, 5, 0.032, time()],
            [9, 4, 0.032, time()],
            [9, 3, 0.032, time()],
            [9, 2, 0.032, time()],
            [9, 1, 0.032, time()],
            [8, 7, 0.032, time()],
            [8, 6, 0.032, time()],
            [8, 5, 0.032, time()],
            [8, 4, 0.032, time()],
            [8, 3, 0.032, time()],
            [8, 2, 0.032, time()],
            [8, 1, 0.032, time()],
            [7, 6, 0.032, time()],
            [7, 5, 0.032, time()],
            [7, 4, 0.032, time()],
            [7, 3, 0.032, time()],
            [7, 2, 0.032, time()],
            [7, 1, 0.032, time()],
            [6, 5, 0.032, time()],
            [6, 4, 0.032, time()],
            [6, 3, 0.032, time()],
            [6, 2, 0.032, time()],
            [6, 1, 0.032, time()],
            [5, 4, 0.032, time()],
            [5, 3, 0.032, time()],
            [5, 2, 0.032, time()],
            [5, 1, 0.032, time()],
            [4, 3, 0.032, time()],
            [4, 2, 0.032, time()],
            [4, 1, 0.032, time()],
            [3, 2, 0.032, time()],
            [3, 1, 0.032, time()],
            [2, 1, 0.0032, time()],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-exchange_rates-from_payment_system_id', $this->exchangeRatesTable);
        $this->dropForeignKey('fk-exchange_rates-to_payment_system_id', $this->exchangeRatesTable);
        $this->dropTable($this->exchangeRatesTable);
    }
}
