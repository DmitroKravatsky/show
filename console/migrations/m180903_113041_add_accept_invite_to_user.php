<?php

use yii\db\Migration;

/**
 * Class m180903_113041_add_accept_invite_to_user
 */
class m180903_113041_add_accept_invite_to_user extends Migration
{
    private $tableName = '{{%user}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'accept_invite', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'accept_invite');
    }
}
