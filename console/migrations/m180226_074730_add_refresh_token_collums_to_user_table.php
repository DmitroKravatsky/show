<?php

use yii\db\Migration;

/**
 * Class m180226_074730_add_refresh_token_collums_to_user_table
 */
class m180226_074730_add_refresh_token_collums_to_user_table extends Migration
{

    public function up()
    {
        $this->addColumn('user', 'refresh_token', $this->string(100));
        $this->addColumn('user', 'created_refresh_token', $this->integer(11));


        $this->createIndex('idx-user-refresh_token', 'user', 'refresh_token');
    }

    public function down()
    {
        $this->dropColumn('user', 'refresh_token');
        $this->dropColumn('user', 'created_refresh_token');

    }

}
