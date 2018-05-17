<?php

namespace backend\modules\admin\controllers\actions\admin;

use backend\modules\authorization\models\RegistrationForm;
use common\models\user\User;
use yii\base\Action;

/**
 * Class IndexAction
 * @package backend\modules\admin\controllers\actions\admin
 */
class IndexAction extends Action
{
    public $view = '@backend/modules/admin/views/admin/index';

    /**
     * Renders an admin panel
     * @return string
     */
    public function run($invitedByLink = false)
    {
        if ($invitedByLink) {
            $userData = User::find()
                ->select(['user.id', 'user.email', 'user_profile.name', 'user_profile.last_name'])
                ->leftJoin('user_profile', 'user_profile.user_id = user.id')
                ->where(['user.id' => 125])
                ->leftJoin('auth_assignment', 'user_id = user.id')
                ->all();

            var_dump($userData); exit;
              /*  ->leftJoin('product_feedback', 'product_feedback.product_id = product.id')
                ->joinWith('images')
                ->where(['product.user_id' => $userId])->joinWith('prices')
                ->orderBy(['product.created_at' => SORT_DESC, 'availability' => SORT_DESC, 'count_report' => SORT_DESC])
                ->groupBy('product.id'););*/
            $passwordUpdateModel = new RegistrationForm();
            return $this->controller->render($this->view, ['passwordUpdateModel' => $passwordUpdateModel]);
        }
        return $this->controller->render($this->view);
    }
}