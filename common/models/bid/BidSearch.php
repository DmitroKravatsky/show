<?php

namespace common\models\bid;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * BidSearch represents the model behind the search form of `common\models\bid\BidEntity`.
 */
class BidSearch extends BidEntity
{
    public $dateRange;

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
            [['id', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['processed_by'], 'string'],
            [
                [
                    'full_name', 'phone_number', 'email', 'status', 'from_payment_system',
                    'to_payment_system', 'from_wallet', 'to_wallet', 'from_currency', 'to_currency',
                    'dateRange', 'processed_by', 'processed'
                ],
                'safe'
            ],
            [['from_sum', 'to_sum'], 'number'],
        ];
    }

    /**
     * @return array
     */
    public function scenarios(): array
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
        $query = BidEntity::find()->joinWith(['managerProfile']);

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
            'from_sum' => $this->from_sum,
            'to_sum' => $this->to_sum,
            'processed' => $this->processed,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'phone_number', $this->phone_number])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'from_payment_system', $this->from_payment_system])
            ->andFilterWhere(['like', 'to_payment_system', $this->to_payment_system])
            ->andFilterWhere(['like', 'from_wallet', $this->from_wallet])
            ->andFilterWhere(['like', 'to_wallet', $this->to_wallet])
            ->andFilterWhere(['like', 'from_currency', $this->from_currency])
            ->andFilterWhere(['like', 'to_currency', $this->to_currency])
            ->andFilterWhere([
                'or',
                ['like', 'user_profile.name', $this->processed_by],
                ['like', 'user_profile.last_name', $this->processed_by]
            ])->andFilterWhere([
                'or',
                ['like', 'bid.name', $this->full_name],
                ['like', 'bid.last_name', $this->full_name]
            ]);

        if (!empty($this->dateRange) && strpos($this->dateRange, '-') !== false) {
            list($fromDate, $toDate) = explode(' - ', $this->dateRange);
            $query->andFilterWhere(['between', 'bid.created_at', strtotime($fromDate), strtotime($toDate)]);
        }

        return $dataProvider;
    }
}
