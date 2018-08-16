<?php

use yii\db\Migration;
use common\models\user\User;

/**
 * Class m180815_142652_add_admin_email
 */
class m180815_142652_add_admin_email extends Migration
{
    /** @var User */
    private $user;

    public function init()
    {
        parent::init();
        $this->user = User::findOne(User::DEFAULT_ADMIN_ID);
    }

    public function up()
    {
        $this->user->email = Yii::$app->params['adminEmail'];
        $this->user->save(false, ['email']);
    }

    public function down()
    {
        $this->user->email = null;
        $this->user->save(false, ['email']);
    }
}
