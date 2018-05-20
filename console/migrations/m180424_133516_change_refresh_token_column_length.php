<?php

use yii\db\Migration;

/**
 * Class m180424_133516_change_refresh_token_column_length
 */
class m180424_133516_change_refresh_token_column_length extends Migration
{
    public function up()
    {
        $this->alterColumn('user', 'refresh_token', 'varchar(255)');
    }

    public function down()
    {
        $this->alterColumn('user', 'refresh_token', 'varchar(100)');
    }

}
