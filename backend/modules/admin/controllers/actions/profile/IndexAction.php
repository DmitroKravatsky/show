<?php

namespace backend\modules\admin\controllers\actions\profile;

use backend\models\BackendUser;
use common\models\userProfile\UserProfileEntity;
use yii\base\Action;
use Yii;

class IndexAction extends Action
{
    public function run()
    {
        $user = BackendUser::findOne(Yii::$app->user->id);
        $user->scenario = BackendUser::SCENARIO_UPDATE_PASSWORD;
        $user->password = null;

        /** @var UserProfileEntity $profile */
        $profile = $user->profile;

        return $this->controller->render('index', [
            'activeTab' => 'general',
            'user' => $user,
            'profile' => $profile,
        ]);
    }
}