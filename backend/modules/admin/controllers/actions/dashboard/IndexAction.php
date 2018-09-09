<?php

namespace backend\modules\admin\controllers\actions\dashboard;

use backend\modules\authorization\models\RegistrationForm;
use common\models\bid\BidEntity;
use common\models\bid\BidSearch;
use common\models\review\ReviewEntity;
use common\models\review\ReviewSearch;
use common\models\user\User;
use common\models\user\UserSearch;
use common\models\userNotifications\UserNotificationsEntity;
use common\models\userNotifications\NotificationsSearch;
use yii\base\Action;
use yii\web\ForbiddenHttpException;

/**
 * Class IndexAction
 * @package backend\modules\admin\controllers\actions\dashboard
 */
class IndexAction extends Action
{
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
            if (!$userData) {
                return $this->controller->redirect(\Yii::$app->homeUrl);
            }
            $passwordUpdateModel = new RegistrationForm();
            $passwordUpdateModel->setAttributes($userData);

            return $this->controller->render('index', ['passwordUpdateModel' => $passwordUpdateModel]);
        }

        $params = \Yii::$app->request->queryParams;
        $params['pageSize'] = 5;

        $bidSearch = new BidSearch();
        $reviewSearch = new ReviewSearch();
        $userSearch = new UserSearch();
        $userSearch->role = User::ROLE_MANAGER;
        $notificationsSearch = new NotificationsSearch();

        $bidProvider = $bidSearch->search($params);
        $reviewProvider = $reviewSearch->search($params);
        $userProvider = $userSearch->search($params);
        $notificationsProvider = $notificationsSearch->search($params);

        return $this->controller->render('index', [
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
