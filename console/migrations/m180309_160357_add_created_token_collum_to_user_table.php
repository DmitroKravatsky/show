<?php

use yii\db\Migration;

/**
 * Class m180309_160357_add_created_token_collum_to_user_table
 */
class m180309_160357_add_created_token_collum_to_user_table extends Migration
{
    public function up()
    {
        $this->addColumn('user', 'created_refresh_token', $this->integer(11));

    }

    public function down()
    {
        $this->dropColumn('user', 'created_refresh_token');

    }

}
