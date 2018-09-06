<?php

use yii\db\Migration;

/**
 * Class m180829_092959_edit_user_notifications
 */
class m180829_092959_edit_user_notifications extends Migration
{
    private $tableName = '{{%user_notifications}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->truncateTable($this->tableName);

        $this->addColumn($this->tableName, 'type', $this->smallInteger()->after('id')->notNull());
        $this->addColumn($this->tableName, 'custom_data', $this->string()->after('text'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'type');
        $this->dropColumn($this->tableName, 'custom_data');
    }
}
