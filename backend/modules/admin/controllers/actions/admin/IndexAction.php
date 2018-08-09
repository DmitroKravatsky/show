<?php

namespace backend\modules\admin\controllers\actions\admin;

use backend\modules\authorization\models\RegistrationForm;
use common\models\bid\BidEntity;
use common\models\bid\BidSearch;
use common\models\review\ReviewEntity;
use common\models\review\ReviewSearch;
use common\models\user\User;
use common\models\user\UserSearch;
use common\models\userNotifications\UserNotificationsEntity;
use common\models\userNotifications\UserNotificationsSearch;
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
     *
     * @param null $inviteCode
     * @return string
     */
    public function run($inviteCode = null)
    {
        if ($inviteCode) {
            $userData = User::find()->select(['email'])->where(['user.invite_code' => $inviteCode])->one();
            $passwordUpdateModel = new RegistrationForm();
            $passwordUpdateModel->setAttributes($userData);

            return $this->controller->render($this->view, ['passwordUpdateModel' => $passwordUpdateModel]);
        }

        $params = \Yii::$app->request->queryParams;
        $params['pageSize'] = 5;

        $bidSearch = new BidSearch();
        $reviewSearch = new ReviewSearch();
        $userSearch = new UserSearch();
        $notificationsSearch = new UserNotificationsSearch();

        $bidProvider = $bidSearch->search($params);
        $reviewProvider = $reviewSearch->search($params);
        $userProvider = $userSearch->search($params);
        $notificationsProvider = $notificationsSearch->search($params);

        return $this->controller->render($this->view, [
            'countBids'             => BidEntity::find()->count(),
            'countManagers'         => User::getCountManagers(),
            'countReviews'          => ReviewEntity::find()->count(),
            'countNotifications'    => UserNotificationsEntity::getCountUnreadNotificationsByRecipient(),
            'bidSearch'             => $bidSearch,
            'bidProvider'           => $bidProvider,
            'reviewSearch'          => $reviewSearch,
            'reviewProvider'        => $reviewProvider,
            'userSearch'            => $userSearch,
            'userProvider'          => $userProvider,
            'notificationsSearch'   => $notificationsSearch,
            'notificationsProvider' => $notificationsProvider,
        ]);
    }
}
