<?php

use yii\db\Migration;

/**
 * Class m180218_080535_add_recovery_collums_to_user
 */
class m180218_080535_add_recovery_collums_to_user extends Migration
{
    public function up()
    {
        $this->addColumn('user','recovery_code',$this->integer(4));
        $this->addColumn('user','created_recovery_code',$this->integer());
    }

    public function down()
    {
      $this->dropColumn('user','recovery_code');
      $this->dropColumn('user','created_recovery_code');

    }

}
