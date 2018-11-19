<?php

namespace common\models\exchangeRates;

use common\models\paymentSystem\PaymentSystem;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\exchangeRates\ExchangeRates;

/**
 * ExchangeRatesSearch represents the model behind the search form of `common\models\exchangeRates\ExchangeRates`.
 */
class ExchangeRatesSearch extends ExchangeRates
{
    public $dateRange;
    public $from_currency;
    public $to_currency;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'created_at', 'updated_at'], 'integer'],
            [['value'], 'number'],
            [['dateRange', 'from_payment_system_id', 'to_payment_system_id', 'to_currency', 'from_currency'], 'safe',],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
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
        $query = ExchangeRates::find()
            ->joinWith('fromPaymentSystem as from_payment_system')
            ->joinWith('toPaymentSystem as to_payment_system');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'value' => $this->value,
        ]);

        $query->andFilterWhere(['from_payment_system.name' => $this->from_payment_system_id]);
        $query->andFilterWhere(['to_payment_system.name' => $this->to_payment_system_id]);
        $query->andFilterWhere(['from_payment_system.currency' => $this->from_currency]);
        $query->andFilterWhere(['to_payment_system.currency' => $this->to_currency]);

        if (!empty($this->dateRange) && strpos($this->dateRange, '-') !== false) {
            list($fromDate, $toDate) = explode(' - ', $this->dateRange);
            $query->andFilterWhere(['between', static::tableName() . '.created_at', strtotime($fromDate), strtotime($toDate)]);
        }

        return $dataProvider;
    }
}
