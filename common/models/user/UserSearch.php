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
            [['id', 'created_at',], 'integer'],
            [['email', 'phone_number', 'status', 'full_name', 'dateRange',], 'safe'],
            ['status', 'in', 'range' => [self::STATUS_VERIFIED, self::STATUS_UNVERIFIED, self::STATUS_BANNED]],
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
            ->where(['auth_assignment.item_name' => 'manager'])
            ->leftJoin('user_profile', 'user_profile.user_id = user.id ');

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

        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ]);

        if ($this->role) {
            $query->andFilterWhere(['item_name' => $this->role]);
        }

        $query->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone_number', $this->phone_number])
            ->andFilterWhere(['or',['like', 'user_profile.name', $this->full_name],['like', 'user_profile.last_name', $this->full_name]]);

        if (!empty($this->time_range) && strpos($this->time_range, '-') !== false) {
            list($fromDate, $toDate) = explode(' - ', $this->time_range);
            $query->andFilterWhere(['between', 'time', strtotime($fromDate), strtotime($toDate)]);
        }

        if (!empty($this->dateRange) && strpos($this->dateRange, '-') !== false) {
            list($fromDate, $toDate) = explode(' - ', $this->dateRange);
            $query->andFilterWhere(['between', 'created_at', strtotime($fromDate), strtotime($toDate)]);
        }

        return $dataProvider;
    }
}
