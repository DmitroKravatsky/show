<?php

namespace backend\modules\admin\controllers\actions\admin;

use common\models\user\User;
use common\models\user\UserSearch;
use yii\base\Action;
use Yii;

/**
 * Class ManagersListAction
 * @package backend\modules\admin\controllers\actions\admin
 */
class ManagersListAction extends Action
{
    public $view = '@backend/modules/admin/views/admin/managers-list';

    /**
     * Renders an admin panel
     * @return string
     */
    public function run()
    {
        $searchModel = new UserSearch();
        $searchModel->role = User::ROLE_MANAGER;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->controller->render($this->view, [
            'dataProvider' => $dataProvider
        ]);
    }
}