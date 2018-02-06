<?php

use yii\db\Migration;

/**
 * Class m180205_150232_edit_user
 */
class m180205_150232_edit_user extends Migration
{
    /**
     * @var string
     */
    private $tableName = '{{%user}}';

    /** @inheritdoc */
    public function up()
    {
        $this->dropColumn($this->tableName, 'status');
        $this->dropColumn($this->tableName, 'username');
        $this->alterColumn($this->tableName, 'email', $this->string()->unique());
        $this->addColumn($this->tableName, 'phone_number', $this->string(20));
        $this->addColumn($this->tableName, 'source', "ENUM('fb', 'gmail', 'vk', 'native') DEFAULT 'native'");
        $this->addColumn($this->tableName,  'source_id', $this->string()->notNull());
    }

    /** @inheritdoc */
    public function down()
    {
        $this->addColumn($this->tableName, 'status', $this->smallInteger()->notNull()->defaultValue(10));
        $this->addColumn($this->tableName, 'username', $this->string()->notNull()->unique());
        $this->alterColumn($this->tableName, 'email', $this->string()->notNull()->unique());
        $this->dropColumn($this->tableName, 'phone_number');
        $this->dropColumn($this->tableName, 'source');
        $this->dropColumn($this->tableName, 'source_id');
    }
}
