<?php

namespace backend\modules\admin\controllers\actions\user;

use backend\modules\admin\controllers\UserController;
use yii\base\Action;
use common\models\bid\BidSearch;
use Yii;

class ViewAction extends Action
{
    /** @var UserController */
    public $controller;

    /**
     * Displays a single UserBackend model.
     *
     * @param $id
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function run($id): string
    {
        $user = $this->controller->findModel($id);
        $searchModel = new BidSearch();
        $queryParams = Yii::$app->request->queryParams;
        $queryParams['BidSearch']['created_by'] = $id;
        $dataProvider = $searchModel->search($queryParams);

        return $this->controller->render('view', [
            'user'         => $user,
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
        ]);
    }
}
