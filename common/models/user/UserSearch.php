<?php

namespace common\models\user;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserSearch represents the model behind the search form of `common\models\user\User`.
 */
class UserSearch extends User
{
    public $dateRange;
    public $lastLoginRange;
    public $role;

    /**
     * String  of $name and $last_name
     * @var $full_name
     */
    public $full_name;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'status_online', 'accept_invite',], 'integer'],
            [['email', 'phone_number', 'full_name', 'dateRange', 'invite_code_status', 'lastLoginRange', 'status',], 'safe'],
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
        $query = User::find()
            ->leftJoin('auth_assignment', 'auth_assignment.user_id = user.id')
            ->leftJoin('user_profile', 'user_profile.user_id = user.id ');
        if ($this->role != null) {
            $query->andWhere(['auth_assignment.item_name' => $this->role]);
        }
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

        $query->andFilterWhere([
            static::tableName() . '.id' => $this->id,
            'invite_code_status' => $this->invite_code_status,
            'status' => $this->status,
            'status_online' => $this->status_online,
            'accept_invite' => $this->accept_invite,
            'created_at' => $this->created_at,
        ]);

        if ($this->role) {
            $query->andFilterWhere(['item_name' => $this->role]);
        }

        $query->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone_number', $this->phone_number])
            ->andFilterWhere([
                'or',
                ['like', 'user_profile.name', $this->full_name],
                ['like', 'user_profile.last_name', $this->full_name]
            ]);

        if (!empty($this->time_range) && strpos($this->time_range, '-') !== false) {
            list($fromDate, $toDate) = explode(' - ', $this->time_range);
            $query->andFilterWhere(['between', 'time', strtotime($fromDate), strtotime($toDate)]);
        }

        if (!empty($this->dateRange) && strpos($this->dateRange, '-') !== false) {
            list($fromDate, $toDate) = explode(' - ', $this->dateRange);
            $query->andFilterWhere(['between', 'user.created_at', strtotime($fromDate), strtotime($toDate)]);
        }

        if (!empty($this->lastLoginRange) && strpos($this->lastLoginRange, '-') !== false) {
            list($fromDate, $toDate) = explode(' - ', $this->lastLoginRange);
            $query->andFilterWhere(['between', 'user.last_login', strtotime($fromDate), strtotime($toDate)]);
        }

        return $dataProvider;
    }
}
