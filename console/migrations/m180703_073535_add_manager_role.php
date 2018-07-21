<?php

use yii\db\Migration;

/**
 * Class m180703_073535_add_manager_role
 */
class m180703_073535_add_manager_role extends Migration
{
    public function up()
    {
        $this->insert('{{%auth_item}}',
            [
                'name' => 'manager',
                'type' => 1,
                'description' => 'Manager Role',
                'created_at' => time(),
                'updated_at' => time()
            ]
        );
    }

    public function down()
    {
        $this->delete('{{%auth_item}}', ['name' => 'manager']);
    }
}
