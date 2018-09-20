<?php

namespace common\models\reserve;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\reserve\ReserveEntity;

/**
 * ReserveEntitySearch represents the model behind the search form of `common\models\reserve\ReserveEntity`.
 */
class ReserveEntitySearch extends ReserveEntity
{
    public $createdDateRange;
    public $updatedDateRange;
    public $payment_system;
    public $currency;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['id', 'visible', 'created_at', 'updated_at'], 'integer'],
            [['createdDateRange', 'updatedDateRange', 'payment_system', 'currency',], 'safe'],
            [['sum'], 'number'],
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
        $query = ReserveEntity::find()->joinWith('paymentSystem');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'sum'                            => $this->sum,
            static::tableName() . '.visible' => $this->visible,
            'currency'                       => $this->currency,
        ]);

        $query->andFilterWhere(['like', 'name', $this->payment_system]);

        if (!empty($this->createdDateRange) && strpos($this->createdDateRange, '-') !== false) {
            list($fromDate, $toDate) = explode(' - ', $this->createdDateRange);
            $query->andFilterWhere(['between', static::tableName() . '.created_at', strtotime($fromDate), strtotime($toDate)]);
        }

        if (!empty($this->updatedDateRange) && strpos($this->updatedDateRange, '-') !== false) {
            list($fromDate, $toDate) = explode(' - ', $this->updatedDateRange);
            $query->andFilterWhere(['between', static::tableName() . '.updated_at', strtotime($fromDate), strtotime($toDate)]);
        }

        return $dataProvider;
    }
}
