<?php

use yii\db\Migration;

/**
 * Class m180506_073504_add_invite_link_field
 */
class m180506_073504_add_invite_link_field extends Migration
{

    public function up()
    {
        $this->addColumn('user', 'invite_code', $this->string(32));
        $this->addColumn('user', 'invite_code_status',"ENUM('ACTIVE', 'INACTIVE') DEFAULT 'ACTIVE'");
    }

    public function down()
    {
        $this->dropColumn('user', 'invite_code');
        $this->dropColumn('user', 'invite_code_status');
    }

}
