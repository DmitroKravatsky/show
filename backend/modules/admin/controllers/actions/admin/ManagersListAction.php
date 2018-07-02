<?php

namespace backend\modules\admin\controllers\actions\admin;

use yii\base\Action;
use yii\data\ActiveDataProvider;

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
        $managers = (new \yii\db\Query())
            ->select(['user.email', 'user.phone_number', 'auth_assignment.item_name',
                'auth_assignment.user_id', 'user_profile.name', 'user_profile.last_name'])
            ->from('auth_assignment')
            ->where(['auth_assignment.item_name' => 'manager'])
            ->leftJoin('user', 'auth_assignment.user_id = user.id ')
            ->leftJoin('user_profile', 'user_profile.user_id = user.id ');
        $dataProvider = new ActiveDataProvider([
            'query' => $managers,
        ]);
        return $this->controller->render($this->view, [
            'dataProvider' => $dataProvider
        ]);
    }
}