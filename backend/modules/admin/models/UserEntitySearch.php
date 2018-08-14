<?php

namespace backend\modules\admin\models;

use common\models\bid\BidEntity;
use Yii;
use common\models\user\User;
use yii\data\ActiveDataProvider;

/**
 * RestEmployeeEntitySearch represents the model behind the search form of `rest\modules\api\v1\employee\models\RestEmployeeEntity`.
 */
class UserEntitySearch extends User
{
    /**
     * String  of $name and $last_name
     * @var $full_name
     */
    public $full_name;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['status', 'full_name', 'email', 'phone_number'], 'string'],
            ['status', 'in', 'range' => [self::STATUS_VERIFIED, self::STATUS_UNVERIFIED, self::STATUS_BANNED]],
        ];
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
        $query =  $managers = (new \yii\db\Query())
            ->select(['user.email', 'user.phone_number', 'auth_assignment.item_name', 'user.status',
                'auth_assignment.user_id', 'user_profile.name', 'user_profile.last_name'])
            ->from('auth_assignment')
            ->where(['auth_assignment.item_name' => 'manager'])
            ->leftJoin('user', 'auth_assignment.user_id = user.id ')
            ->leftJoin('user_profile', 'user_profile.user_id = user.id ');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'phone_number', $this->phone_number]);
        $query->andFilterWhere(['like', 'email', $this->email]);
        $query->andFilterWhere(['or',['like', 'user_profile.name', $this->full_name],['like', 'user_profile.last_name', $this->full_name]]);

        return $dataProvider;
    }
}
