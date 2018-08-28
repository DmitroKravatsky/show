<?php

use yii\db\Migration;

/**
 * Class m180816_153955_language
 */
class m180816_153955_language extends Migration
{
    private $tableName = '{{%language}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $tableOptions = ($this->db->driverName === 'mysql') ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : null;
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'code' => $this->string(4),
            'name' => $this->string(100),
            'visible' => $this->boolean()->defaultValue(true)
        ], $tableOptions);

        $this->batchInsert($this->tableName, ['id', 'code', 'name', 'visible'], [
            [1, 'en', 'English', 1],
            [2, 'ru', 'Русский', 1]
        ]);
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
