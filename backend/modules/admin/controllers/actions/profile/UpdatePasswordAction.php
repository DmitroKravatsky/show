<?php

namespace backend\modules\admin\controllers\actions\profile;

use backend\models\BackendUser;
use common\models\userProfile\UserProfileEntity;
use yii\base\Action;
use Yii;
use yii\widgets\ActiveForm;
use yii\web\Response;

class UpdatePasswordAction extends Action
{
    /**
     * @return array|string|Response
     */
    public function run()
    {
        $user = BackendUser::findOne(Yii::$app->user->id);
        $user->scenario = BackendUser::SCENARIO_UPDATE_PASSWORD;

        /** @var UserProfileEntity $profile */
        $profile = $user->profile;

        if (Yii::$app->request->isAjax && $user->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($user);
        }

        if ($user->load(Yii::$app->request->post()) && $user->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Password was successfully updated.'));
            $user->password = null;
            $user->currentPassword = null;
            $user->repeatPassword = null;
        }

        return $this->controller->render('index', [
            'activeTab' => 'password',
            'user' => $user,
            'profile' => $profile,
        ]);
    }
}