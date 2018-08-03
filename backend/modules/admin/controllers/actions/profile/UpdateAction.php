<?php

namespace backend\modules\admin\controllers\actions\profile;

use backend\models\BackendUser;
use common\models\userProfile\UserProfileEntity;
use yii\base\Action;
use Yii;

class UpdateAction extends Action
{
    /**
     * @return string|\yii\web\Response
     */
    public function run()
    {
        $user = BackendUser::findOne(Yii::$app->user->id);
        $user->scenario = BackendUser::SCENARIO_UPDATE_PASSWORD;

        /** @var UserProfileEntity $profile */
        $profile = $user->profile;

        if ($profile->load(Yii::$app->request->post()) && $profile->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'The profile was successfully updated.'));
            return $this->controller->redirect(['index']);
        }

        return $this->controller->render('index', [
            'activeTab' => 'general',
            'user' => $user,
            'profile' => $profile,
        ]);
    }
}
