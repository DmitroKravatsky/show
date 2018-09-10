<?php

namespace common\models\userNotifications;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserNotificationsSearch represents the model behind
 * the search form of `common\models\userNotifications\UserNotificationsEntity`.
 */
class UserNotificationsSearch extends UserNotificationsEntity
{
    public $dateRange;
    public $text;
    public $full_name;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['id', 'user_id', 'notification_id', 'created_at'], 'integer'],
            [['is_read', 'text', 'full_name', 'dateRange'], 'safe'],
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
            ->joinWith('notification')
            ->joinWith('userProfile');
        $dataProvider = new ActiveDataProvider([
            'query' => $query->orderBy(['created_at' => SORT_DESC]),
            'pagination' => [
                'pageSize' => $params['pageSize'] ?? Yii::$app->params['pageSize'],
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['is_read' => $this->is_read])
            ->andFilterWhere(['like', 'notifications.text', $this->text])
            ->andFilterWhere([
                'or',
                ['like', 'user_profile.name', $this->full_name],
                ['like', 'user_profile.last_name', $this->full_name]
            ]);

        if (!empty($this->dateRange) && strpos($this->dateRange, '-') !== false) {
            list($fromDate, $toDate) = explode(' - ', $this->dateRange);
            $query->andFilterWhere(['between', 'notifications.created_at', strtotime($fromDate), strtotime($toDate)]);
        }

        return $dataProvider;
    }

}
