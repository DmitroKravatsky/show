<?php

use yii\db\Migration;

/**
 * Class m180123_123051_user_profile
 */
class m180123_123051_user_profile extends Migration
{
    /**
     * @var string
     */
    private $tableName= '{{%user_profile}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = ($this->db->driverName === 'mysql')
            ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : null;

        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11)->notNull(),
            'name' => $this->string(20)->notNull(),
            'last_name' => $this->string(20)->notNull(),
            'phone_number' => $this->string(20)->notNull(),
            'email' => $this->string()->notNull(),
            'avatar' => $this->string(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ], $tableOptions);

        $this->addForeignKey('fk-user_profile-user', $this->tableName, 'user_id', 'user', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk-user_profile-user', $this->tableName);
        $this->dropTable($this->tableName);
    }

}
