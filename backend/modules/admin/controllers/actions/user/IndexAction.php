<?php

namespace backend\modules\admin\controllers\actions\user;

use common\models\user\User;
use common\models\user\UserSearch;
use yii\base\Action;
use Yii;

/**
 * Class IndexAction
 */
class IndexAction extends Action
{
    /**
     * Returns user list
     * @return string
     */
    public function run()
    {
        $searchModel = new UserSearch();
        $searchModel->role = User::ROLE_USER;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->controller->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }
}
