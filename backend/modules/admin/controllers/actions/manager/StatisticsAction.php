<?php

namespace backend\modules\admin\controllers\actions\manager;

use common\models\user\User;
use common\models\user\UserSearch;
use yii\base\Action;
use Yii;

/**
 * Class StatisticsAction
 * @package backend\modules\admin\controllers\actions\manager
 */
class StatisticsAction extends Action
{
    /**
     * Renders a manager's statistic
     * @param int $id
     * @return string
     */
    public function run($id)
    {
        $searchModel = new UserSearch();
        $searchModel->role = User::ROLE_MANAGER;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->controller->render('statistics', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }
}
