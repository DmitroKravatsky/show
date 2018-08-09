<?php

namespace backend\modules\admin\controllers\actions\notifications;

use common\models\userNotifications\UserNotificationsSearch;
use yii\base\Action;
use Yii;

class IndexAction extends Action
{
    /**
     * @return string
     */
    public function run(): string
    {
        $searchModel = new UserNotificationsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->controller->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
