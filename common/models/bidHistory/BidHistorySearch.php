<?php

namespace common\models\bidHistory;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * BidHistorySearch represents the model behind the search form of `common\models\bidHistory\BidHistory`.
 */
class BidHistorySearch extends BidHistory
{
    public $time_range;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['id', 'bid_id', 'time', 'processed_by',], 'integer'],
            [['status', 'time_range',], 'safe'],
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
        $query = BidHistory::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'bid_id' => $this->bid_id,
            'processed_by' => $this->processed_by,
            'time' => $this->time,
        ]);

        $query->andFilterWhere(['like', 'status', $this->status]);

        if (!empty($this->time_range) && strpos($this->time_range, '-') !== false) {
            list($fromDate, $toDate) = explode(' - ', $this->time_range);
            $query->andFilterWhere(['between', 'time', strtotime($fromDate), strtotime($toDate)]);
        }

        return $dataProvider;
    }
}
