<?php

use yii\db\Migration;

/**
 * Class m180213_135137_review
 */
class m180213_135137_review extends Migration
{
    /**
     * @var string
     */
    private $tableName = '{{%review}}';
    
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = ($this->db->driverName === 'mysql') ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : null;

        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'created_by' => $this->integer()->notNull(),
            'text' => $this->text()->notNull(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ], $tableOptions);

        $this->addForeignKey('fk-review-user', $this->tableName, 'created_by', 'user', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk-review-user');
        $this->dropTable($this->tableName);
    }
}
