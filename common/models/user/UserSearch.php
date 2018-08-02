<?php

namespace common\models\user;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\user\User;

/**
 * UserSearch represents the model behind the search form of `common\models\user\User`.
 */
class UserSearch extends User
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'terms_condition', 'recovery_code', 'created_recovery_code', 'created_refresh_token', 'verification_code'], 'integer'],
            [['auth_key', 'password', 'password_reset_token', 'email', 'phone_number', 'source', 'source_id', 'refresh_token', 'status', 'invite_code', 'invite_code_status'], 'safe'],
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
        $query = User::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'terms_condition' => $this->terms_condition,
            'recovery_code' => $this->recovery_code,
            'created_recovery_code' => $this->created_recovery_code,
            'created_refresh_token' => $this->created_refresh_token,
            'verification_code' => $this->verification_code,
        ]);

        $query->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone_number', $this->phone_number])
            ->andFilterWhere(['like', 'source', $this->source])
            ->andFilterWhere(['like', 'source_id', $this->source_id])
            ->andFilterWhere(['like', 'refresh_token', $this->refresh_token])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'invite_code', $this->invite_code])
            ->andFilterWhere(['like', 'invite_code_status', $this->invite_code_status]);

        return $dataProvider;
    }
}
