<?php

namespace common\models\userNotifications;

use common\models\userProfile\UserProfileEntity;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserNotificationsSearch represents the model behind the search form of `common\models\userNotifications\UserNotificationsEntity`.
 */
class UserNotificationsSearch extends UserNotificationsEntity
{
    public $dateRange;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['id', 'created_at', 'updated_at'], 'integer'],
            [['status', 'text', 'dateRange', 'recipient_id',], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = UserNotificationsEntity::find()
            ->joinWith('recipientProfile')
            ->where(['recipient_id' => Yii::$app->user->id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $params['pageSize'] ?? Yii::$app->params['pageSize'],
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['status' => $this->status])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere([
                'or',
                ['like', 'name', $this->recipient_id],
                ['like', 'last_name', $this->recipient_id]
            ]);;

        if (!empty($this->dateRange) && strpos($this->dateRange, '-') !== false) {
            list($fromDate, $toDate) = explode(' - ', $this->dateRange);
            $query->andFilterWhere(['between', 'user_notifications.created_at', strtotime($fromDate), strtotime($toDate)]);
        }

        return $dataProvider;
    }
}
