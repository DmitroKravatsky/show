<?php

use yii\db\Migration;

/**
 * Class m181010_091029_add_user_social
 */
class m181010_091029_add_user_social extends Migration
{
    private $userTable = '{{%user}}';
    private $userSocialTable = '{{%user_social}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // user table
        $this->delete($this->userTable, ['!=', 'source', 'native']);
        $this->alterColumn($this->userTable, 'source', "ENUM('native', 'social') DEFAULT 'native'");
        $this->dropColumn($this->userTable, 'source_id');
        $this->dropColumn($this->userTable, 'terms_condition');
        $this->addColumn($this->userTable, 'is_deleted', $this->boolean()->defaultValue(false));

        // user_social table
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->userSocialTable, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'source_id' => $this->string()->notNull(),
            'source_name' => "ENUM('fb', 'gmail')",
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ], $tableOptions );

        $this->addForeignKey(
            'fk-user_social-user_id',
            $this->userSocialTable,
            'user_id',
            $this->userTable,
            'id',
            'CASCADE',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // user table
        $this->delete($this->userTable, ['!=', 'source', 'native']);
        $this->alterColumn($this->userTable, 'source', "ENUM('fb', 'gmail', 'vk', 'native') DEFAULT 'native'");
        $this->addColumn($this->userTable, 'source_id', $this->string());
        $this->dropColumn($this->userTable, 'is_deleted');
        $this->addColumn($this->userTable, 'terms_condition', $this->boolean());

        // user_social table
        $this->dropForeignKey('fk-user_social-user_id', $this->userSocialTable);
        $this->dropTable($this->userSocialTable);
    }
}
