<?php

use yii\db\Migration;

/**
 * Class m180814_140217_add_fields_to_user
 */
class m180814_140217_add_fields_to_user extends Migration
{
    private $tableName = '{{%user}}';

    /**
     * @return bool|void
     */
    public function up()
    {
        $this->addColumn($this->tableName, 'verification_token', $this->string());
        $this->addColumn($this->tableName, 'new_email', $this->string());
    }

    /**
     * @return bool|void
     */
    public function down()
    {
        $this->dropColumn($this->tableName, 'verification_token');
        $this->dropColumn($this->tableName, 'new_email');
    }
}
