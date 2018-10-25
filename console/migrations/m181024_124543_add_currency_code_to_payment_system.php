<?php

use yii\db\Migration;

/**
 * Class m181024_124543_add_currency_code_to_payment_system
 */
class m181024_124543_add_currency_code_to_payment_system extends Migration
{
    private $tableName = '{{%payment_system}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'currency_code', $this->string(50)->notNull());
        $this->update($this->tableName, ['currency_code' => 'WMX'], ['id' => 1]);
        $this->update($this->tableName, ['currency_code' => 'WMR'], ['id' => 2]);
        $this->update($this->tableName, ['currency_code' => 'WMZ'], ['id' => 3]);
        $this->update($this->tableName, ['currency_code' => 'WMU'], ['id' => 4]);
        $this->update($this->tableName, ['currency_code' => 'WME'], ['id' => 5]);
        $this->update($this->tableName, ['currency_code' => 'TBRUB'], ['id' => 6]);
        $this->update($this->tableName, ['currency_code' => 'YAMRUB'], ['id' => 7]);
        $this->update($this->tableName, ['currency_code' => 'SBERRUB'], ['id' => 8]);
        $this->update($this->tableName, ['currency_code' => 'P24UAH'], ['id' => 9]);
        $this->update($this->tableName, ['currency_code' => 'RNKBRUB'], ['id' => 10]);
        $this->update($this->tableName, ['currency_code' => 'CARDRUB'], ['id' => 11]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'currency_code');
    }
}
