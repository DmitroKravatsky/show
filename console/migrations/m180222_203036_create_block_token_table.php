<?php

use yii\db\Migration;

/**
 * Handles the creation of table `block_token`.
 */
class m180222_203036_create_block_token_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('block_token', [
            'id'         => $this->primaryKey(),
            'token'      => $this->string(255)->unique(),
            'user_id'    => $this->integer()->unsigned()->defaultValue(null),
            'created_at' => $this->integer()->unsigned()->defaultValue(null),
            'expired_at' => $this->integer()->unsigned()->defaultValue(null),
        ]);

        $this->createIndex('idx_user_token', '{{%block_token}}', 'token');
        $this->createIndex('idx_user_user_id', '{{%block_token}}', 'user_id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropIndex('idx_user_token', '{{%block_token}}');
        $this->dropIndex('idx_user_user_id', '{{%block_token}}');

        $this->dropTable('block_token');
    }
}
