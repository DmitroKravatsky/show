<?php

use yii\db\Migration;

/**
 * Class m180914_111517_add_visible_to_reserve
 */
class m180914_111517_add_visible_to_reserve extends Migration
{
    private $tableName = '{{%reserve}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'visible', $this->boolean()->defaultValue(true)->after('sum'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'visible');
    }
}
