<?php

namespace backend\modules\admin\controllers\actions\reserve;

use Yii;
use backend\modules\admin\controllers\ReserveController;
use yii\base\Action;
use common\models\reserve\ReserveEntitySearch;

class DeleteAction extends Action
{
    /** @var ReserveController */
    public $controller;

    public function run($id)
    {
        if (Yii::$app->request->isAjax) {
            $this->controller->findModel($id)->delete();
        }

        $searchModel = new ReserveEntitySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->controller->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
