<?php

use yii\db\Migration;

/**
 * Class m180215_073401_user_notifications
 */
class m180215_073401_user_notifications extends Migration
{
    /**
     * @var string
     */
    private $tableName = '{{%user_notifications}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : null;

        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'status' => "ENUM('read', 'unread') DEFAULT 'unread'",
            'recipient_id' => $this->integer()->notNull(),
            'text' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ], $tableOptions);

        $this->addForeignKey(
            'fk-user_notifications-user',
            $this->tableName,
            'recipient_id',
            'user',
            'id',
            'CASCADE',
            'RESTRICT'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk-user_notifications-user', $this->tableName);
        $this->dropTable($this->tableName);
    }
}
