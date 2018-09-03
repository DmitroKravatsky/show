<?php

namespace backend\modules\admin\controllers\actions\manager;

use common\models\user\User;
use common\models\user\UserSearch;
use yii\base\Action;
use Yii;

/**
 * Class IndexAction
 * @package backend\modules\admin\controllers\actions\admin
 */
class IndexAction extends Action
{
    /**
     * Renders an admin panel
     * @return string
     */
    public function run()
    {
        $searchModel = new UserSearch();
        $searchModel->role = User::ROLE_MANAGER;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->controller->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }
}
