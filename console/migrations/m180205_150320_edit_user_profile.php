<?php

use yii\db\Migration;

/**
 * Class m180205_150320_edit_user_profile
 */
class m180205_150320_edit_user_profile extends Migration
{
    /**
     * @var string
     */
    private $tableName = '{{%user_profile}}';
    
    public function up()
    {
        $this->dropColumn($this->tableName, 'email');
        $this->dropColumn($this->tableName, 'phone_number');
    }

    public function down()
    {
        $this->addColumn($this->tableName, 'email', $this->string()->notNull());
        $this->addColumn($this->tableName, 'phone_number', $this->string(20)->notNull());
    }
}
