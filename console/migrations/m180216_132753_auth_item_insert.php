<?php

use yii\db\Migration;

/**
 * Class m180216_132753_auth_item_insert
 */
class m180216_132753_auth_item_insert extends Migration
{
    /**
     * @var string
     */
    private $tableName = '{{%auth_item}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->insert($this->tableName,
            [
                'name' => 'user',
                'type' => 1,
                'description' => 'User Role',
                'created_at' => time(),
                'updated_at' => time()
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->truncateTable($this->tableName);
    }
}
