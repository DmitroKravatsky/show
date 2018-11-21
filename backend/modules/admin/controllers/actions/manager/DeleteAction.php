<?php

namespace backend\modules\admin\controllers\actions\manager;

use Yii;
use backend\models\BackendUser;
use backend\modules\admin\controllers\ManagerController;
use yii\base\Action;
use yii\helpers\Url;
use common\models\user\UserSearch;
use common\models\user\User;

/**
 * Class DeleteAction
 * @package backend\modules\admin\controllers\actions\admin
 */
class DeleteAction extends Action
{
    /** @var ManagerController */
    public $controller;

    public function run($userId)
    {
        if (BackendUser::managerHasBidInProgress($userId)) {
            Yii::$app->session->setFlash('error',
                Yii::t('app', 'Manager has at least one bid in process. Deletion is not allowed')
            );
            return $this->controller->redirect(Url::to(Yii::$app->request->referrer));
        }

        if (Yii::$app->request->isAjax) {
            $this->controller->findModel($userId)->delete();
        }

        $searchModel = new UserSearch();
        $searchModel->role = User::ROLE_MANAGER;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->controller->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }
}
