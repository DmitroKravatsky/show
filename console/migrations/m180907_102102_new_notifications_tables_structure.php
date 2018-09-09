<?php

use yii\db\Migration;

/**
 * Class m180907_102102_new_notifications_tables_structure
 */
class m180907_102102_new_notifications_tables_structure extends Migration
{
    protected $notificationsTable = '{{%notifications}}';
    protected $userNotificationsTable = '{{%user_notifications}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->getTableSchema($this->userNotificationsTable, true)) {
            $this->dropTable($this->userNotificationsTable);
        }

        $this->createTable($this->notificationsTable, [
            'id' => $this->primaryKey(),
            'type' => "ENUM('new_user', 'new_bid', 'paid_by_client', 'bid_in_progress', 'bid_done', 'bid_rejected')",
            'custom_data' => $this->text()->notNull(),
            'text' => $this->text()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createTable($this->userNotificationsTable, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'notification_id' => $this->integer()->notNull(),
            'is_read' => $this->boolean(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk-user_notifications-user',
            $this->userNotificationsTable,
            'user_id',
            'user',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->addForeignKey(
            'fk-user_notifications-notifications',
            $this->userNotificationsTable,
            'notification_id',
            'notifications',
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
        echo "m180907_102102_new_notifications_tables_structure cannot be reverted.\n";

        return false;
    }
}
