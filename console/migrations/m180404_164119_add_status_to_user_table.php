<?php

use yii\db\Migration;

/**
 * Class m180404_164119_add_status_to_user_table
 */
class m180404_164119_add_status_to_user_table extends Migration
{
    /**
     * @var string
     */
    private $tableName = '{{%user}}';


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn($this->tableName, 'status', $this->string(11));
    }

    public function down()
    {
        $this->dropColumn($this->tableName, 'status');
    }

}
