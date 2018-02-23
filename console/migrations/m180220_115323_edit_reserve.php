<?php

use yii\db\Migration;

/**
 * Class m180220_115323_edit_reserve
 */
class m180220_115323_edit_reserve extends Migration
{
    /**
     * @var string
     */
    private $tableName = '{{%reserve}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->dropColumn($this->tableName, 'image');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->addColumn($this->tableName, 'image', $this->string());
    }
}
