<?php

use yii\db\Migration;

/**
 * Class m181106_153148_add_fake_user_rivew_fields
 */
class m181106_153148_add_fake_user_rivew_fields extends Migration
{
    private $tableName = '{{%review}}';

    public function up()
    {
        $this->addColumn($this->tableName, 'avatar', $this->string(100)->after('text'));
        $this->addColumn($this->tableName, 'name', $this->string(20)->after('avatar'));
        $this->addColumn($this->tableName, 'last_name', $this->string(20)->after('name'));
    }

    public function down()
    {
        $this->dropColumn($this->tableName, 'avatar');
        $this->dropColumn($this->tableName, 'name');
        $this->dropColumn($this->tableName, 'last_name');
    }

}
