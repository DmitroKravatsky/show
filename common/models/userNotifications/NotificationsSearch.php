<?php

namespace common\models\userNotifications;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * NotificationsSearch represents the model behind the search form of `common\models\userNotifications\NotificationsEntity`.
 */
class NotificationsSearch extends NotificationsEntity
{
    public $dateRange;
    public $read;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['id', 'updated_at'], 'integer'],
            [['read', 'text',  'recipient',], 'safe'],
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
        $query = NotificationsEntity::find()
            ->joinWith('userNotifications')
            ->where(['user_id' => Yii::$app->user->id]);
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

        $query->andFilterWhere(['user_notifications.is_read' => $this->read])
            ->andFilterWhere(['like', 'text', $this->text]);
           /* ->andFilterWhere([
                'or',
                ['like', 'name', $this->recipient],
                ['like', 'last_name', $this->recipient]
            ]);*/

        if (!empty($this->dateRange) && strpos($this->dateRange, '-') !== false) {
            list($fromDate, $toDate) = explode(' - ', $this->dateRange);
            $query->andFilterWhere(['between', 'user_notifications.created_at', strtotime($fromDate), strtotime($toDate)]);
        }

        return $dataProvider;
    }

    public function getIsRead()
    {
        return ($this->userNotifications->is_read == true) ? true : false;
    }
}
