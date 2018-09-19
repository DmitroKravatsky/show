<?php

namespace backend\modules\admin\controllers\actions\profile;

use common\models\userProfile\UserProfileEntity;
use yii\base\Action;
use Yii;

class ViewAction extends Action
{
    /**
     * Views a manager profile info
     * @param $id
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function run($id)
    {
        $profileModel = UserProfileEntity::findUserProfile($id);
        return $this->controller->render('view', [
            'profileModel' => $profileModel
        ]);
    }
}