<?php

use yii\db\Migration;

/**
 * Class m180312_074625_add_verification_code_to_user
 */
class m180312_074625_add_verification_code_to_user extends Migration
{
    /**
     * @var string
     */
    private $tableName = '{{%user}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'verification_code', $this->integer(4));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'verification_code');
    }
}
