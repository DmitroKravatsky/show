<?php

use yii\db\Migration;

/**
 * Class m181102_135022_add_email_validation_field
 */
class m181102_135022_add_email_validation_field extends Migration
{
    private $tableName = '{{%user}}';

    public function up()
    {
        $this->addColumn($this->tableName, 'email_verification_code', $this->smallInteger(4));
        $this->addColumn($this->tableName, 'created_email_verification_code', $this->integer(11));
    }

    public function down()
    {
        $this->dropColumn($this->tableName, 'email_verification_code');
        $this->dropColumn($this->tableName, 'created_email_verification_code');
    }
}
