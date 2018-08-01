<?php

use yii\db\Migration;

/**
 * Class m180801_141214_admin_profile_information
 */
class m180801_141214_admin_profile_information extends Migration
{
    private $tableName = '{{%user_profile}}';

    /**
     * @return bool|void
     */
    public function up()
    {
        $this->insert($this->tableName, [
            'user_id'    => 2,
            'name'       => 'Admin',
            'last_name'  => 'Admin',
            'created_at' => time(),
            'updated_at' => time(),
        ]);
    }

    /**
     * @return bool|void
     */
    public function down()
    {
        $this->delete($this->tableName, ['user_id' => 2]);
    }
}
