<?php

namespace backend\modules\admin\controllers\actions\manager;

use backend\models\BackendUser;
use backend\modules\admin\controllers\ManagerController;
use yii\base\Action;
use yii\helpers\Url;
use yii\web\ErrorHandler;
use yii\web\ServerErrorHttpException;
use Yii;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class UpdatePasswordAction
 * @package backend\modules\admin\controllers\actions\admin
 */
class UpdatePasswordAction extends Action
{
    /** @var ManagerController */
    public $controller;

    /**
     * Update manager password
     * @return mixed
     * @throws ServerErrorHttpException
     * @throws \Throwable
     * @throws \yii\web\NotFoundHttpException
     */
    public function run()
    {
        $params = Yii::$app->request->post()['BackendUser'];
        $managerModel = BackendUser::findByEmail($params['email']);
        $managerModel->setScenario(BackendUser::SCENARIO_UPDATE_PASSWORD_BY_ADMIN);

        try {
            $managerModel->setAttributes(
                [
                    'newPassword' => $params['newPassword'],
                    'repeatPassword' => $params['repeatPassword'],
                ]
            );
            $managerModel->save();
            Yii::$app->session->setFlash('success', Yii::t('app', 'Password was successfully updated.'));
        } catch (\Exception $e) {
            Yii::error(ErrorHandler::convertExceptionToString($e));
            Yii::$app->session->setFlash('error', Yii::t('app', 'Something wrong, please try again later.'));
            throw new ServerErrorHttpException();
        }
        return $this->controller->redirect(Url::to(\Yii::$app->request->referrer));
    }
}
