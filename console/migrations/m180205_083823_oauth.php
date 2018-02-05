<?php

use yii\db\Migration;

/**
 * Class m180205_083823_oauth
 */
class m180205_083823_oauth extends Migration
{
    /**
     * @var string
     */
    private $tableName = '{{%oauth}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = ($this->db->driverName === 'mysql')
            ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : null;

        $this->createTable($this->tableName, [
            'id'         => $this->primaryKey(),
            'user_id'    => $this->integer(11)->notNull(),
            'source'     => "ENUM('fb', 'gmail', 'vk')",
            'source_id'  => $this->string()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('fk-oauth-user', $this->tableName, 'user_id', 'user', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk-oauth-user', $this->tableName);
        $this->dropTable($this->tableName);
    }
}
