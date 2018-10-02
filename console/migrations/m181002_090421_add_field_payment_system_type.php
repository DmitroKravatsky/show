<?php

use yii\db\Migration;

/**
 * Class m181002_090421_add_field_payment_system_type
 */
class m181002_090421_add_field_payment_system_type extends Migration
{
    public $tableName  = '{{%payment_system}}';
    public $columnName = '{{%payment_system_type}}';

    public function up()
    {
        $this->addColumn($this->tableName, $this->columnName, "ENUM('credit_card', 'online_wallet') NOT NULL");
    }

    public function down()
    {
        $this->dropColumn($this->tableName, $this->columnName);
    }

}
