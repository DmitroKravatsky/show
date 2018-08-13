<?php

namespace console\controllers;

use Yii;
use common\models\user\User;
use yii\console\Controller;

/**
 * Class RbacController
 * @package console\controllers
 */
class RbacController extends Controller
{
    /**
     * @throws \yii\base\Exception
     */
    public function actionInit()
    {
        $auth = Yii::$app->getAuthManager();
        $auth->removeAll();

        $admin = $auth->createRole(User::ROLE_ADMIN);
        $auth->add($admin);

        $manager = $auth->createRole(User::ROLE_MANAGER);
        $auth->add($manager);

        $guest = $auth->createRole(User::ROLE_GUEST);
        $auth->add($guest);

        $user = $auth->createRole(User::ROLE_USER);
        $auth->add($user);

        $auth->addChild($admin, $manager);

        $auth->assign($guest, User::DEFAULT_GUEST_ID);
        $auth->assign($admin, User::DEFAULT_ADMIN_ID);
    }
}