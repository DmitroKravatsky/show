<?php

use yii\db\Migration;

/**
 * Class m181121_161532_add_visible_to_review
 */
class m181121_161532_add_visible_to_review extends Migration
{
    private $tableName = '{{%review}}';

    public function up()
    {
        $this->addColumn($this->tableName, 'visible', $this->boolean()->defaultValue(true));
    }

    public function down()
    {
        $this->dropColumn($this->tableName, 'visible');
    }
}
