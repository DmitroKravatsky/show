<?php

namespace backend\modules\admin\controllers\actions\profile;

use backend\models\BackendUser;
use common\models\userProfile\UserProfileEntity;
use yii\base\Action;
use Yii;
use yii\web\ErrorHandler;

class UpdateAvatarAction extends Action
{
    /**
     * @return \yii\web\Response
     */
    public function run()
    {
        $user = BackendUser::findOne(Yii::$app->user->id);

        /** @var UserProfileEntity $profile */
        $profile = $user->profile;

        try {
            if ($profile->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Image successfully uploaded.'));
            }
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'An error occurred while loading the image.'));
            ErrorHandler::convertExceptionToString($e);
        }

        return $this->controller->redirect('index');
    }
}
