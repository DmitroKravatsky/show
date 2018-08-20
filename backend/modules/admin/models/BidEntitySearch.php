<?php

namespace backend\modules\admin\models;

use common\models\bid\BidEntity;
use Yii;
use common\models\user\User;
use yii\data\ActiveDataProvider;

/**
 * RestEmployeeEntitySearch represents the model behind the search form of `rest\modules\api\v1\employee\models\RestEmployeeEntity`.
 */
class BidEntitySearch extends BidEntity
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
            [
                [
                    'id', 'from_currency', 'to_currency', 'processed', 'processed_by',
                    'from_sum', 'to_sum', 'created_at'
                ],
                'integer'
            ],
            [
                [
                    'created_by', 'status', 'full_name', 'from_payment_system', 'to_payment_system',
                    'from_wallet', 'to_wallet', 'email', 'phone_number'
                ],
                'string'
            ],
            ['status', 'in', 'range' =>
                [
                    self::STATUS_NEW, self::STATUS_REJECTED, self::STATUS_DONE,
                    self::STATUS_PAID_BY_US, self::STATUS_PAID_BY_CLIENT,
                ],
            ],
            ['updated_at', 'safe']
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
        $query = BidEntity::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'created_by' => $this->created_by,
            'id' => $this->id,
            'status' => $this->status,
            'processed' => $this->processed,
            'processed_by' => $this->processed_by,
        ]);

        if ($this->updated_at && strpos($this->updated_at, '-') !== false) {
            list($fromDate, $toDate) = explode(' - ', $this->updated_at);
            $query->andFilterWhere(['between', 'updated_at', strtotime($fromDate), strtotime($toDate)]);
        }

        $query->andFilterWhere(['like', 'phone_number', $this->phone_number]);
        $query->andFilterWhere(['like', 'email', $this->email]);
        $query->andFilterWhere(['or',['like', 'name', $this->full_name],['like', 'last_name', $this->full_name]]);

        return $dataProvider;
    }
}
