<?php

namespace common\models\userNotifications\repositories;

use common\models\userNotifications\UserNotificationsEntity;
use yii\data\ArrayDataProvider;
use yii\db\BaseActiveRecord;
use yii\web\NotFoundHttpException;
use common\models\userProfile\UserProfileEntity;
use yii\web\ServerErrorHttpException;

/**
 * Class RestUserNotificationsRepository
 * @package common\models\userNotifications\repositories
 */
trait RestUserNotificationsRepository
{
    /**
     * Returns a user by User id
     *
     * @param $params array
     * @return ArrayDataProvider
     *
     * @throws ServerErrorHttpException
     */
    public function getUserNotificationsByUser(array $params): ArrayDataProvider
    {
        try {
            $userNotificationsModel = UserNotificationsEntity::find()
                ->select(['text', 'created_at'])
                ->where(['recipient_id' => \Yii::$app->user->id]);
            if (isset($params['status'])) {
                $userNotificationsModel->andWhere(['status' => $params['status']]);
            }

            $page = isset($params['page']) ? $params['page'] - 1 : 0;

            $dataProvider = new ArrayDataProvider([
                'allModels' => $userNotificationsModel->orderBy(['created_at' => SORT_DESC])->all(),
                'pagination' => [
                    'pageSize' => \Yii::$app->request->get('per-page') ?? 10,
                    'page' => $page
                ]
            ]);

            return $dataProvider;

        } catch (ServerErrorHttpException $e) {
            throw new ServerErrorHttpException('Internal server error');
        }

    }

    /**
     * Removes a notify by Notification id and User id
     *
     * @param $id int
     *
     * @return bool
     *
     * @throws NotFoundHttpException
     * @throws \yii\db\StaleObjectException
     */
    public function deleteNotify(int $id): bool
    {
        $userNotificationsModel = $this->findModel(['id' => $id, 'recipient_id' => \Yii::$app->user->id]);
        if ($userNotificationsModel->delete()) {
            return true;
        }
        return false;
    }

    /**
     * Finds a Notify by params
     *
     * @param $params array
     *
     * @return BaseActiveRecord
     *
     * @throws NotFoundHttpException
     */
    public function findModel(array $params): BaseActiveRecord
    {
        if (empty($userNotificationsModel = self::findOne($params))) {
            throw new NotFoundHttpException('Уведомление не найдено.');
        }

        return $userNotificationsModel;
    }

    /**
     * Creates a new notify
     *
     * @param $text string
     * @param $recipientId int
     *
     * @return mixed
     */
    public function addNotify(string $text, int $recipientId)
    {
        $this->setAttributes(['text' => $text, 'recipient_id' => $recipientId]);
        return $this->save();
    }

    /**
     * Generates a message for a bid with status done
     *
     * @param $params array
     *
     * @return string
     *
     * @throws NotFoundHttpException
     */
    public static function getMessageForDoneBid(array $params)
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
     * Generates a message for a bid with status rejected
     *
     * @param $params array
     *
     * @return string
     *
     * @throws NotFoundHttpException
     */
    public static function getMessageForRejectedBid(array $params)
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
     * Generates a message for a guest User
     *
     * @return string
     */
    public static function getMessageForLoginGuest(): string
    {
        return 'Вы вошли как "Гость", для доступа ко всему функционалу приложения, пройдите этап регистрации.';
    }
}