<?php

namespace backend\modules\admin\controllers\actions\user;

use backend\modules\admin\controllers\UserController;
use common\models\user\User;
use yii\base\Action;
use yii\helpers\Url;
use Yii;
use yii\web\Response;

class UpdateStatusAction extends Action
{
    /** @var  UserController */
    public $controller;

    /**
     * @return array
     */
    public function run()
    {
        $bodyParams = Yii::$app->request->getBodyParams();
        $id = $bodyParams['id'];
        $status = $bodyParams['status'];
        Yii::$app->language = $bodyParams['language'];

        Yii::$app->response->format = Response::FORMAT_JSON;

        $user = $this->controller->findModel($id);

        $user->status = $status;
        if ($user->save(true, ['status'])) {
            return [
                'status' => 200,
                'message' => Yii::t('app', 'Status successfully updated.'),
                'isAdmin' => Yii::$app->user->can(User::ROLE_ADMIN),
                'userStatus' => User::getStatusValue($user->status),
            ];
        }

        Yii::$app->response->setStatusCode(500);
        return  ['message' => Yii::t('app', 'Something wrong, please try again later.')];
    }
}
