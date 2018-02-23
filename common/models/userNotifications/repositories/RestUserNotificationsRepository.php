<?php

namespace common\models\userNotifications\repositories;

use common\models\userNotifications\UserNotificationsEntity;
use yii\data\ArrayDataProvider;
use Yii;
use yii\db\BaseActiveRecord;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use common\models\userProfile\UserProfileEntity;

/**
 * Class RestUserNotificationsRepository
 * @package common\models\userNotifications\repositories
 */
trait RestUserNotificationsRepository
{
    /**
     * @param $userId
     * @return ArrayDataProvider
     */
    public function getUserNotificationsByUser($userId): ArrayDataProvider
    {
        $userNotificationsModel = UserNotificationsEntity::find()
            ->select(['text', 'created_at'])
            ->where(['recipient_id' => $userId])
            ->orderBy(['created_at' => SORT_DESC])
            ->asArray()
            ->all();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $userNotificationsModel,
            'pagination' => [
                'pageSize' => Yii::$app->request->get('per-page') ?? 10
            ]
        ]);

        return $dataProvider;
    }

    /**
     * @param $id
     * @return bool
     * @throws NotFoundHttpException
     * @throws \yii\db\StaleObjectException
     */
    public function deleteNotify($id): bool
    {
        $userNotificationsModel = $this->findModel(['id' => (int) $id, 'recipient_id' => Yii::$app->user->id]);
        if ($userNotificationsModel->delete()) {
            return true;
        }
        return false;
    }

    /**
     * @param $params
     * @return BaseActiveRecord
     * @throws NotFoundHttpException
     */
    public function findModel($params): BaseActiveRecord
    {
        if (empty($userNotificationsModel = self::findOne($params))) {
            throw new NotFoundHttpException('Уведомление не найдено.');
        }

        return $userNotificationsModel;
    }

    /**
     * @param $text
     * @param $recipientId
     * @return mixed
     */
    public function addNotify($text, int $recipientId)
    {
        $this->setAttributes(['text' => $text, 'recipient_id' => $recipientId]);
        return $this->save();
    }

    /**
     * @param $params
     * @return string
     * @throws NotFoundHttpException
     */
    public static function getMessageForDoneBid($params)
    {
        $fullName = UserProfileEntity::getFullName($params['created_by']);
        $sum = $params['to_sum'];
        $currency = $params['to_currency'];
        $to_wallet = $params['to_wallet'];

        $message = <<<EOT
{$fullName}, Ваша заявка успешно обработана. Перевод на карту {$sum} {$currency} через приложение Wallet. Получатель:
Карта/счет {$to_wallet}
EOT;
    
        return $message;
    }

    /**
     * @param $params
     * @return string
     * @throws NotFoundHttpException
     */
    public static function getMessageForRejectedBid($params)
    {
        $fullName = UserProfileEntity::getFullName($params['created_by']);
        $sum = $params['to_sum'];
        $currency = $params['to_currency'];
        $to_wallet = $params['to_wallet'];

        $message = <<<EOT
{$fullName}, Ваша заявка не выполнена. Перевод на карту {$sum} {$currency} через приложение Wallet. Получатель:
Карта/счет {$to_wallet}
EOT;

        return $message;
    }

    /**
     * @return string
     */
    public static function getMessageForLoginGuest(): string
    {
        return 'Вы вошли как "Гость", для доступа ко всему функционалу приложения, пройдите этап регистрации.';
    }
}