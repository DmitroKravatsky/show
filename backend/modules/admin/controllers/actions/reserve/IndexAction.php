<?php

namespace backend\modules\admin\controllers\actions\reserve;

use common\models\reserve\{ ReserveEntity as Reserve, ReserveEntitySearch };
use yii\{ base\Action, helpers\Json };
use Yii;

class IndexAction extends Action
{
    public function run()
    {
        $searchModel = new ReserveEntitySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (Yii::$app->request->post('hasEditable')) {
            $reserve = Reserve::findOne(Yii::$app->request->post('editableKey'));
            $reserve->sum = current(Yii::$app->request->post('ReserveEntity'))['sum'] ?? 0;
            $reserve->save(false, ['sum']);
            return  Json::encode(['output' => round($reserve->sum, 2)]);
        }

        return $this->controller->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
