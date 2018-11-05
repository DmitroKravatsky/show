<?php

use yii\db\Migration;

/**
 * Class m181103_113809_add_new_phone_verification_fields
 */
class m181103_113809_add_new_phone_verification_fields extends Migration
{
    private $tableName = '{{%user}}';

    public function up()
    {
        $this->addColumn($this->tableName, 'phone_verification_code', $this->smallInteger(4));
        $this->addColumn($this->tableName, 'created_phone_verification_code', $this->integer(11));
    }

    public function down()
    {
        $this->dropColumn($this->tableName, 'phone_verification_code');
        $this->dropColumn($this->tableName, 'created_phone_verification_code');
    }
}
