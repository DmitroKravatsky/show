<?php

use yii\db\Migration;

/**
 * Class m181016_133321_add_restrict_on_delete_manager
 */
class m181016_133321_add_restrict_on_delete_manager extends Migration
{
    private $tableName = '{{%bid}}';

    public function up()
    {
        $this->addForeignKey('fk-bid-manager', $this->tableName, 'in_progress_by_manager', 'user', 'id', 'RESTRICT');
    }

    public function down()
    {
        $this->dropForeignKey('fk-bid-manager', $this->tableName);
    }
}
